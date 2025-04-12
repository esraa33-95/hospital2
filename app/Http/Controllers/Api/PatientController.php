<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    use Common;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patient = Patient::get();
       
    if($patient){
    
    return response()->json([
        'msg'=>'patient profile',
        'data'=>$patient,
        'status'=>200,
       ]);
   }
else{
    return response()->json([
        'msg'=>' no patient profile',
        'data'=>[],
        
       ]);
}
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::where('id', $id)->whereHas('role', function ($q) {
            $q->where('name', 'patient'); 
        })->first();
    
        if ($user) {
            return response()->json([
                'msg' => 'Patient profile',
                'data' => $user,
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'msg' => 'No patient profile found',
                'data' => [],
                'status' => 404,
            ]);
        }
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
        'name'=>'required|string',
        'email' => 'required|email|unique:users,email,' . $id,
        'phone' => [ 'required', 'regex:/^01[0125][0-9]{8}$/', 'unique:users,mobile,' . $id ],
        'image'=>'nullable|mimes:png,jpg,jpeg',

        ]);

        if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assests/images'); 
       }

    //    $patient = Patient::where('id',$id)->FindOrFail($id);

    $patient = User::where('id', $id)->whereHas('role', function ($q) {
        $q->where('name', 'patient');  })->first();

        if($patient)
        {
       $patient->update($data);

       return response()->json([
        'msg' => 'Patient updating successfully',
        'data' => $patient,
        'status' => 200,
    ]);
}
else{
    return response()->json([
        'msg' => ' no Patient',
        'data' => [],
        
    ]);
}

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function changePassword(Request $request)
{
     $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);
  
    $user = $request->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'msg' => 'Current password is incorrect',
            'status' => 400,
        ]);
    }

    $user->password = bcrypt($request->new_password);
    $user->save();

  return response()->json([
            'msg' => 'Password changed successfully',
            'status' => 200,
        ]);
      
   
}

public function deleteAccount(Request $request)
{
   
    auth()->user()->delete();

    return response()->json([
        'msg' => 'Account deleted successfully',
        'status' => 200,
    ]);
}


}
