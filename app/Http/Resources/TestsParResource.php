<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestsParResource extends JsonResource
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
          'title' => $this->title,
          'total_questions' => $this->questions()->count(),
          'time' => $this->start.' - '.$this->end,
          'true_value' => $this->true_value,
          'empty_value' => $this->empty_value,
          'wrong_value' => $this->wrong_value,
        ];
    }
}
