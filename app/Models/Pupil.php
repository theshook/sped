<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pupil extends Model
{
    protected $fillable = [
		'school_id',
		'name',
		'age',
		'address'
	];
}
