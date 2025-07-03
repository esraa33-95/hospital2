<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visit;
use App\Traits\Response;
use App\Http\Requests\Api\front\StoreVisit;
use App\Http\Requests\Api\front\UpdateVisit;
use App\Transformers\front\VisitTransform;
use League\Fractal\Serializer\ArraySerializer;

class VisitController extends Controller
{
    use Response;
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVisit $request,string $id)
    {
        $data = $request->validated();

        $user = auth()->user();

        $visit = Visit::findOrFail($request->visit_id);

        if($data['price'] < $visit->min_price || $data['price'] > $visit->max_price )
        {
             return $this->responseApi(__('price should be between min,max'));     
        }

       $user->visits()->attach($visit->id, [
                              'price' => $data['price'],
                               'active' =>  $data['active'] ,
                            ]);

         $visit = fractal($visit,new VisitTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_visit'), $visit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $visit = Visit::findOrFail($id);
       
         $visit = fractal()
                 ->item($visit)
                 ->transformWith(new VisitTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$visit,200);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisit $request, string $id)
    {
        $data = $request->validated();

        $user = auth()->user();
 
        $visit = $user->visits()
                 ->where('visit_id', $id)
                 ->firstOrFail();


        if($data['price'] < $visit->min_price || $data['price'] > $visit->max_price )
        {
             return $this->responseApi(__('price should be between min,max'));     
        }

       $user->visits()->updateExistingPivot($id, [
                                        'price' => $data['price'] ??  $visit->pivot->price,
                                        'active' => $data['active'] ??  $visit->pivot->active,
                                        ]);


        $visit = fractal($visit, new VisitTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_visit'), $visit, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
         $user = auth()->user();
        
        $visit = $user->visits()
                       ->where('visit_id',$id)
                       ->where('active',1)
                       ->exists();
      
      if ($visit) 
      {
        return $this->responseApi(__('you are subcribe with this visit'));
      }

         $user->visits()->detach($id);

         return  $this->responseApi(__('messages.delete_visit'),204);
    }
}
