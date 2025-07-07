<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreAdminRequest;
use App\Http\Requests\Api\front\updateUser;
use App\Models\User;
use App\Traits\Common;
use App\Traits\Response;
use App\Transformers\admin\UserTransform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use League\Fractal\Serializer\ArraySerializer;

class PatientController extends Controller
{
    use Common;
    use Response;
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang',app()->getLocale());


        $query = User::where('user_type', 3);

    if ($search) 
    {
        $query->where(function ($q) use ($search ) {
             $q->where('name', 'like', '%' . $search . '%')
              ->orwhere('email', 'like', '%' . $search . '%')
              ->orwhere('mobile', 'like', '%' . $search . '%');
        });
    }
   
    $total = $query->count(); 

    $patients = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $patients =  fractal()->collection($patients)
                  ->transformWith(new UserTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();
    
    return $this->responseApi('',$patients,200,['count' => $total]);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $data = $request->validated();
    
        $data['password'] = Hash::make($data['password']);  

         $patient =  User::create($data);

         if ($request->hasFile('image')) 
         {
            $patient->addMedia($request->file('image'))
                     ->toMediaCollection('image');
        }

        $patient = fractal($patient, new UserTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

         return $this->responseApi(__('messages.store_patients'),$patient,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $patient = User::where('user_type',3)
       ->where('id', $id)
       ->firstorFail();
     
      $patient = fractal()
                 ->item($patient)
                 ->transformWith(new UserTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

       return $this->responseApi('', $patient, 200);
       
    }
   
    /**
     * Update the specified resource in storage.
     */
    public function update(updateUser $request ,string $id)
{
    $data = $request->validated();

    $patient = User::where('user_type',3)
                    ->where('id',$id)
                    ->firstOrFail();
  
   foreach (['name', 'email', 'mobile', 'department_id', 'user_type'] as $field)
    {
        if (isset($data[$field])) 
        {
            $patient->$field = $data[$field];
        }
    }

    if ($request->hasFile('image')) 
     {
               $patient->clearMediaCollection('image');
                $patient->addMedia($request->file('image'))
                       ->toMediaCollection('image');
    }

        if(!isset($patient->password ) && !Hash::check($patient->password ,$data['password']) )
        {
          $patient->password = Hash::make($data['password']);

        }

    $patient->save();

     $patient = fractal()
                  ->item($patient)
                  ->transformWith(new UserTransform())
                  ->serializeWith(new ArraySerializer())
                  ->toArray();

    return $this->responseApi(__('messages.update_patient'),$patient,200);
}

  /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = User::where('user_type',3)
                          ->where('id', $id)
                         ->firstorFail();
    
        $patient->delete();
    
        return $this->responseApi(__('messages.delete_patient'),200);
    }
}
