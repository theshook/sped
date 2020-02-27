<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
	use SoftDeletes;
	protected $fillable = ['teacher_id', 'title', 'description', 'test_questions'];

	public function questions() {
		return $this->hasMany(Question::class);
	}
}
