<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
  protected $table = 'verification';
  protected $primaryKey = 'id_ver';
  protected $fillable = ['code','status','start','end'];

  public $timestamps = false;

  public function committees()
  {
    return $this->hasMany(Committees::class, 'id_ver');
  }

  public function participants()
  {
    return $this->hasMany(Participants::class, 'id_ver');
  }
}
