<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Committees extends Model
{
  protected $table = 'committees';
  protected $primaryKey = 'id_com';
  protected $fillable = ['name','username','password','id_ver'];

  public $timestamps = false;

  public function verification()
  {
    return $this->belongsTo(Verification::class,'id_ver','id_ver');
  }

  public function tests()
  {
    return $this->hasMany(Tests::class, 'id_com');
  }

}
