<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PupilParent extends Model
{
  use SoftDeletes;
  protected $table = 'parents';
  protected $fillable = ['name'];
}
