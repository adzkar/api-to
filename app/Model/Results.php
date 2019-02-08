<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
  protected $table = 'results';
  protected $primaryKey = 'id_result';
  protected $fillable = ['date','id_participant','id_test','status','score'];

  public $timestamps = false;

  public function participant()
  {
    return $this->belongsTo(Participants::class,'id_participant','id_participant');
  }

  public function test()
  {
    return $this->belongsTo(Tests::class,'id_test','id_test');
  }

  public function detail()
  {
    return $this->hasMany(DetailedResults::class, 'id_result');
  }
}
