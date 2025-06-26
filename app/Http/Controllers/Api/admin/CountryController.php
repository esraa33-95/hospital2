<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreCountry;
use App\Http\Requests\Api\Admin\UpdateCountry;
use App\Models\Country;
use App\Traits\Response;
use App\Transformers\admin\CountryTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class CountryController extends Controller
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

    $query = Country::query();

      if ($search)
    {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
    }

    $total = $query->count();

    $countries = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $countries =  fractal()->collection($countries)
                  ->transformWith(new CountryTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $countries, 200, ['count' =>$total]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountry $request)
    {
       $data = [
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $countries = Country::create($data);

       $countries = fractal($countries,new CountryTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_countries'), $countries, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $country = Country::findOrFail($id);

         $country = fractal()
                 ->item($country)
                 ->transformWith(new CountryTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$country,200);
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountry $request, string $id)
    {
          $data = [
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $country = Country::FindOrfail($id);

       $country->update($data);

       $country = fractal($country,new CountryTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.update_countries'), $country, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $country = Country::with('city')->findOrFail($id);

        if ($country)
        {
            return  $this->responseApi(__('messages.no_delete_country'),403); 
        }

        $country->delete();
        
        return  $this->responseApi(__('messages.delete_country'),204); 
    }
}
