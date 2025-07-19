<?php

namespace App\Transformers\front;

use App\Models\User;
use App\Models\Wallet;
use League\Fractal\TransformerAbstract;

class WalletTransform extends TransformerAbstract
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
    public function transform(Wallet $wallet):array
    {
        $user = User::find($wallet->user_id);

        return [
            'user_id'=>$user->name,
            'amount'=>$wallet->amount,
        ];
    }
}
