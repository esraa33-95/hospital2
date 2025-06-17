<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreDisease;
use App\Http\Requests\Api\front\UpdateDisease;
use App\Models\Disease;
use App\Traits\Response;
use App\Transformers\front\DiseaseTransform;
use League\Fractal\Serializer\ArraySerializer;
use Illuminate\Http\Request;

class DiseaseController extends Controller
{
    use Response;
    
    /**
     * Store a newly created resource in storage.
     */
   public function store(StoreDisease $request)
    {
     $data =  $request->validated();

     $user = auth()->user();

     $user->diseases()->attach($data);

     $disease = Disease::findOrfail($request->disease_id);

       $disease = fractal($disease,new DiseaseTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_disease'), $disease, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {     
         $disease = Disease::findOrFail($id);

         $disease = fractal()
                 ->item($disease)
                 ->transformWith(new DiseaseTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$disease,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDisease $request, string $id)
    { 
          $data = [
        'en' => ['name' => $request->name_en],
        'ar' => ['name' =>  $request->name_ar],
    ];

    $disease = Disease::findOrFail($id);

     $disease->update($data);

      $disease = fractal($disease, new DiseaseTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_disease'), $disease, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
 public function delete(string $id)
    {
        $user = auth()->user();

        $disease = $user->diseases()
                    ->where('user_id',$user->id)
                    ->firstOrfail();
  
      //  $disease->delete();
        $user->diseases()->detach($disease->id);
        
        return  $this->responseApi(__('messages.delete_disease'),204);
    }

}
