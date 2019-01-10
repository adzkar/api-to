<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $status = [
          'p' => 'participants', 'c' => 'committees', 't' => 'tests'
        ];

        return [
          'id' => $this->id_ver,
          'code' => $this->code,
          'status' => $status[$this->status],
          'start' => $this->start,
          'end' => $this->end,
        ];
    }
}
