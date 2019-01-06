<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'username';
    protected $fillable = ['username','password','name'];
    protected $hidden = ['password'];

    public $timestamps = false;

    public function committees()
    {
      return $this->hasMany();
    }
}
