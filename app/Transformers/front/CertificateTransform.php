<?php

namespace App\Transformers\front;

use League\Fractal\TransformerAbstract;
use App\Models\Certificate;

class CertificateTransform extends TransformerAbstract
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
    public function transform(Certificate $certificate)
    {
        return [
              'id' => $certificate->id,
              'name_en' => $certificate->translate('en')->name ?: null,
              'name_ar' => $certificate->translate('ar')->name ?: null,
              'image' => $certificate->getFirstMediaUrl('files') ?: null,

        ];
    }
}
