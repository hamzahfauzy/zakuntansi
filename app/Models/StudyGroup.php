<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StudyGroup
 *
 * @property $id
 * @property $name
 * @property $level
 * @property $created_at
 * @property $updated_at
 *
 * @property Student[] $students
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class StudyGroup extends Model
{
    
    static $rules = [
		'name' => 'required',
		'level' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','level'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\Models\Student', 'group_id', 'id');
    }
    

}
