<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
	use SoftDeletes;
	protected $table = 'assessment_questions';
	protected $fillable = ['teacher_id', 'question', 'choice1', 'choice2', 'choice3', 'choice4', 'answer'];
	
	public function teacher() {
		return $this->belongsTo(Teacher::class);
	}

	public function test() {
		return $this->belongsTo(Test::class);
	}
}
