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
        $locale = app()->getLocale();

    $media = $banner->getMedia('files')->firstWhere('custom_properties.locale', $locale);
    $image = $media ? $media->getFullUrl() : null;

    return [
        'id' => $banner->id,
        'position' => $banner->position,
        'description_en'=>$banner->translate('en')->description ?? '',
        'description_ar'=>$banner->translate('ar')->description ?? '',
        'image_ar' => $image,
        'image_en' => $image,
    ];
    }
}
