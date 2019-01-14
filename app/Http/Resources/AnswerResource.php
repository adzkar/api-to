<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImageResource as ImgRes;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $status = ($this->status ? true:false);
        return [
          'id' => $this->id_answer,
          'option' => $this->option,
          'status' => ($this->status ? true:false),
          'images' => ImgRes::collection($this->images),
        ];
    }
}
