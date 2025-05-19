<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'image' => $this->getFirstMediaUrl('image') ? :url('public/assets/images/' . $this->image),
            'department_name' =>($this->user_type == 2 && $this->department) ? $this->department->name : null,
            'user_type'=>$this->user_type,
        ];

        return $data;
    }
       

}

