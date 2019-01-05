<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailedResults extends Model
{
  protected $table = 'detailed_results';
  protected $primaryKey = 'id_dr';
  protected $fillable = ['status','id_result','id_question','id_answer'];

  public $timestamps = false;

  public function result()
  {
    return $this->belongsTo(Results::class,'id_result','id_result');
  }

  public function question()
  {
    return $this->belongsTo(Questions::class,'id_question','id_question');
  }

  public function answer()
  {
    return $this->belongsTo(Answers::class,'id_answer','id_answer');
  }
}
