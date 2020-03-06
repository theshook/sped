<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
	use SoftDeletes;
	protected $fillable = ['school_id', 'first_name', 'last_name', 'middle_name'];

	public function school()
	{
		return $this->belongsTo(School::class);
	}

	public function tests()
	{
		return $this->hasMany(Test::class);
	}

	public function questions()
	{
		return $this->hasMany(Question::class);
	}
}
