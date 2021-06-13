<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RefAccount
 *
 * @property $id
 * @property $parent_id
 * @property $account_code
 * @property $name
 * @property $pos
 * @property $normal_balance
 * @property $created_at
 * @property $updated_at
 *
 * @property Account[] $accounts
 * @property RefAccount[] $refAccounts
 * @property RefAccount $refAccount
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class RefAccount extends Model
{
    
    static $rules = [
		'account_code' => 'sometimes|required|unique:ref_accounts',
		'name' => 'required',
		'pos' => 'required',
		'normal_balance' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id','account_code','name','pos','normal_balance'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany('App\Models\Account', 'ref_account_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs()
    {
        return $this->hasMany('App\Models\RefAccount', 'parent_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function refAccount()
    {
        return $this->hasOne('App\Models\RefAccount', 'id', 'parent_id');
    }
    

}
