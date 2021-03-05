<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 *
 * @property $id
 * @property $name
 * @property $date_from
 * @property $date_to
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Account[] $accounts
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Book extends Model
{
    
    static $rules = [
		'name' => 'required',
		'date_from' => 'required',
		'date_to' => 'required',
		'status' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','date_from','date_to','status'];

    protected $casts = [
      'date_from' => 'datetime',
      'date_to' => 'datetime',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany('App\Models\Account', 'book_id', 'id');
    }
    

}
