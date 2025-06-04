<?php

namespace App\Transformers\admin;

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
            'direction'=>$banner->direction ?? '',
            'description_en'=>$banner->translate('en')->description ?? '',
            'description_ar'=>$banner->translate('ar')->description ?? '',
           'image_left' => $banner->image_left? asset('storage/' . $banner->image_left): asset('asset/default.png'),

           'image_right' => $banner->image_right ? asset('storage/' . $banner->image_right): asset('asset/default.png'),

        ];
    }
}
