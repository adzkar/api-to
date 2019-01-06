<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participants extends Model
{
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
