<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Teacher
 *
 * @property $id
 * @property $NIK
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * @property TeacherMeta[] $teacherMetas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Teacher extends Model
{
    
    static $rules = [
		'NIK' => 'required',
		'name' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['NIK','name'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teacherMetas()
    {
        return $this->hasMany('App\Models\TeacherMeta', 'teacher_id', 'id');
    }

}
