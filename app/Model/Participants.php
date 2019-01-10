<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Participants extends Authenticatable
{
  use HasApiTokens, Notifiable;

  protected $table = 'participants';
  protected $primaryKey = 'id_participant';
  protected $fillable = ['first_name','last_name','username','password','id_ver','school'];
  protected $hidden = ['password'];

  public $timestamps = false;

  public function verification()
  {
    return $this->belongsTo(Verification::class,'id_ver','id_ver');
  }

  public function results()
  {
    return $this->hasMany(Results::class, 'id_participant');
  }

}
