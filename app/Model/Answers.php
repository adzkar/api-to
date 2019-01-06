<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
  protected $table = 'answers';
  protected $primaryKey = 'id_answer';
  protected $fillable = ['option','status','id_question'];

  public $timestamps = false;

  public function question()
  {
    return $this->belongsTo(Questions::class,'id_question','id_question');
  }

  public function images()
  {
    return $this->hasMany(Images::class, 'id_answer');
  }

  public function detail()
  {
    return $this->hasMany(DetailedResults::class, 'id_answer');
  }
}
