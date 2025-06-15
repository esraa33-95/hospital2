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
   
    public function index(Request $request)
{
    $search = $request->input('search');
    $take = $request->input('take'); 
    $skip = $request->input('skip');  
    $locale = $request->query('lang', app()->getLocale());

    $query = Surgery::query();

      if ($search)
    {
        $query->whereTranslationLike('name', '%' . $search . '%', $locale);
    }

    $total = $query->count();

    $surgery = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $surgery =  fractal()->collection($surgery)
                  ->transformWith(new SurgeryTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

     return $this->responseApi('', $surgery, 200, ['count' =>$total]);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurgery $request)
    {
      $data = [ 
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $surgery = Surgery::create($data);

       $surgery = fractal($surgery,new SurgeryTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_surgery'), $surgery, 201);

    }
//show
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
        'ar' => ['name' => $request->name_ar],
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
        $surgery = Surgery::with('users')->findOrFail($id);

         if ($surgery) 
         {
        
            return  $this->responseApi(__('messages.Nodelete_surgery'),403); 
        }

        $surgery->delete();
        
        return  $this->responseApi(__('messages.delete_surgery'),204);
    }
}

