<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\DetailResultResource as DetRes;

class NewResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $res = $this->results;
        return [
          'id_par' => $this->id_participant,
          'full_name' => $this->first_name.' '.$this->last_name,
          'school' => $this->school,
          'results' => DetRes::collection($res),
        ];
    }
}
