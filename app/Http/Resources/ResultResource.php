<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // init
        $test = $this->test;

        return [
          'title' => $test->title,
          'start' => $test->start,
          'end' => $test->end,
          'score' => $this->score,
        ];
    }
}
