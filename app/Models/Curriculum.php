<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    // ✅ Explicitly tell Laravel which table to use
    protected $table = 'curriculums';

    protected $fillable = [
        'course_id',   // ❌ FIXED: was "2_id"
        'name',
        'year_start',
        'year_end',
        'is_active',
    ];

    // ✅ Each curriculum belongs to one course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // ✅ Each curriculum has many subjects
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'curriculum_id');
    }
}
