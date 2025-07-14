<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreOrder;
use App\Models\Order;
use App\Traits\Response;
use App\Transformers\front\OrderTransform;
use Illuminate\Support\Facades\DB;
use League\Fractal\Serializer\ArraySerializer;

class OrderController extends Controller
{
    use Response;
    
//orders of user
    public function orders(string $id)
    {
     $user = auth()->user();

    $order = $user->orders()
                  ->with('visit')
                  ->get();

     $orders =  fractal()->collection($order)
                  ->transformWith(new  OrderTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $orders, 200);
        
    }

    //store
    public function store(StoreOrder $request )
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['status'] = 1;

    $visit = DB::table('visit_doctors')
                    ->where('user_id', $data['doctor_id'])
                    ->where('visit_id', $data['visit_id'])
                    ->where('active',true)
                    ->first();

        if(!$visit)    
        {
           return $this->responseApi(__('doctor not subcribe in this visit'));
        }  
        
         if ($data['price'] != $visit->price) 
            {
        return $this->responseApi(__('price must match the doctor visit price'));
           }

       $order = Order::create($data);

       $order = fractal($order, new OrderTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_order'), $order, 201);    
    }

    

    
}
