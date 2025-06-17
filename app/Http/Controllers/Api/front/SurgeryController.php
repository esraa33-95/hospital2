<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreSurgery;
use App\Http\Requests\Api\front\UpdateSurgery;
use App\Models\Surgery;
use App\Traits\Response;
use App\Transformers\front\SurgeryTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class SurgeryController extends Controller
{
    use Response;


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurgery $request)
    {
    $data =  $request->validated();

     $user = auth()->user();

     $user->surgeries()->attach($data);

     $surgery = Surgery::findOrfail($request->surgery_id);

        $surgery = fractal($surgery,new SurgeryTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_surgery'), $surgery, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $surgery = Surgery::findOrFail($id);
       
         $surgery = fractal()
                 ->item($surgery)
                 ->transformWith(new SurgeryTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$surgery,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurgery $request, string $id)
    {
       $data = [
        'en' => ['name' => $request->name_en],
        'ar' => ['name' =>  $request->name_ar],
    ];

    $surgery = Surgery::findOrFail($id);

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
        $user = auth()->user();
        
        $surgery = $user->surgeries()
                       ->where('user_id',$user->id)
                       ->firstOrfail();

       // $surgery->delete(); 
         $user->surgeries()->detach($surgery->id);

         return  $this->responseApi(__('messages.delete_surgery'),204);

    }
}
