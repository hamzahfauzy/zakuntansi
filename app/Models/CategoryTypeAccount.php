<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoryTypeAccount
 *
 * @property $id
 * @property $account_id
 * @property $status
 *
 * @property Account $account
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CategoryTypeAccount extends Model
{
    
    static $rules = [
		'account_id' => 'required',
		'status' => 'required',
    ];

    public $timestamps = false;

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['account_id','status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne('App\Models\Account', 'id', 'account_id');
    }
    

}
