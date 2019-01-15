<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
  protected $table = 'images';
  protected $primaryKey = 'id_image';
  protected $fillable = ['mime','image','id_question','id_answer'];

  public $timestamps = false;

  public function question()
  {
    return $this->belongsTo(Questions::class,'id_question','id_question');
  }

  public function answer()
  {
    return $this->belongsTo(Answers::class,'id_answer','id_answer');
  }
}
