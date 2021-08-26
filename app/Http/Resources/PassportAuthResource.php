<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PassportAuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        return [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];
    }

}
