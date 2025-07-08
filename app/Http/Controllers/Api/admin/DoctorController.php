<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreAdminRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\Response;
use App\Http\Requests\Api\front\updateUser;
use App\Traits\Common;
use App\Transformers\admin\UserTransform;
use Illuminate\Support\Facades\Hash;
use League\Fractal\Serializer\ArraySerializer;

class DoctorController extends Controller
{
    use Response;
    use Common;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
       
    
        $query = User::where('user_type', 2);

    if ($search) 
    {
        $query->where(function ($q) use ($search) {
             $q->where('name', 'like', '%' . $search . '%')
              ->orwhere('email', 'like', '%' . $search . '%')
              ->orwhere('mobile', 'like', '%' . $search . '%');
        });
    }

     $total = $query->count();
  
      $doctors = $query->skip($skip ?? 0)->take($take ?? $total)->get();

      $doctors =  fractal()->collection($doctors)
                  ->transformWith(new UserTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();
    
     return $this->responseApi('',$doctors,200,['count' => $total]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $data = $request->validated();
    
        $data['password'] = Hash::make($data['password']);  

         $doctor =  User::create($data);

         if ($request->hasFile('image')) 
         {
            $doctor->addMedia($request->file('image'))
                   ->toMediaCollection('image');
        }

        $doctor = fractal($doctor, new UserTransform())
                  ->serializeWith(new ArraySerializer())
                  ->toArray();

         return $this->responseApi(__('messages.store_doctors'),$doctor,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $doctor = User::where('user_type',2)
                        ->where('id',$id)
                         ->firstorFail();
   
        $doctor = fractal()
                 ->item($doctor)
                 ->transformWith(new UserTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

       return $this->responseApi('', $doctor, 200);
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(updateUser $request ,string $id)
{
    $data = $request->validated();

    $doctor = User::where('user_type',2)
                   ->where('id',$id)
                   ->firstOrFail();

       
   foreach (['name', 'email', 'mobile', 'department_id', 'user_type'] as $field)
    {
        if (isset($data[$field])) 
        {
            $doctor->$field = $data[$field];
        }
    }

     if ($request->hasFile('image')) 
     {
               $doctor->clearMediaCollection('image');
                $doctor->addMedia($request->file('image'))
                       ->toMediaCollection('image');
    }

        if(!isset($doctor->password ) && !Hash::check($doctor->password ,$data['password']) )
        {
          $doctor->password = Hash::make($data['password']);

        }

       $doctor->save();
    
       $doctor = fractal()
                 ->item($doctor)
                 ->transformWith(new UserTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

    return $this->responseApi(__('messages.update_doctors'),$doctor,200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $doctor = User::where('user_type', 2) 
                   ->where('id',$id)
                   ->firstorFail();
   

    $doctor->delete();

    return $this->responseApi(__('messages.delete_doctor'),[],200);
}

}
