<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'admin';
    protected $fillable = ['username','password','name'];
    protected $hidden = ['password'];

    public $timestamps = false;

    public function committees()
    {
      return $this->hasMany();
    }
}
