<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\front\RegisterRequest;
use App\Http\Requests\Api\front\Updatebyname;
use App\Models\User;
use App\Traits\Common;
use App\Traits\Response;
use App\Transformers\UserTransform;
use Illuminate\Support\Facades\Hash;


class PatientController extends Controller
{
    use Response;
    use Common;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
    
        $query = User::where('user_type', 3);

    if ($search) 
    {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('mobile', 'like', '%' . $search . '%');
        });
    }


    $total = $query->count(); 

    $patients = $query->skip($skip ?? 0)->take($take ?? 0)->get();

    $patients =  fractal()->collection($patients)
                  ->transformWith(new UserTransform())
                   ->toArray();

    return $this->responseApi('',$patients,200,['count' => $total]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(RegisterRequest $request)
    {
        $data = $request->validated();
  
        $data['password'] = Hash::make($data['password']);  

         $patient =  User::create($data);

         if ($request->hasFile('image')) 
         {
            $patient->addMedia($request->file('image'))
                   ->toMediaCollection('image');
        }

        $patient= fractal($patient, new UserTransform())->toArray();

         return $this->responseApi(__('messages.store_patients'),$patient,200);
    }

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $patient = User::where('id',$id)
      ->where('user_type',3)
       ->first();

       if (!$patient)
        {
        return $this->responseApi(__('messages.trash'), 404);
       }
       $patient = fractal()
                  ->item($patient)
                 ->transformWith(new UserTransform())
                 ->toArray();

       return $this->responseApi('', $patient, 200);

       
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function updatename(Updatebyname $request)
{
    $request->validated();

    $uuid = $request->input('uuid');

    $patient = User::withTrashed()
    ->where('uuid', $uuid)
    ->where('user_type',3)
    ->first();

    if (!$patient) 
    {
        return $this->responseApi(__('messages.trash'), 404);
    }

    if ($patient->trashed()) 
    {
        return $this->responseApi(__('messages.trash'), 403);
    }

    if($patient->name !== $request->input('name'))
    {
        $patient->name = $request->input('name');
    }
  
    $patient->save();

     $patient = fractal()
        ->item($patient)
        ->transformWith(new UserTransform())
        ->toArray();

    return $this->responseApi(__('messages.update_patient'),$patient,200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $uuid = $request->input('uuid');
    
        $patient = User::where('user_type',3)
        ->where('uuid',$uuid)
        ->first();
    
        if (!$patient) 
        {
            return $this->responseApi(__('messages.trash'), 404);
        }
 
        $patient->delete();
    
        return $this->responseApi(__('messages.delete_patient'), 200);
    }
    

    
}
