<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visit;
use App\Traits\Response;
use App\Http\Requests\Api\front\StoreVisit;
use App\Http\Requests\Api\front\UpdateVisit;
use App\Models\Order;
use App\Models\User;
use App\Transformers\front\OrderTransform;
use App\Transformers\front\VisitTransform;
use League\Fractal\Serializer\ArraySerializer;

class VisitController extends Controller
{
    use Response;
    
     public function index(Request $request,string $id)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

        $user = auth()->user();

      $query = $user->visits()
                 ->wherePivot('active', true);

      if ($search) 
      {
       $query->whereTranslationLike('visit_type', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

    $visit = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $visit =  fractal()->collection($visit)
                  ->transformWith(new VisitTransform())
                  ->serializeWith(new ArraySerializer())
                  ->toArray();

    return $this->responseApi('',$visit,200,['count' => $total]);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVisit $request)
    {
        $data = $request->validated();

        $user = auth()->user();

      $user->visits()->attach([
          $data['visit_id'] => [
            'price' => $data['price'],
            'active' => false
          ]
    ]);

       $visit = Visit::findOrFail($request->visit_id);

      $visit = fractal($visit,new VisitTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_visit'), $visit, 201);
    }

    //active and non active
    public function active(Request $request,string $id )
    {
        $user = auth()->user();

        $request->validate([
          'active'=>'required|boolean',
       ]);

       $visit = $user->visits()
                     ->where('visit_id', $id)
                     ->firstOrfail();
     
      $user->visits()->updateExistingPivot($id, [
                           'active' => $request->active,
                          ]);

      return  $this->responseApi('update active');

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
        
    $data = [
        'en' => ['visit_type' => $request->visit_type_en],
        'ar' => ['visit_type' =>  $request->visit_type_ar],
        'min_price'=>$request->min_price,
        'max_price'=>$request->max_price,
    ];

    $visit = visit::findOrFail($id);

     $visit->update($data);

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
                       ->where('active',true)
                       ->exists();

      
      if ($visit) 
      {
        return $this->responseApi(__('you are subcribe with this visit'));
      }

         $user->visits()->detach($id);

         return  $this->responseApi(__('messages.delete_visit'),204);
    }



}
