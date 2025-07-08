<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreOrder;
use App\Models\Order;
use App\Models\User;
use App\Traits\Response;
use App\Transformers\front\OrderTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class OrderController extends Controller
{
    use Response;
    
//orders of user
    public function orders(string $id)
    {
     $user = auth()->user();

    $order = $user->order()
              ->with('visit')
              ->where('user_id',$user->id)
              ->get();

     $orders =  fractal()->collection($order)
                  ->transformWith(new OrderTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $orders, 200);
        
    }

    //store
    public function store(StoreOrder $request )
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

       $order = Order::create($data);

       $order = fractal($order, new OrderTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_order'), $order, 201);
        
    }

   

    

    
}
