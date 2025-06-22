<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\front\StoreOrder;
use App\Models\Order;
use App\Traits\Response;
use App\Transformers\front\OrderTransform;
use League\Fractal\Serializer\ArraySerializer;

class RequestController extends Controller
{
    use Response;

     public function store(StoreOrder $request)
    {         
     $data = [
        'address_id'=>$request->address_id,
        'is_current'=>$request->is_current,
      ];
   
     $order = Order::create($data);

     $order = fractal($order, new OrderTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_request'), $order, 201);
    }
}
