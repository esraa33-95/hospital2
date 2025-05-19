
<?php

use League\Fractal\TransformerAbstract;

class DepartmentTransform extends TransformerAbstract
{
    protected $locale;

    public function __construct($locale = null)
    {
        $this->locale = $locale ?? app()->getLocale();
    }

    public function transform($department): array
    {
        return [
            'id' => $department->id,
            'name' => $department->translate($this->locale)->name,
        ];
    }
}
