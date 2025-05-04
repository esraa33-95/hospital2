<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\admin\Updatebyname;
use App\Http\Requests\Api\front\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\Common;
use App\Traits\Response;
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

    if (!is_null($skip)) 
        {
            $query->skip($skip);
        }

    $patient = $query->take($take)->get();

    if ($patient->isEmpty()) 
    {
        return $this->responseApi(__('No patients found.'), 404);
    }

    return response()->json([
        'data' => UserResource::collection($patient),
        'total' => $total,
        'skip' => $skip,
        'take' => $take,
    ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(RegisterRequest $request)
    {
        $data = $request->validated();
    
        if($request->hasFile('image'))
        {
            $data['image'] = $this->uploadFile($request->image,'assets/images');
        }

        $data['password'] = Hash::make($data['password']);  

         $patient =  User::create($data);

         return $this->responseApi(__('patient created successfully'),$patient,200);
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
        return $this->responseApi(__('patient not found'), 404);
       }

        return new UserResource($patient);

    }

    
    /**
     * Update the specified resource in storage.
     */
    public function updatename(Updatebyname $request)
{
    $request->validated();

    $types = $request->input('user_type');
    $email = $request->input('email');

    $patient = User::where('user_type',$types)
    ->whereNull('deleted_at')
    ->where('email', $email)
    ->first();

    if (!$patient) 
    {
        return $this->responseApi(__('patient not found'), 404);
    }

    $patient->name = $request->input('name');
    $patient->save();

    return $this->responseApi(__('patient name updated successfully'), 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
{
    $userType = $request->input('user_type');

    $name = $request->input('name');

    $patient = User::where('user_type', $userType)
                   ->where('name', $name)
                   ->first();

    if (!$patient) 
    {
        return $this->responseApi(__('No patient found'), 404);
    }

    $patient->delete();

    return $this->responseApi(__('Patient deleted successfully'), 200);
}

    
}
