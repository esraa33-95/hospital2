<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreVisit;
use App\Http\Requests\Api\admin\UpdateVisit;
use App\Models\Visit;
use App\Traits\Response;
use App\Transformers\admin\VisitTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class VisitController extends Controller
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
    $locale = $request->query('lang', app()->getLocale());

    $query = Visit::query();

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

     return $this->responseApi('', $visit, 200, ['count' =>$total]);
}

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVisit $request)
    {
        $data = [ 
        'ar' => ['visit_type' => $request->visit_type_ar],
        'en' => ['visit_type' => $request->visit_type_en],

        'min_price'=>$request->min_price,
        'max_price'=>$request->max_price,
    ];

       $visit = Visit::create($data);

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
       $data = [ 
        'ar' => ['visit_type' => $request->visit_type_ar],
        'en' => ['visit_type' => $request->visit_type_en],

        'min_price'=>$request->min_price,
        'max_price'=>$request->max_price,
    ];

       $visit = Visit::findOrFail($id);

       $visit->update($data);

       $visit = fractal($visit,new VisitTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.update_visit'), $visit, 200);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $visit = Visit::with('doctors')->findOrFail($id);

         if ($visit) 
         {
        
            return  $this->responseApi(__('messages.cant_delete'),403); 
        }

        $visit->delete();
        
        return  $this->responseApi(__('messages.delete_visit'),204);
    }
}
