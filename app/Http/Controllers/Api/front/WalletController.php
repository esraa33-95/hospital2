<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\StoreDeposit;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Traits\Response;
use App\Transformers\front\WalletTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class WalletController extends Controller
{
    use Response;

//add deposit for wallet 
public function deposit(StoreDeposit $request, string $id)
{
$user = auth()->user();

$data = $request->validated();

$wallet = Wallet::create([
    'user_id'=>$user->id,
]);

$wallet->total_price += $data['amount'];

$wallet->save();

 Transaction::create([
   'wallet_id'=>$wallet->id,
   'amount'=>$data['amount'],
   'status'=> 1,
]);

  $wallet = fractal($wallet, new WalletTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

 return $this->responseApi(__('messages.store_deposit'),$wallet,201); 

}








}
