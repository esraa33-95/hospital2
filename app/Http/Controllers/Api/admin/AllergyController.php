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
     * Display a listing of the resource.
     */
     public function index(Request $request)
{
    $search = $request->input('search');
    $take = $request->input('take'); 
    $skip = $request->input('skip');  
   
    $query = Allergy::query();

      if ($search)
    {
        $query->where('allergy_type', 'like', '%' . $search . '%');
    }

    $total = $query->count();

    $allergy  = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $allergy = fractal()
                   ->collection($allergy)
                   ->transformWith(new AllergyTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $allergy, 200, ['count' =>$total]);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAllergy $request)
    {
        $data = $request->validated();

       $allergy = Allergy::create($data);

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

        if( $allergy->user_id !== auth()->id())
        {
           return $this->responseApi(__('messages.not_show'),403);
        }

        $allergy = fractal()
                  ->item($allergy)
                  ->transformWith(new AllergyTransform())
                  ->serializeWith(new ArraySerializer())
                  ->toArray();

        return $this->responseApi('', $allergy, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAllergy $request, string $id)
    {
      $data = $request->validated();

      $allergy = Allergy::findOrFail($id);

      if( $allergy->user_id !== auth()->id())
        {
            return  $this->responseApi(__('messages.not_updated'),403); 
        }

      $allergy->update($data);

      $allergy = fractal($allergy, new AllergyTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_allergy'), $allergy, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $allergy = Allergy::with('users')->findOrFail($id);

        if( $allergy->user_id !== auth()->id())
        {
            return  $this->responseApi(__('messages.Nodelete_allergy'),403); 
        }

        $allergy->delete();
        
        return  $this->responseApi(__('messages.delete_allergy'),204);
    }
}
