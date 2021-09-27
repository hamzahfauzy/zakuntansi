<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TeacherMeta
 *
 * @property $id
 * @property $teacher_id
 * @property $name
 * @property $content
 *
 * @property Teacher $teacher
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TeacherMeta extends Model
{
    
    static $rules = [
		'teacher_id' => 'required',
		'name' => 'required',
		'content' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['teacher_id','name','content'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher', 'id', 'teacher_id');
    }
    

}
