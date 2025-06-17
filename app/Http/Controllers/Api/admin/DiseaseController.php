<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreDisease;
use App\Http\Requests\Api\admin\UpdateDisease;
use App\Models\Disease;
use App\Traits\Response;
use App\Transformers\Admin\DiseaseTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class DiseaseController extends Controller
{
    use Response;
    
    public function index(Request $request)
{
    $search = $request->input('search');
    $take = $request->input('take'); 
    $skip = $request->input('skip');  
    $locale = $request->query('lang', app()->getLocale());

    $query = Disease::query();

      if ($search)
    {
        $query->whereTranslationLike('name', '%' . $search . '%', $locale);
    }

    $total = $query->count();

    $disease = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $disease =  fractal()->collection($disease)
                  ->transformWith(new DiseaseTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $disease, 200, ['count' =>$total]);
}
    /**
     * Store a newly created resource in storage.
     */
     public function store(StoreDisease $request)
    {
         $data = [
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $disease = Disease::create($data);

       $disease = fractal($disease,new DiseaseTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_disease'), $disease, 201);
    }

     public function show(string $id)
    {     
        $disease = Disease::findOrFail($id);

         $disease = fractal()
                 ->item($disease)
                 ->transformWith(new DiseaseTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$disease,200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDisease $request, string $id)
    { 
         $data = [
        'en' => ['name' => $request->name_en],
        'ar' => ['name' =>  $request->name_ar],
    ];

    $disease = Disease::findOrFail($id);

     $disease->update($data);

      $disease = fractal($disease, new DiseaseTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_disease'), $disease, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $user = auth()->user();

        $disease = Disease::with('users')
                   ->where('user_id',$user->id)
                    ->findOrFail($id);

        if($disease)
        {
              return  $this->responseApi(__('messages.Nodelete_disease'),403); 
        }

        $disease->delete();
        
        return  $this->responseApi(__('messages.delete_disease'),204);
    }
}
