<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'name'];

    public function curriculums()
    {
        return $this->hasMany(Curriculum::class);
    }

    public function subjects()
    {
        return $this->hasManyThrough(
            \App\Models\Subject::class,
            \App\Models\Curriculum::class,
            'course_id',       // Foreign key on curriculums table
            'curriculum_id',   // Foreign key on subjects table
            'id',              // Local key on courses table
            'id'               // Local key on curriculums table
        );
    }
}
