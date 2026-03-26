<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable =[
        'title',
        'description',
        'price',
        'teacher_id',
        'domain_id'
    ];  


    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function domain() {
        return $this->belongsTo(Domain::class);
    }

    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }

    public function groups() {
        return $this->hasMany(Group::class);
    }

    public function favoritedBy() {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
