<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreBlood;
use App\Http\Requests\Api\admin\UpdateBlood;
use App\Models\Blood;
use App\Traits\Response;
use App\Transformers\Admin\BloodTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class BloodController extends Controller
{
use Response;

   public function index(Request $request)
{
    $search = $request->input('search');
    $take = $request->input('take'); 
    $skip = $request->input('skip');  
    $locale = $request->query('lang', app()->getLocale());

    $query = Blood::query();

      if ($search)
    {
        $query->whereTranslationLike('name', '%' . $search . '%', $locale);
    }

    $total = $query->count();

    $blood = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $blood =  fractal()->collection($blood)
                  ->transformWith(new BloodTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $blood, 200, ['count' =>$total]);
}
    /**
     * Store a newly created resource in storage.
     */
     public function store(StoreBlood $request)
    {
         $data = [
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $blood = Blood::create($data);

       $blood = fractal($blood,new BloodTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_blood'), $blood, 201);
    }
//show

      public function show(string $id)
    {     
        $blood = Blood::findOrFail($id);

         $blood = fractal()
                 ->item($blood)
                 ->transformWith(new BloodTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$blood,200);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlood $request, string $id)
    {  
         $data = [
        'en' => ['name' => $request->name_en],
        'ar' => ['name' => $request->name_ar],
    ];

    $blood = Blood::findOrFail($id);

     $blood->update($data);

      $blood = fractal($blood, new  BloodTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_blood'), $blood, 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $user = auth()->user();
        
        $blood = Blood::with('users')
                        ->where('user_id',$user->id)
                        ->findOrFail($id);

        if($blood)
        {
              return  $this->responseApi(__('messages.Nodelete_blood'),403); 
        }

        $blood->delete();
        
        return  $this->responseApi(__('messages.delete_blood'),204);
    }
}
