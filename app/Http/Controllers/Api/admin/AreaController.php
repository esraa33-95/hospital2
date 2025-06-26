<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreArea;
use App\Http\Requests\Api\Admin\UpdateArea;
use App\Models\Area;
use App\Traits\Response;
use App\Transformers\admin\AreaTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class AreaController extends Controller
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

    $query = Area::query();

      if ($search)
    {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
    }

    $total = $query->count();

    $areas = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $areas =  fractal()->collection($areas)
                  ->transformWith(new AreaTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $areas, 200, ['count' =>$total]);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArea $request)
    {
         $city = $request->input('city_id');
         
        $data = [
        'city_id'=>$city,
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $areas = Area::create($data);

       $areas = fractal($areas,new AreaTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_areas'), $areas, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $area = Area::findOrFail($id);

         $area = fractal()
                 ->item($area)
                 ->transformWith(new AreaTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$area,200);
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArea $request, string $id)
    { 
        $city = $request->input('city_id');

        $data = [
        'city_id'=>$city,
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $area = Area::FindOrfail($id);

       $area->update($data);

       $area = fractal($area,new AreaTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.update_areas'), $area, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
         $area = Area::with('city')->findOrFail($id);
         
        if ($area)
         {
        return $this->responseApi(__('messages.no_delete_area'), 403);
    }

        $area->delete();
        
        return  $this->responseApi(__('messages.delete_area'),204); 
    }
}
