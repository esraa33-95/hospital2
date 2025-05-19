<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    
     protected $locale;

    public function __construct($resource, $locale = null)
    {
        parent::__construct($resource);
        $this->locale = $locale ?? app()->getLocale();
    }

    
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->translate($this->locale)->name,
        ];
    }

}
