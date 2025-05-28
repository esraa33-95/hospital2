<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreAllergy;
use App\Http\Requests\Api\admin\UpdateAllergy;
use App\Models\Allergy;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;
use App\Traits\Response;
use App\Transformers\admin\AllergyTransform;


class AllergyController extends Controller
{
    use Response;
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAllergy $request,string $id)
    {
         $data = [
        'user_id' => auth()->id(),
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $allergy = Allergy::create($data);

       $allergy = fractal($allergy,new AllergyTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_allergy'), $allergy, 201);
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAllergy $request, string $id)
    {
       $data = [
        'en' => ['name' => $request->name_en],
        'ar' => ['name' => $request->name_ar],
    ];

    $allergy = Allergy::findOrFail($id);

     $allergy->update($data);

      $allergy = fractal($allergy, new AllergyTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_allergy'), $allergy, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
         $allergy = Allergy::findOrFail($id);

        if($allergy->users()->exists())
        {
              return  $this->responseApi(__('messages.Nodelete_allergy'),403); 
        }

        $allergy->delete();
        
        return  $this->responseApi(__('messages.delete_allergy'),204);
    }
}
