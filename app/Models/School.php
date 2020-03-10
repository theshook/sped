<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
	use SoftDeletes;
	protected $fillable = ['province_id', 'name'];

	public function province()
	{
		return $this->belongsTo(Province::class);
	}

	public function pupils()
	{
		return $this->hasMany(Pupil::class);
	}

	public function teachers()
	{
		return $this->hasMany(Teacher::class);
	}
}
