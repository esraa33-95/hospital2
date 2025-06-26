<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreCity;
use App\Http\Requests\Api\Admin\UpdateCity;
use App\Models\City;
use App\Traits\Response;
use App\Transformers\admin\CityTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class CityController extends Controller
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

    $query = City::query();

      if ($search)
    {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
    }

    $total = $query->count();

    $cities = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $cities =  fractal()->collection($cities)
                  ->transformWith(new CityTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $cities, 200, ['count' =>$total]);
}

 
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCity $request)
    {
       $country = $request->input('country_id');

        $data = [
        'country_id'=>$country,
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $cities = City::create($data);

       $cities = fractal($cities,new CityTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_cities'), $cities, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $city = City::findOrFail($id);

         $city = fractal()
                 ->item($city)
                 ->transformWith(new CityTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$city,200);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCity $request, string $id)
    { 
        $country = $request->input('country_id');

        $data = [
        'country_id'=>$country,
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $city = City::FindOrfail($id);

       $city->update($data);

       $city = fractal($city,new CityTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.update_cities'), $city, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $city = City::with('area')->findOrFail($id);

        if($city)
        {
            return  $this->responseApi(__('messages.no_delete_city'),403); 
        }

        $city->delete();
        
        return  $this->responseApi(__('messages.delete_city'),204);
    }
}
