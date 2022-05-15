<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserReaction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'from_user_id' => $this->from_user_id,
            'to_user_id' => $this->to_user_id,
            'type' => $this->type,
            'reaction' => $this->reaction,
        ];
    }
}
