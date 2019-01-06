<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
  protected $table = 'questions';
  protected $primaryKey = 'id_question';
  protected $fillable = ['content','id_test'];

  public $timestamps = false;

  public function test()
  {
    return $this->belongsTo(Tests::class,'id_test','id_test');
  }

  public function answers()
  {
    return $this->hasMany(Answers::class, 'id_question');
  }

  public function images()
  {
    return $this->hasMany(Images::class, 'id_question');
  }

  public function detail()
  {
    return $this->hasMany(DetailedResults::class, 'id_question');
  }
}
