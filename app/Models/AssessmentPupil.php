<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentPupil extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'assessment_id',
    'pupil_id',
    'is_locked',
    'total_score',
    'status',
    'recommendation',
    'validated_by',
    'validated_date',
    'closed_by',
    'closed_date',
  ];
}
