<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    use Common;
   
    
    public function show(string $id)
    {
        $patient = Patient::FindOrFail($id);
    
        if ($patient) {
            return response()->json([
                'msg' => 'Patient profile',
                'data' => $patient,
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
    

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
        'name'=>'nullable|string',
        'email' => 'nullable|email|unique:users,email,' . $id,
        'phone' => [ 'nullable', 'regex:/^01[0125][0-9]{8}$/', 'unique:users,mobile,' . $id ],
        'image'=>'nullable|mimes:png,jpg,jpeg',

        ]);

        if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assests/images'); 
        }

    $patient = Patient::find($id);

    if (!$patient) 
    {
        return response()->json([
            'msg' => 'patient not found',
            'status' => 404,
        ]);
    }

    $patient->update($data);

    return response()->json([
        'msg' => 'patient updated successfully',
        'data' => $patient,
        'status' => 200,
    ]);


 }

    public function changePassword(Request $request)
{
     $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);
  
    $user = $request->user();

    if (!Hash::check($request->current_password, $user->password)) 
    {
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
