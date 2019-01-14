<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
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
          'id' => $this->id_image,
          'file_name' => $this->file_name,
          'url' => url('/images/'.$this->file_name),
          'mime' => $this->mime,
        ];
    }
}
