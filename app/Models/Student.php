<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Student
 *
 * @property $id
 * @property $NIS
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * @property Bill[] $bills
 * @property StudentMeta[] $studentMetas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Student extends Model
{
    
    static $rules = [
		'NIS' => 'required',
		'name' => 'required',
		'group_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['NIS','name','user_id','group_id'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentMetas()
    {
        return $this->hasMany('App\Models\StudentMeta', 'student_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function studyGroup()
    {
        return $this->hasOne('App\Models\StudyGroup', 'id', 'group_id');
    }
    
    

}
