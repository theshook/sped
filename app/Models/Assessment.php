<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'teacher_id',
    'pupil_id',
    'test_id',
    'assessment_no',
    'assessment_date',
  ];

  public function teacher()
  {
    return $this->belongsTo(Teacher::class);
  }

  public function pupil()
  {
    return $this->belongsTo(Pupil::class);
  }

  public function assessment_pupils()
  {
    return $this->hasMany(AssessmentPupil::class);
  }

  public function test()
  {
    return $this->hasOne(Test::class);
  }
}
