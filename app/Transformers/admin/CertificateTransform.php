<?php

namespace App\Transformers\Admin;

use App\Models\Certificate;
use League\Fractal\TransformerAbstract;

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
    public function transform(Certificate $certificate):array
    {
        return [
              'id' => $certificate->id,
              'name_en' => $certificate->translate('en')->name,
              'name_ar' => $certificate->translate('ar')->name,
        ];
    }
}
