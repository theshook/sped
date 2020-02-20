<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['school_id', 'first_name', 'last_name', 'middle_name'];
}
