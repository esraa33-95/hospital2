<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\ChangePassword;
use App\Http\Requests\Api\front\updateUser;
use App\Models\Document;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\Common;
use App\Traits\Response;
use App\Transformers\UserTransform;
use Illuminate\Support\Facades\Hash;


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
                 ->toArray();

       return $this->responseApi('', $user, 200);
          
    }


    //upload image
    public function uploadimage(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,jpg,png,pdf|max:5120', 

        ]);

        if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assets/images'); 
        }

        return $this->responseApi(__('messages.upload'));
    }

//update data
    public function update(updateUser $request)
    {
        $data = $request->validated();

        $uuid = $request->input('uuid');
        
         if ($request->hasFile('image')) {
        $data['image'] = $this->uploadFile($request->file('image'), 'assets/images'); 
    }

        $types = [2,3];

        $user = User::withTrashed()
        ->whereIn('user_type', $types)
        ->where('uuid', $uuid)
       ->first();

    if (!$user) {
        return $this->responseApi(__('messages.trash'), 404);
    }

    if ($user->trashed()) {
        return $this->responseApi(__('messages.trash'), 403);
    }

    $user->fill($data)->save();

    $user = fractal()
        ->item($user)
        ->transformWith(new UserTransform())
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

//     public function uploadimage(Request $request, string $id)
// {
//     $request->validate([
//         'file' => 'required|mimes:jpeg,jpg,png,pdf|max:5120', 
//     ]);

//     if ($request->hasFile('file')) {
      
//         $file = $request->file('file');
//         $filePath = $file->store('assets/images', 'public'); 

       
//         $document = new Document();
//         $document->file_path = $filePath;
//         $document->file_type = $file->getClientOriginalExtension(); 
//         $document->save();

//         return response()->json([
//             'message' => 'File uploaded and saved to database.',
//             'document' => $document,
//         ], 201);
//     }

//     return response()->json([
//         'message' => 'No file was uploaded.',
//     ], 400);
// }







}