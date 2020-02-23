<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checklist extends Model
{
    use SoftDeletes;
    protected $fillable = ['content', 'checklist_categories_id'];

    public function category()
    {
        return $this->belongsTo(ChecklistCategory::class, 'checklist_categories_id');
    }
}
