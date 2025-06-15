<?php

namespace App\Transformers\front;

use App\Models\Experience;
use League\Fractal\TransformerAbstract;

class ExperienceTransform extends TransformerAbstract
{
    

    public function transform(Experience $experience):array
    {
        return [
            'id'=>$experience->id,
            'jobtitle_en'=>$experience->translate('en')->jobtitle,
            'jobtitle_ar'=>$experience->translate('ar')->jobtitle,
            'organization_en'=>$experience->translate('en')->organization,
            'organization_ar'=>$experience->translate('ar')->organization,
            'current'=>($experience->current) ? 'work' : 'notwork',
            
        ];
    }
}
