<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'teacher_id', 
    'questions_id', 
    'title', 
    'description'
  ];

  public function teacher()
  {
    return $this->belongsTo(Teacher::class);
  }

  public function questions()
  {
    return $this->hasMany(Question::class);
  }

  public function assessments()
  {
    return $this->hasMany(Assessment::class);
  }
}
