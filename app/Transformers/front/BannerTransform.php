<?php

namespace App\Transformers\front;

use App\Models\Banner;
use League\Fractal\TransformerAbstract;

class BannerTransform extends TransformerAbstract
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
    public function transform(Banner $banner):array
    {
        return [
            'id'=>$banner->id,
            'position'=>$banner->position ?? '',
            'description_en'=>$banner->translate('en')->description ?? null,
            'description_ar'=>$banner->translate('ar')->description ?? null,
           'image_en' =>$banner->image_en ? asset('storage/' . $banner->image_en): asset('asset/default.png'),
           'image_ar' => $banner->image_ar ? asset('storage/' . $banner->image_ar): asset('asset/default.png'),

        ];
    }
}
