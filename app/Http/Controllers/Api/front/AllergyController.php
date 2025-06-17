<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreAllergy;
use App\Http\Requests\Api\front\UpdateAllergy;
use App\Models\Allergy;
use App\Traits\Response;
use App\Transformers\front\AllergyTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class AllergyController extends Controller
{
    use Response;
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAllergy $request)
    {
         $data =  $request->validated();

        $user = auth()->user();

       $user->allergies()->attach($data);

     $allergy = Allergy::findOrfail($request->allergy_id);

       $allergy = fractal($allergy,new AllergyTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_allergy'), $allergy, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {  
       $allergy = Allergy::findOrFail($id);

         $allergy = fractal()
                 ->item($allergy)
                 ->transformWith(new AllergyTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$allergy,200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAllergy $request, string $id)
    {
          $data = [
        'en' => ['name' => $request->name_en],
        'ar' => ['name' =>  $request->name_ar],
    ];

    $allergy = Allergy::findOrFail($id);

     $allergy->update($data);

      $allergy = fractal($allergy, new AllergyTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_disease'), $allergy, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $user = auth()->user();

        $allergy = $user->allergies()
                    ->where('user_id',$user->id)
                    ->firstOrfail();
  
       // $allergy->delete();
         $user->Allergies()->detach($allergy->id);
        
       return  $this->responseApi(__('messages.delete_disease'),204);
    }
}
