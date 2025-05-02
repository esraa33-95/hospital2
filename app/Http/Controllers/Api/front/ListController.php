<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Response;

class ListController extends Controller
{
    use Response;

    public function departments()
    {
      $department = Department::get();

      return $this->responseApi(__('all departments'),$department,200);

    }

    public function doctors()
    {
        $doctors = User::where('user_type',2)->get();

        return $this->responseApi(__('all doctors'),$doctors,200); 
    }

    public function patients()
    {
        $patients = User::where('user_type',3)->get();

        return $this->responseApi(__('all patients'),$patients,200); 
    }
}
