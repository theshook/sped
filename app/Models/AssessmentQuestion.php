<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentQuestion extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'assessment_pupil_id',
    'question_id',
    'question_type',
    'answer',
    'correct',
    'wrong',
  ];

  public function assessment_pupil()
  {
    return $this->belongsTo(AssessmentPupil::class, 'assessment_pupil_id');
  }

  public function question()
  {
    return $this->hasOne(Question::class);
  }
}
