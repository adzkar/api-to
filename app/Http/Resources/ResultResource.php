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
        $par = $this->participant;

        return [
          'title' => $test->title,
          'start' => $test->start,
          'end' => $test->end,
          'score' => $this->score,
          'id_participant' => $par->id_participant,
          'name' => $par->first_name.' '.$par->last_name,
          'school' => $par->school,
        ];
    }
}
