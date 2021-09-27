<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StudentMeta
 *
 * @property $id
 * @property $student_id
 * @property $name
 * @property $content
 *
 * @property Student $student
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class StudentMeta extends Model
{
    
    static $rules = [
		'student_id' => 'required',
		'name' => 'required',
		'content' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['student_id','name','content'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne('App\Models\Student', 'id', 'student_id');
    }
    

}
