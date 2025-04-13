<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Traits\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{

    use Common;
    public function show($id)
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
        'name'=>'required|string',
        'email' => 'required|email|unique:users,email,' . $id,
        'phone' => [ 'required', 'regex:/^01[0125][0-9]{8}$/', 'unique:users,mobile,' . $id ],
        'image'=>'nullable|mimes:png,jpg,jpeg',
        'specialization'=>'required|string',
        'department_id'=>'required|integer|exists:departments,id',

        ]);

        if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assests/images'); 
       }

            $doctor = Doctor::where('id',$id)->FindOrFail($id);

        if($doctor)
        {
       $doctor->update($data);

       return response()->json([
        'msg' => 'doctor updating successfully',
        'data' => $doctor,
        'status' => 200,
        ]);
      }
else{
    return response()->json([
        'msg' => ' no doctor',
        'data' => [],
        
    ]);
}

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
