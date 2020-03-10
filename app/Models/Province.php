<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
	use SoftDeletes;
	protected $fillable = ['name'];

	public function schools()
	{
		return $this->hasMany(School::class);
	}
}
