<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\RegisterRequest;
use App\Http\Requests\Api\front\Updatebyname;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\Common;
use App\Traits\Response;
use App\Transformers\UserTransform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
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
    
        $query = User::where('user_type', 2);

    if ($search) 
    {
        $query->where(function ($q) use ($search) {
             $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('mobile', 'like', '%' . $search . '%');
        });
    }
    
    $total = $query->count(); 

    $doctors = $query->skip($skip ?? 0)->take($take ?? 0)->get();

     $doctors =  fractal()->collection($doctors)
                  ->transformWith(new UserTransform())
                   ->toArray();

    return $this->responseApi('',$doctors,200,['count' => $total]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(RegisterRequest $request)
    {
        $data = $request->validated();


        $data['password'] = Hash::make($data['password']);  

         $doctor =  User::create($data);

         if ($request->hasFile('image')) 
         {
            $doctor->addMedia($request->file('image'))
                   ->toMediaCollection('image');
        }

        $doctor = fractal($doctor, new UserTransform())->toArray();

         return $this->responseApi(__('messages.store_doctors'),$doctor,200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $doctor = User::where('id',$id)
       ->where('user_type',2)
       ->first();

       if (!$doctor)
        {
        return $this->responseApi(__('messages.trash'), 404);
       }

       $doctor = fractal()
                  ->item($doctor)
                 ->transformWith(new UserTransform())
                 ->toArray();

       return $this->responseApi('', $doctor, 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function updatename(Updatebyname $request)
{
    $request->validated();

    $uuid = $request->input('uuid');

    $doctor = User::withTrashed()
    ->where('uuid', $uuid)
    ->where('user_type',2)
    ->first();

    if (!$doctor) 
    {
        return $this->responseApi(__('messages.trash'), 404);
    }

    if ($doctor->trashed()) 
    {
        return $this->responseApi(__('messages.trash'), 403);
    }

    if($doctor->name !== $request->input('name'))
    {
        $doctor->name = $request->input('name');

    }

    $doctor->save();

     $doctor = fractal()
        ->item($doctor)
        ->transformWith(new UserTransform())
        ->toArray();

    return $this->responseApi(__('messages.update_doctors'),$doctor,200);

}

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $uuid = $request->input('uuid');
    
        $doctor = User::where('user_type',2)
        ->where('uuid', $uuid)
        ->first();
    
        if (!$doctor) 
        {
            return $this->responseApi(__('messages.trash'), 404);
        }

        $doctor->delete();
    
        return $this->responseApi(__('messages.delete_doctor'), 200);
    }




//filter doctor
//     public function filterDoctors(Request $request)
// {
//     $take = $request->input('take'); 

//     $query = User::where('user_type', 2); 
    
//     if ($request->filled('name')) {
//         $query->where('name', 'like', '%' . $request->name . '%');
//     }

//     if ($request->filled('number_rate')) {
//         $query->where('number_rate', 'like', '%' . $request->number_rate . '%');
//     }
    
//     if ($request->filled('department')) {
//         $query->whereHas('department', function ($q) use ($request) {
//             $q->where('name', 'like', '%' . $request->department . '%');
//         });
//     }

//     if ($request->filled('sort_by')) 
//     {
//         $Sorts = ['department', 'number_rate', 'name'];
//         $sortBy = $request->get('sort_by');
//         $input = $request->get('sort_order', 'high to low');

//        $sortOrder = match ($input) {
//         'low to high' => 'asc',
//         'high to low' => 'desc',
//     };

//         if (in_array($sortBy, $Sorts) )
//          {
//             $query->orderBy($sortBy, $sortOrder);
//         }
//     }

//     $total = $query->count(); 

//     $doctors = $query->take($take)->get();

//     return response()->json([
//         'data' => DoctorResource::collection($doctors),
//         'total' => $total,
//         'take' => $take,
//     ]);
// }
    
}
