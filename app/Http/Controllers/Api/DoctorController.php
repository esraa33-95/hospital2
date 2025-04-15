<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{

    use Common;

    public function show(string $id)
    {
      $doctor = Doctor::FindOrFail($id);
    
        if ($doctor)
         {
            return response()->json([
                'msg' => 'Patient profile',
                'data' => $doctor,
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
        'specialization'=>'nullable|string',
        'department_id'=>'nullable|integer|exists:departments,id',

        ]);

        if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assests/images'); 
       }

    $doctor = Doctor::find($id);

    if (!$doctor) {
        return response()->json([
            'msg' => 'Doctor not found',
            'status' => 404,
        ]);
    }

    $doctor->update($data);

    return response()->json([
        'msg' => 'Doctor updated successfully',
        'data' => $doctor,
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
