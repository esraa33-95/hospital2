<?php

namespace App\Transformers\admin;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransform extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction):array
    {

        $name = ($transaction->wallet->user)->name;

        return [
           
            'amount'       => $transaction->amount,
            'status'       => $transaction->status == Transaction::DEPOSIT ? 'deposit' : 'withdraw',
            'patient_name' => $transaction->status == Transaction::WITHDRAW ? $name : '',
            'doctor_name'  => $transaction->status == Transaction::DEPOSIT ? $name : '',
            'created_at'   => $transaction->created_at,
        ];
    }
}
