<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreAddress;
use App\Http\Requests\Api\front\UpdateAddress;
use App\Models\Address;
use App\Traits\Response;
use App\Transformers\front\AddressTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class AdressController extends Controller
{
    use Response;


    public function store(StoreAddress $request, string $id)
    {
     $user = auth()->user();
                   
     $data = [
        'user_id'=>$user->id,
        'country_id'=>$request->country_id,
        'city_id'=>$request->city_id,
        'area_id'=>$request->area_id,

        'ar' => ['street_name' =>$request->street_name_ar,
                'building_number'=>$request->building_number_ar, 
                 'floor_number'=>$request->floor_number_ar,
                 'landmark'=>$request->landmark_ar
                 ],
        'en' => ['street_name' => $request->street_name_en,
                'building_number'=>$request->building_number_en, 
                 'floor_number'=>$request->floor_number_en,
                 'landmark'=>$request->landmark_en],

        'lng' =>$request->lng,
        'lat' =>$request->lat,     
      ];
   
     $address = Address::create($data);

     $address = fractal($address, new AddressTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_address'), $address, 201);
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



   public function update(UpdateAddress $request, string $id)
    {
     $user = auth()->user();
                   
     $data = [
        'user_id'=>$user->id,
        'country_id'=>$request->country_id,
        'city_id'=>$request->city_id,
        'area_id'=>$request->area_id,

        'ar' => ['street_name' =>$request->street_name_ar,
                'building_number'=>$request->building_number_ar, 
                 'floor_number'=>$request->floor_number_ar,
                 'landmark'=>$request->landmark_ar
                 ],
        'en' => ['street_name' => $request->street_name_en,
                'building_number'=>$request->building_number_en, 
                 'floor_number'=>$request->floor_number_en,
                 'landmark'=>$request->landmark_en],

        'lng' =>$request->lng,
        'lat' =>$request->lat,     
      ];
   
     $address = Address::findOrFail($id);

     $address->update($data);

     $address = fractal($address, new AddressTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_address'), $address, 200);
    }


    public function delete(string $id)
    {
         $user = auth()->user();

         $address = Address::where('id', $id)
                             ->where('user_id',$user->id)
                             ->firstOrFail();

        $orders = $address->order()
                             ->where('is_current',1)
                             ->exists();                           
     
        if($orders)
    {
    return  $this->responseApi(__('messages.cant_delete')); 
    }

        $address->delete();
        
        return  $this->responseApi(__('messages.delete_address'),204); 

    }

}
