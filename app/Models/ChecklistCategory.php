<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function checklists()
    {
        return $this->hasMany(Checklist::class, 'checklist_categories_id');
    }
}
