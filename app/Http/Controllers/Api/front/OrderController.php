<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreOrder;
use App\Models\Order;
use App\Traits\Response;
use App\Transformers\front\OrderTransform;
use Illuminate\Http\Request;
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
        $data['status'] = Order::WAITING;

    $visit = DB::table('visit_doctors')
                    ->where('user_id', $data['doctor_id'])
                    ->where('visit_id', $data['visit_id'])
                    ->where('active',true)
                    ->first();

        if(!$visit)    
        {
           return $this->responseApi(__('doctor not subcribe in this visit'));
        }  
        
       $order = Order::create($data);

       $order = fractal($order, new OrderTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_order'), $order, 201);    
    }

//filter of current,history
public function filter(Request $request,string $id)
{
    $user = auth()->user();

    $take = $request->input('take');
    $skip = $request->input('skip');
    $search = $request->input('search'); 

    $query = Order::with('visit');

    if ($user->user_type === 2) 
        {
        $query->where('doctor_id', $user->id);
    } else {
        $query->where('user_id', $user->id);
    }

    if ($search === 'current') 
        {
        $query->whereIn('status', [Order::WAITING, Order::ACCEPTED]);
        } 
    elseif ($search === 'history') 
        {
        $query->whereIn('status', [Order::CANCELED, Order::REJECTED, Order::COMPLETED]);
        }

    $total = $query->count();

    $orders = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $orders = fractal()->collection($orders)
        ->transformWith(new OrderTransform())
        ->serializeWith(new ArraySerializer())
        ->toArray();

    return $this->responseApi('', $orders, 200, ['count' => $total]);
}
   
//update waiting orders for doctors to accepted
public function acceptorders(string $id)
{
    $user = auth()->user();

  $order =  Order::with('visit')
                ->where('status',Order::WAITING)
                ->where('doctor_id',$user->id)
                ->first();
    if(!$order)  
    {
          return $this->responseApi(__('no waiting order for this doctor'));
    }          
      
    $order->update(['status'=>Order::ACCEPTED]);
            
   $order = fractal()
            ->item($order) 
            ->transformWith(new OrderTransform())
            ->serializeWith(new ArraySerializer())
            ->toArray();

    return $this->responseApi(__('messages.update_order'), $order, 200);            

}

//reject order by doctor
public function rejectedorders(string $id)
{
    $user = auth()->user();

    $order = order::with('visit')
                   ->where('id',$id)
                   ->where('doctor_id',$user->id)
                   ->where('status',Order::ACCEPTED)
                   ->firstOrFail();
     
    $order->update(['status'=>Order::REJECTED]);

   $order = fractal()
            ->item($order) 
            ->transformWith(new OrderTransform())
            ->serializeWith(new ArraySerializer())
            ->toArray();

     return $this->responseApi(__('messages.delete_order'));
    
}

//cancel one order
public function cancelorder(string $id)
{
     $user = auth()->user();

    $order = order::with('visit')
                   ->where('id',$id)
                   ->where('user_id',$user->id)
                   ->where('status',Order::ACCEPTED)
                   ->firstOrFail();
     
    $order->update(['status'=>Order::CANCELED]);

   $order = fractal()
            ->item($order) 
            ->transformWith(new OrderTransform())
            ->serializeWith(new ArraySerializer())
            ->toArray();

      return $this->responseApi(__('messages.delete_order'));   
}




//show waiting orders
// public function waitingorders(string $id)
// {
//     $user = auth()->user();

//   $order =  Order::with('visit')
//                    ->where('status',Order::WAITING)
//                    ->where('doctor_id',$user->id)
//                    ->get();

//         if(!$order)
//        {
//             return $this->responseApi(__('there is no waiting orders'));
//        }     
   
//  $orders =  fractal()->collection($order)
//                   ->transformWith(new OrderTransform())
//                    ->serializeWith(new ArraySerializer())
//                    ->toArray();

//     return $this->responseApi('', $orders, 200);             

// }

//show all accepted orders
// public function acceptedorders(string $id)
// {
//     $user = auth()->user();

//     $orders = Order::with('visit')
//                    ->where('doctor_id',$user->id)
//                    ->where('status',Order::ACCEPTED)
//                    ->get();

//        if(!$orders)
//        {
//             return $this->responseApi(__('there is no accepted orders'));
//        }            

//     $orders =  fractal()->collection($orders)
//                         ->transformWith(new OrderTransform())
//                         ->serializeWith(new ArraySerializer())
//                         ->toArray();

//       return $this->responseApi('', $orders, 200);              
// }

  //show all accepeted orders for patient
// public function acceptedorder(string $id)
// {
//     $user = auth()->user();

//      $orders = Order::with('visit')
//                    ->where('status',Order::ACCEPTED)
//                    ->where('user_id',$user->id)
//                    ->get();

//        if(!$orders)
//        {
//             return $this->responseApi(__('there is no accepted orders'));
//        }            

//     $orders =  fractal()->collection($orders)
//                   ->transformWith(new OrderTransform())
//                    ->serializeWith(new ArraySerializer())
//                    ->toArray();

//       return $this->responseApi('', $orders, 200);              
// } 


// //show all waiting orders
// public function waitingorder(string $id)
// {
//     $user = auth()->user();

//   $order =  Order::with('visit')
//                 ->where('status',Order::WAITING)
//                 ->where('user_id',$user->id)
//                 ->get();

//         if(!$order)
//        {
//             return $this->responseApi(__('there is no waiting orders'));
//        }     
   
//  $orders =  fractal()->collection($order)
//                      ->transformWith(new OrderTransform())
//                      ->serializeWith(new ArraySerializer())
//                      ->toArray();

//     return $this->responseApi('', $orders, 200);             
// }










    
}
