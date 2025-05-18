<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         $name = auth()->user()?->name;
       
        $data = [  
            'report_name' => $this->report_name,
            'symptoms'=>$this->symptoms,
            'traitment'=>$this->traitment,
            'doctor_name'=>$name,
        ];

        return $data;
    }
}
