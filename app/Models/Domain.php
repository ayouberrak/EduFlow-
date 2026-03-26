<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable =[
        'name'
    ];


    public function users(){
        return $this->belongsToMany(User::class, 'domain_users');
    }

    public function courses(){
        return $this->hasMany(Course::class);
    }
}
