<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Traits\Response;
use App\Transformers\admin\OrderTransform;
use App\Transformers\admin\TransactionTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class OrderController extends Controller
{
    use Response;

    //filter orders for patients and doctors
 public function filterorders(Request $request)
    {
    $take = $request->input('take');
    $skip = $request->input('skip');
    $search = $request->input('search'); 
    $patient = $request->input('patient_id');
    $doctor = $request->input('doctor_id');

    $query = Order::with('visit');
 
    if($request->patient_id)
    {
      $query->where('user_id',$patient);

    }elseif ($request->doctor_id)
    {
       $query->where('doctor_id',$doctor); 
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


//lists of transactions diposit and withdraw
public function transaction()
{
  $transaction =  Transaction::with('wallet')
                  ->whereIn('status',[Transaction::DEPOSIT,Transaction::WITHDRAW])
                  ->get();


     $transaction = fractal()->collection($transaction)
                             ->transformWith(new TransactionTransform())
                             ->serializeWith(new ArraySerializer())
                             ->toArray();

    return $this->responseApi('deposit and withdraw transactions',$transaction);             
}




}
