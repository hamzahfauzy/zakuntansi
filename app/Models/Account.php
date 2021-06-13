<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 *
 * @property $id
 * @property $book_id
 * @property $ref_account_id
 * @property $debt
 * @property $credit
 * @property $created_at
 * @property $updated_at
 *
 * @property Book $book
 * @property RefAccount $refAccount
 * @property Transaction[] $transactions
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Account extends Model
{
    
    static $rules = [
		'book_id' => 'required',
		'ref_account_id' => 'required',
		'balance' => 'required',
		// 'debt' => 'required',
		// 'credit' => 'required',
    ];

    // static $update_rules = [
	// 	'debt' => 'required',
	// 	'credit' => 'required',
    // ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['book_id','ref_account_id','balance'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'book_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function refAccount()
    {
        return $this->hasOne('App\Models\RefAccount', 'id', 'ref_account_id');
    }

    public function getChildsAttribute()
    {
        $book_id = $this->book_id;
        $parent_childs = $this->refAccount->childs()->pluck('id');
        $active_childs = Account::where('book_id',$book_id)->whereIn('ref_account_id',$parent_childs)->get();
        return $active_childs;


        // return $parent_childs;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'account_id', 'id');
    }

    public function getTDebtAttribute()
    {
        return $this->transactions()->sum('debt');
    }

    public function getTCreditAttribute()
    {
        return $this->transactions()->sum('credit');
    }

    public function getTDebtFormatAttribute()
    {
        return number_format($this->t_debt);
    }

    public function getTCreditFormatAttribute()
    {
        return number_format($this->t_credit);
    }

    public function getTNetAttribute()
    {
        $balance = $this->refAccount->normal_balance == 'Db' ? $this->t_debt - $this->t_credit : $this->t_credit - $this->t_debt;
        return $balance;
    }

    public function getTBalanceAttribute()
    {
        $balance = $this->refAccount->normal_balance == 'Db' ? $this->t_debt - $this->t_credit : $this->t_credit - $this->t_debt;
        return $this->balance + $balance;
    }

    public function getTBalanceFormatAttribute()
    {
        $balance = $this->t_balance;
        return $balance >= 0 ? number_format($balance) : '('.number_format(abs($balance)).')';
    }

    // public function getBalanceAttribute()
    // {
    //     $balance = $this->refAccount->normal_balance == 'Db' ? $this->debt - $this->credit : $this->credit - $this->debt;
    //     return $balance;
    // }

    public function getTNetFormatAttribute()
    {
        $balance = $this->t_net;
        return $balance >= 0 ? number_format($balance) : '('.number_format(abs($balance)).')';
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
    

}
