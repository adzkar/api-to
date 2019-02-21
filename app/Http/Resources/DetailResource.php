<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\AnswerParResource as AnsRes;
use App\Http\Resources\ImageResource as ImgRes;

class DetailResource extends JsonResource
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
          'id' => $this->id_dr,
          'question' => $this->question->content,
          'image' => ImgRes::collection($this->question->images),
          'answer' => new AnsRes($this->answer),
          'options' => AnsRes::collection($this->question->answers),
        ];
    }
}
