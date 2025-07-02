<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreAddress;
use App\Http\Requests\Api\front\UpdateAddress;
use App\Models\Address;
use App\Models\Area;
use App\Traits\Response;
use App\Transformers\front\AddressTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class AdressController extends Controller
{
    use Response;

//add address

    public function store(StoreAddress $request, string $id)
    {
     $user = auth()->user();

    $area = $request->area_id;  

     $area = Area::findOrFail($area);

     $main = $user->address()->exists();

     $data = [
        'user_id'=>$user->id,
        'country_id'=>$area->city->country->id,
        'city_id'=>$area->city->id,
        'area_id'=>$area->id,
        'lng' =>$request->lng,
        'lat' =>$request->lat, 
        'is_main'=>!$main,

        'ar' => ['street_name' =>$request->street_name_ar,
                'building_number'=>$request->building_number_ar, 
                 'floor_number'=>$request->floor_number_ar,
                 'landmark'=>$request->landmark_ar
                 ],
        'en' => ['street_name' => $request->street_name_en,
                'building_number'=>$request->building_number_en, 
                 'floor_number'=>$request->floor_number_en,
                 'landmark'=>$request->landmark_en
                ],    
      ];
   
      Address::create($data);

    return $this->responseApi(__('messages.store_address'),'');
    }

//show
   public function show(string $id)
    {     
         $address = Address::findOrFail($id);

         $address = fractal()
                 ->item($address)
                 ->transformWith(new AddressTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$address,200);
    }


//update
   public function update(UpdateAddress $request, string $id)
    {
     $user = auth()->user();
                   
    $area = $request->area_id;  

     $area = Area::findOrFail($area);

     $address = $user->address()->findOrFail($id);

      $is_main = $request->input('is_main');

    if ($is_main) 
    {
        $user->address()->where('id', '!=', $address->id)
                        ->update(['is_main' => false]);

        $address->is_main = true;
    }

     $data = [
        'user_id'=>$user->id,
        'country_id'=>$area->city->country->id,
        'city_id'=>$area->city->id,
        'area_id'=>$area->id,
         'lng' =>$request->lng,
        'lat' =>$request->lat, 
       

        'ar' => ['street_name' =>$request->street_name_ar,
                'building_number'=>$request->building_number_ar, 
                 'floor_number'=>$request->floor_number_ar,
                 'landmark'=>$request->landmark_ar
                 ],
        'en' => ['street_name' => $request->street_name_en,
                'building_number'=>$request->building_number_en, 
                 'floor_number'=>$request->floor_number_en,
                 'landmark'=>$request->landmark_en],
     
      ];
   
     $address->update($data);

     $address = fractal($address, new AddressTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_address'), $address, 200);
    }


//delete
    public function delete(string $id)
    {
         $user = auth()->user();

           $address = $user->address()->findOrFail($id);

           if($user->address()->count() == 1)
            {
             return $this->responseApi(__('messages.cant_delete'));
            }
            
            if ($address->is_main) 
            {
               $newaddress = $user->address()
                           ->where('id', '!=', $address->id)
                           ->orderBy('created_at', 'asc')
                           ->firstOrFail();

            $newaddress->update(['is_main' => true]);
           
    }
        $address->delete();
        
        return  $this->responseApi(__('messages.delete_address'),204); 

    }

}
