<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $test = $this->test;
        return [
          'id_result' => $this->id_result,
          'title' => $test->title,
          'score' => $this->score,
          'start' => $test->start,
          'end' => $test->end,
        ];
    }
}
