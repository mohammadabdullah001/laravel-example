<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Designation\DesignationResource;

class UserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when($this->id, $this->id),
            'name' =>  $this->when($this->name, $this->name),
            'email' => $this->when($this->email, $this->email),
            'designation_id' => $this->when(
                $this->designation_id,
                $this->designation_id
            ),
            'designation' => $this->when(
                $this->designation_id,
                new DesignationResource($this->whenLoaded('designation'))
            ),
        ];
    }
}
