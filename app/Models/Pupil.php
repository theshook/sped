<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pupil extends Model
{
	use SoftDeletes;
    protected $fillable = [
		'school_id',
		'first_name',
		'last_name',
		'middle_name',
		'birth_date',
		'prof_pic'
	];
}
