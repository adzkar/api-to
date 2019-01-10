<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tests extends Model
{
  protected $table = 'tests';
  protected $primaryKey = 'id_test';
  protected $fillable = [
    'title','information','start','end','id_com','duration','true_value','empty_value','wrong_value'
  ];

  public $timestamps = false;

  public function committee()
  {
    return $this->belongsTo(Committees::class,'id_com','id_com');
  }

  public function questions()
  {
    return $this->hasMany(Questions::class, 'id_test');
  }

  public function results()
  {
    return $this->hasMany(Results::class, 'id_test');
  }

}
