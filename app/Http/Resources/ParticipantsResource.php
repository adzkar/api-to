<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id_participant,
          'first_name' => $this->first_name,
          'last_name' => $this->last_name,
          'full_name' => $this->first_name.' '.$this->last_name,
          'username' => $this->username,
          'school' => $this->school,
        ];
    }
}
