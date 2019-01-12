<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestsResource extends JsonResource
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
          'id' => $this->id_test,
          'title' => $this->title,
          'information' => $this->information,
          'start' => $this->start,
          'end' => $this->end,
          'duration' => $this->duration,
          'true_value' => $this->true_value,
          'empty_value' => $this->empty_value,
          'wrong_value' => $this->wrong_value,
          'committee' => new CommitteesResource($this->committee),
        ];
    }
}
