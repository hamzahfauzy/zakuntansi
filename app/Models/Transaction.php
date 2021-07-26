<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 *
 * @property $id
 * @property $account_id
 * @property $ref_account_id
 * @property $date
 * @property $description
 * @property $reference
 * @property $debt
 * @property $credit
 * @property $created_at
 * @property $updated_at
 *
 * @property Account $account
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Transaction extends Model
{
    
    static $rules = [
		'account_id'       => 'required',
		'transaction_code' => 'required',
		'date'             => 'required',
		'description'      => 'required',
		'debt'             => 'required',
		'credit'           => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['account_id','parent_transaction_id','date','description','debt','credit','transaction_code'];

    protected $casts = [
      'date' => 'datetime',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne('App\Models\Account', 'id', 'account_id');
    }

    public function getBalanceAttribute()
    {
        $balance = $this->account->normal_balance == 'Db' ? $this->debt - $this->credit : $this->credit - $this->debt;
        return $balance;
    }

    public function getBalanceFormatAttribute()
    {
        $balance = $this->balance;
        return $balance >= 0 ? number_format($balance) : '('.number_format(abs($balance)).')';
    }

    public function getDebtFormatAttribute()
    {
        return number_format($this->debt);
    }

    public function getCreditFormatAttribute()
    {
        return number_format($this->credit);
    }

    public function items()
    {
        return $this->hasMany(Transaction::class,'parent_transaction_id','id')->orderby('date','asc');
    }

    public function parent()
    {
        return $this->hasOne(Transaction::class,'id','parent_transaction_id');
    }

}
