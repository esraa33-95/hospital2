<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreSurgery;
use App\Http\Requests\Api\admin\UpdateSurgery;
use League\Fractal\Serializer\ArraySerializer;
use App\Models\Surgery;
use App\Traits\Response;
use App\Transformers\admin\SurgeryTransform;
use Illuminate\Http\Request;

class SurgeryController extends Controller
{
    use Response;
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurgery $request,string $id)
    {
          $user = auth()->user();

        $data = $request->validated();

        $data['user_id']= $user->id;

       $surgery = Surgery::create($data);

       $surgery = fractal($surgery,new SurgeryTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_surgery'), $surgery, 201);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurgery $request, string $id)
    {
        $user = auth()->user();

    $surgery = Surgery::where('id', $id)
                      ->where('user_id', $user->id) 
                      ->firstOrFail();
                      
     $data = $request->validated();

      $surgery->update($data);

      $surgery = fractal($surgery, new SurgeryTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_surgery'), $surgery, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $surgery = Surgery::with('users')->findOrFail($id);

         if ($surgery) {
        
            return  $this->responseApi(__('messages.Nodelete_surgery'),403); 
        }

        $surgery->delete();
        
        return  $this->responseApi(__('messages.delete_surgery'),204);
    }
}

