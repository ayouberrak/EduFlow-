<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function courses(){
        return $this->hasMany(Course::class,'teacher_id');
    }

    public function enrollments(){
        return $this->hasMany(Enrollment::class);
    }


    public function favorites(){
        return $this->belongsToMany(Course::class,'favorites');
    }

    public function domains(){
        return $this->belongsToMany(Domain::class, 'domain_users');
    }
}
