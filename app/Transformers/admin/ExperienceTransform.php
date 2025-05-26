<?php

namespace App\Transformers\admin;

use App\Models\Experience;
use League\Fractal\TransformerAbstract;

class ExperienceTransform extends TransformerAbstract
{
    

    public function transform(Experience $experience):array
    {
        return [
            'id'=>$experience->id,
            'jobtitle'=>$experience->jobtitle,
            'organization'=>$experience->organization,
            'current'=>($experience->current) ? 'work' : 'notwork',
            
        ];
    }
}
