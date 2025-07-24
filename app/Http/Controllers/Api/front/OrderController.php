<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreOrder;
use App\Http\Requests\Api\front\StoreStatus;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Wallet;
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

       $patient = Wallet::Create(
        ['user_id' => $order->user_id],
        );   
        $patient->pending_price +=  $order['price'];
         $patient->save();

      $doctor = Wallet::Create(
        ['user_id' => $order->doctor_id],
        );

        $doctor->pending_price +=  $order['price'];  
         $doctor->save();
        
       $order = fractal($order, new OrderTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_order'), $order, 201);    
    }

//filter of current,history
public function filter(Request $request, string $id)
{
    $take = $request->input('take');
    $skip = $request->input('skip');
    $search = $request->input('search'); 

    $query = Order::with('visit');
 
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
   
//make waiting orders for doctors to accepted
public function acceptorders(string $id)
{
    $user = auth()->user();

  $order =  Order::with('visit')
                ->where('status',Order::WAITING)
                ->where('doctor_id', $user->id)
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
public function rejectorder(string $id)
{
    $user = auth()->user();

    $order = order::with('visit')
                   ->where('id',$id)
                   ->where('doctor_id',$user->id)
                   ->where('status',Order::WAITING)
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

//change status in 2,3
public function changestatus(StoreStatus $request, string $id)
{
    
    $data = $request->validated();

    $order = Order::with('visit')->findOrFail($id);

    $patient = $order->user->wallet;
    $doctor  = $order->doctor->wallet;


    if ($data['status'] == Order::ACCEPTED) {

        $patient->pending_price += $order->price;
        $doctor->pending_price  += $order->price;
 

        Transaction::create([
            'wallet_id' => $patient->id,
            'status'    => Transaction::WITHDRAW,
            'amount'    => $order->price,
        ]);

        Transaction::create([
            'wallet_id' => $doctor->id,
            'status'    => Transaction::DEPOSIT,
            'amount'    => $order->price,
        ]);

    } elseif (in_array($data['status'], [Order::CANCELED, Order::REJECTED])) {

        $patient->pending_price -= $order->price;
        $doctor->pending_price  -= $order->price;
        $patient->total_price   += $order->price;

        Transaction::create([
            'wallet_id' => $patient->id,
            'status'    => Transaction::DEPOSIT,
            'amount'    => $order->price,
        ]);

        Transaction::create([
            'wallet_id' => $doctor->id,
            'status'    => Transaction::WITHDRAW,
            'amount'    => $order->price,
        ]);

    } elseif ($data['status'] == Order::COMPLETED) {

        $patient->pending_price -= $order->price;
        $doctor->pending_price  -= $order->price;
        $doctor->total_price    += $order->price;

    
        Transaction::create([
            'wallet_id' => $patient->id,
            'status'    => Transaction::WITHDRAW,
            'amount'    => $order->price,
        ]);

        Transaction::create([
            'wallet_id' => $doctor->id,
            'status'    => Transaction::DEPOSIT,
            'amount'    => $order->price,
        ]);
    }

    $patient->save();
    $doctor->save();

    $order->status = $data['status'];
    $order->save();

    return $this->responseApi(__('status changed successfully'));
}




}






