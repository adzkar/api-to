<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Committees extends Authenticatable
{
  use HasApiTokens, Notifiable;

  protected $table = 'committees';
  protected $primaryKey = 'id_com';
  protected $fillable = ['name','username','password','id_ver'];
  protected $hidden = ['password'];

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
