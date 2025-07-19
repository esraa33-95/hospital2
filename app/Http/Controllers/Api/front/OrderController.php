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

   
   //show all accepeted orders for patient
public function acceptedorder(string $id)
{
    $user = auth()->user();

   $orders = Order::with('visit')
                   ->where('status',2)
                   ->where('user_id',$user->id)
                   ->get();

       if(!$orders)
       {
            return $this->responseApi(__('there is no accepted orders'));
       }            

    $orders =  fractal()->collection($orders)
                  ->transformWith(new OrderTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

      return $this->responseApi('', $orders, 200);              
} 


//show all waiting orders
public function waitingorder(string $id)
{
    $user = auth()->user();

  $order =  Order::with('visit')
                ->where('status',1)
                ->where('user_id',$user->id)
                ->get();

        if(!$order)
       {
            return $this->responseApi(__('there is no waiting orders'));
       }     
   
 $orders =  fractal()->collection($order)
                  ->transformWith(new OrderTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

return $this->responseApi('', $orders, 200);             

}

//cancel all accepted orders
public function cancelorder(string $id)
    {
     $user = auth()->user();

    $orders = order::with('visit')
                  ->where('user_id',$user->id)
                  ->where('status',2)
                  ->get();

          if(!$orders)
          {
            return $this->responseApi(__('no accepted orders'));
          }

       foreach($orders as $order)  
        {
            $order->update(['status'=>3]);
        }  
   
     $orders =  fractal()->collection($orders)
                  ->transformWith(new  OrderTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi(__('messages.cancel_order'));
        
    }

    //cancel one order
// public function cancelorder(string $id)
// {
//      $user = auth()->user();

//     $order = order::with('visit')
//                    ->where('id',$id)
//                    ->where('user_id',$user->id)
//                    ->where('status',2)
//                    ->firstOrFail();
     
//     $order->update(['status'=>3]);

//    $order = fractal()
//             ->item($order) 
//             ->transformWith(new OrderTransform())
//             ->serializeWith(new ArraySerializer())
//             ->toArray();

//       return $this->responseApi(__('messages.delete_order'));   
// }




//update all waiting orders for doctors to accepted
public function updateorders(string $id)
{
    $user = auth()->user();

  $orders =  Order::with('visit')
                ->where('status',1)
                ->where('doctor_id',$user->id)
                ->get();
    if(!$orders)  
    {
          return $this->responseApi(__('no waiting order for this doctor'));
    }          
      
  foreach($orders as $order ) 
  {
    $order->update(['status'=>2]);
  }             
   
 $orders =  fractal()->collection($orders)
                  ->transformWith(new OrderTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi(__('messages.update_order'), $orders, 200);            

}



//show waiting orders
public function waitingorders(string $id)
{
    $user = auth()->user();

  $order =  Order::with('visit')
                   ->where('status',1)
                   ->where('doctor_id',$user->id)
                   ->get();

        if(!$order)
       {
            return $this->responseApi(__('there is no waiting orders'));
       }     
   
 $orders =  fractal()->collection($order)
                  ->transformWith(new OrderTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $orders, 200);             

}

//show all accepted orders
public function acceptedorders(string $id)
{
    $user = auth()->user();

   $orders = Order::with('visit')
                   ->where('doctor_id',$user->id)
                   ->where('status',2)
                   ->get();

       if(!$orders)
       {
            return $this->responseApi(__('there is no accepted orders'));
       }            

    $orders =  fractal()->collection($orders)
                        ->transformWith(new OrderTransform())
                        ->serializeWith(new ArraySerializer())
                        ->toArray();

      return $this->responseApi('', $orders, 200);              
}


//cancel all accepted order
public function cancelorders(string $id)
    {
     $user = auth()->user();

    $orders = order::with('visit')
                  ->where('doctor_id',$user->id)
                  ->where('status',2)
                  ->get();

          if(!$orders)
          {
            return $this->responseApi(__('no accepted orders'));
          }

       foreach($orders as $order)  
        {
            $order->update(['status'=>3]);
        }  
   
     $orders =  fractal()->collection($orders)
                  ->transformWith(new  OrderTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi(__('messages.cancel_order'));
        
}

//cancel order
// public function cancelorder(string $id)
// {
//     $user = auth()->user();

//     $order = order::with('visit')
//                    ->where('id',$id)
//                    ->where('doctor_id',$user->id)
//                    ->where('status',2)
//                    ->firstOrFail();
     
//     $order->update(['status'=>3]);

//    $order = fractal()
//             ->item($order) 
//             ->transformWith(new OrderTransform())
//             ->serializeWith(new ArraySerializer())
//             ->toArray();

//      return $this->responseApi(__('messages.delete_order'));
    
// }

    
}
