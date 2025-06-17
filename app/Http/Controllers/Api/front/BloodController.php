<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreBlood;
use App\Http\Requests\Api\front\UpdateBlood;
use League\Fractal\Serializer\ArraySerializer;
use App\Models\Blood;
use App\Traits\Response;
use App\Transformers\front\BloodTransform;
use Illuminate\Http\Request;

class BloodController extends Controller
{
    use Response;
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlood $request)
    {    
    $data =  $request->validated();

     $user = auth()->user();

     $user->blood()->sync($data);

     $blood = Blood::findOrfail($request->blood_id);

       $blood = fractal($blood,new BloodTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_blood'), $blood, 201);
    }

      public function show(string $id)
    {     
       $user = auth()->user();

       $blood = $user->blood()->first();

         $blood = fractal()
                 ->item($blood)
                 ->transformWith(new BloodTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$blood,200);
    }
 
    /**
     * Update the specified resource in storage.
     */
     public function update(UpdateBlood $request, string $id)
    {  
         $data = $request->validated();

          $user = auth()->user();

        $user->blood()->sync($data);

       $blood = Blood::findOrFail($id);

      $blood = fractal($blood, new  BloodTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_blood'), $blood, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $user = auth()->user();
        
        $blood = $user->blood()
                       ->where('user_id',$user->id)
                       ->firstOrfail();

        $blood->delete();
        
        return  $this->responseApi(__('messages.delete_blood'),204);
    }
}
