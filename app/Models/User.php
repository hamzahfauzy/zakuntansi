<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    static $rules = [
        'name' => 'required',
        'email' => 'required',
        'password' => 'nullable',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getInstallationAttribute()
    {
        return Installation::first();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'user_roles');
    }

    public function hasRole($role) 
    {
        return $this->roles()->where('name', $role)->count() == 1;
    }

    public function hasRoles($roles) 
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function student()
    {
        return $this->hasOne(Student::class,'user_id','id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function bills()
    {
        return $this->hasMany('App\Models\Bill', 'user_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'user_id', 'id');
    }
}
