<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AnswerResource as AnsRes;

class QuestionResource extends JsonResource
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
          'id' => $this->id_question,
          'test_title' => $this->test->title,
          'question' => $this->content,
          'answers' => AnsRes::collection($this->answers),
        ];
    }
}
