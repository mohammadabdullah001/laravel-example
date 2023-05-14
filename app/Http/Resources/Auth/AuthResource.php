<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'token_type' => $this->data['token_type'],
            'access_token' => $this->data['access_token'],
            'refresh_token' => $this->data['refresh_token'],
            'expires_in' => $this->data['expires_in'],
        ];
    }
}
