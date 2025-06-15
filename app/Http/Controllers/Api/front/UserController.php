<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreCeritificate;
use App\Http\Requests\Api\admin\StoreExperience;
use App\Http\Requests\Api\admin\Updatecertificate;
use App\Http\Requests\Api\admin\UpdateExperience;
use App\Http\Requests\Api\front\ChangePassword;
use App\Http\Requests\Api\front\updateUser;
use App\Http\Requests\Api\front\uploadimageRequest;
use App\Models\Certificate;
use App\Models\Experience;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\Common;
use App\Traits\Response;
use App\Transformers\Admin\CertificateTransform;
use App\Transformers\admin\ExperienceTransform;
use App\Transformers\front\UserTransform;
use Illuminate\Support\Facades\Hash;
use League\Fractal\Serializer\ArraySerializer;


class UserController extends Controller
{
    use Common;
    use Response;

    //show profile 
    public function userprofile()
    {
       $user = auth()->user(); 

        $user = fractal()
                 ->item($user)
                 ->transformWith(new UserTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

       return $this->responseApi('', $user, 200);
          
    }

    //upload pdf
    public function uploadfile(uploadimageRequest $request,string $id)
    {
        $request->validated();

        $user = User::findOrFail($id);

        if ($request->hasFile('image'))
        {
             $user->clearMediaCollection('files');

             $user->addMedia($request->file('image'))
                 ->toMediaCollection('files');
        }

        return $this->responseApi(__('messages.uploadpdf'));
    }

//update data
    public function update(updateUser $request, string $id)
    {
        $data = $request->validated();

        $uuid = $request->input('uuid');

        $types = [2,3];

        $user = User::withTrashed()
         ->whereIn('user_type', $types)
         ->where('id',$id)
         ->where('uuid', $uuid)
         ->firstOrFail();

       if ($request->hasFile('image'))
        {
           $user->addMedia($request->file('image'))
                 ->toMediaCollection('image');
        }

    if ($user->trashed()) 
    {
        return $this->responseApi(__('messages.trash'), 403);
    }

    $user->fill($data)->save();

    $user = fractal()
        ->item($user)
        ->transformWith(new UserTransform())
         ->serializeWith(new ArraySerializer())
        ->toArray();

   return $this->responseApi(__('profile update successfully'),$user,200);
 }

//delete account

 public function deleteAccount(Request $request)
{
   $user = auth()->user()->delete();

    if (!$user) 
    {
        return $this->responseApi(__('messages.authentication'), 401);
    }

   return $this->responseApi(__('messages.trash'));
      
}


//change password 
public function changePassword(ChangePassword $request)
{
    $data = $request->validated(); 

    $user = auth()->user();

    if (!Hash::check($data['current_password'], $user->password)) 
    {
        return $this->responseApi(__('messages.change'), 422);
    }

    if (Hash::check($data['new_password'], $user->password)) 
    {
        return $this->responseApi(__('messages.different'), 422);
    }

    $user->password = Hash::make($data['new_password']);
    $user->save();

    return $this->responseApi(__('messages.change_password'),200);
}


//add certificate
public function addcertificate(StoreCeritificate $request, string $id)
    {
     $user = auth()->user();
                
     $data = [
        'user_id'=>$user->id,
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
      ];

         if ($user->user_type !== 2)
            {
            return $this->responseApi(__('unauthorized')); 
            }

       $certificate = Certificate::create($data);

       $certificate = fractal($certificate, new CertificateTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_certificate'), $certificate, 201);
    }

   //update certificate 
    public function updatecertificate(Updatecertificate $request, string $id)
    {
        $user = auth()->user();

      if ($user->user_type !== 2)
            {
            return $this->responseApi(__('unauthorized')); 
            }

         $data =[
            'ar'=>['name'=>$request->name_ar],
            'en' => ['name' => $request->name_en],
              ];

         $certificate = Certificate::findOrFail($id);

        $certificate->update($data);

      $certificate = fractal($certificate, new CertificateTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_certificate'), $certificate, 200);
    }


    //show certificate
    public function showcertificate(Request $request,string $id)
    {     
        $certificate = Certificate::findOrFail($id);

         $certificate = fractal()
                 ->item($certificate)
                 ->transformWith(new CertificateTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$certificate,200);
    }

//delete certificate
 public function deletecertificate( string $id)
    {
        $certificate = Certificate::with('users')->findOrFail($id);
    
        if($certificate)
        {
            return  $this->responseApi(__('messages.no_deletecerificate'),403); 
        }

        $certificate->delete();
        
        return  $this->responseApi(__('messages.delete_certificate'),204); 
    }

//experience
public function addexperience(StoreExperience $request,string $id)
    {
     $user = auth()->user();

      if ($user->user_type !== 2)
            {
            return $this->responseApi(__('unauthorized')); 
            }

    if ($request->current == 1) 
         {
            Experience::where('user_id', $user->id)
                        ->update(['current' => 0]);
        }
                
     $data = [
        'user_id'=>$user->id,
        'ar' => ['jobtitle' => $request->jobtitle_ar,
                  'organization' => $request->organization_ar],

        'en' => ['jobtitle' => $request->jobtitle_en,
                   'organization'=>$request->organization_en],

        'current'=> $request->current,         
      ];

       $experience = Experience::create($data);

       $experience = fractal($experience, new ExperienceTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_experience'), $experience, 201);
    }


//show experience

 public function showexperience(Request $request,string $id)
    {     
        $experience = Experience::findOrFail($id);

         $experience = fractal()
                 ->item($experience)
                 ->transformWith(new ExperienceTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$experience,200);
    }

//update experience
public function updateexperience(UpdateExperience $request,string $id)
    {
     $user = auth()->user();

      if ($user->user_type !== 2)
            {
            return $this->responseApi(__('unauthorized')); 
            }

     $experience = Experience::where('user_id', $user->id)->findOrFail($id);
  
      if ($request->current == 1) 
         {
            Experience::where('user_id', $user->id)
                       ->update(['current' => 0]);
        }
                
     $data = [
        'ar' => ['jobtitle' => $request->jobtitle_ar,
                  'organization' => $request->organization_ar],

        'en' => ['jobtitle' => $request->jobtitle_en,
                   'organization'=>$request->organization_en],

        'current'=> $request->current ?? 0,         
      ];
 
        $experience->update($data);

       $experience = fractal($experience, new ExperienceTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_experience'), $experience, 200);
    }

//deleteexperience
public function deleteexperience( string $id)
    {
        $experience = Experience::with('users')->findOrFail($id);
    
        if($experience)
        {
            return  $this->responseApi(__('messages.no_deleteexperience'),403); 
        }

        $experience->delete();
        
        return  $this->responseApi(__('messages.delete_experience'),204); 
    }


    //rate for doctor
// public function rate(Request $request,string $id)
//     {
//         $request->validate([
//             'rate'=>'required|decimal:1',
//         ]);

//         $doctor = User::where('user_type',2)->findOrfail($id);

//         Rate::create([
//             'user_id' => $doctor->id,
//             'rate' => $request->rate,
//         ]);

//     $ratings = Rate::where('user_id', $doctor->id)->get();

//     $average = round($ratings->avg('rate'), 2);
//     $number_rate = $ratings->count();

//      $doctor->number_rate = $number_rate;
//      $doctor->save();

//     return response()->json([
//         'average' => $average,
//         'total_rate' => $number_rate,
//     ]);     

// }

}