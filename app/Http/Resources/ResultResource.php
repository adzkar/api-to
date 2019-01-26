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
        $detail = $this->detail;
        $score = 0;
        for ($i=0; $i < $detail->count(); $i++) {
          if($detail[$i]->status === null)
            $score += $test->empty_value;
          if($detail[$i]->status === false)
            $score += $test->wrong_value;
          if($detail[$i]->status === true)
            $score += $test->true_value;
        }

        return [
          'title' => $test->title,
          'start' => $test->start,
          'end' => $test->end,
          'score' => $score,
        ];
    }
}
