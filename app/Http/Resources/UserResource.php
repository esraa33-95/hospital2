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
            'password'=>$this->password,
            'image' => $this->image,
            'role' => $this->role,
        ];

        if ($this->role === 'doctor') {
            $data['department'] = [
                'id' => ($this->department)->id,
                'name' => ($this->department)->name,
            ];
        }

        return $data;
    }
       

}

