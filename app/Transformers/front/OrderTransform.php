<?php

namespace App\Transformers\front;

use League\Fractal\TransformerAbstract;
use App\Models\Order;

class OrderTransform extends TransformerAbstract
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
    public function transform(Order $order)
    {
        return [
            'is_current'=>$order->is_current,
             'address_id'=>$order->address_id,

        ];
    }
}
