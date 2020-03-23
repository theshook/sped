<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pupil extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'school_id',
    'parent_id',
    'first_name',
    'last_name',
    'middle_name',
    'birth_date',
    'prof_pic'
  ];

  public function school()
  {
    return $this->belongsTo(School::class);
  }

  public function parent()
  {
    return $this->belongsTo(PupilParent::class);
  }

  public function assessments()
  {
    return $this->hasMany(Assessment::class);
  }
}
