<?php

namespace App\Transformers\admin;

use App\Models\Order;
use App\Models\User;
use League\Fractal\TransformerAbstract;

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
     public function transform(Order $order):array
    {
         $visit = $order->visit;
        
    return [
       'patient_name' => $order->user->name ,
        'doctor_name' => $order->doctor->name,
        'visit_id' => $order->visit_id,
        'visit_type_ar' => $visit->translate('ar')->visit_type,
        'visit_type_en' => $visit->translate('en')->visit_type,
        'date' => $order->date,
        'time' => $order->time,
        'status' => $order->status,
        'price'=>$order->price,

        
        
                
        
    ];
    }
}
