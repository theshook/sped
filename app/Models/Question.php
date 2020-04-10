<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
  use SoftDeletes;
  protected $fillable = ['teacher_id', 'checklist_id', 'question_type', 'question', 'choice1', 'choice2', 'choice3', 'choice4', 'answer'];

  public function teacher()
  {
    return $this->belongsTo(Teacher::class);
  }

  public function checklist()
  {
    return $this->belongsTo(Checklist::class);
  }
}
