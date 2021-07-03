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
		'account_code' => 'required',
		'account_transaction_code' => 'required',
		'name' => 'required',
		'pos' => 'required',
		'normal_balance' => 'required',
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
    protected $fillable = ['parent_account_id','account_code','account_transaction_code','name','balance','pos','normal_balance','balance'];

    // public function getChildsAttribute()
    // {
    //     $parent_childs = $this->childs()->pluck('id');
    //     $active_childs = Account::whereIn('parent_account_id',$parent_childs)->get();
    //     return $active_childs;
    //     // return $parent_childs;
    // }

    public function childs()
    {
        return $this->hasMany('App\Models\Account', 'parent_account_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Account', 'id', 'parent_account_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        if(isset($_GET['from']) && isset($_GET['to']))
            return $this->hasMany('App\Models\Transaction', 'account_id', 'id')->whereBetween('date',[$_GET['from'],$_GET['to']]);
        return $this->hasMany('App\Models\Transaction', 'account_id', 'id');
    }

    public function getTDebtAttribute()
    {
        if(isset($_GET['from']) && isset($_GET['to']))
            return $this->transactions()->whereBetween('date',[$_GET['from'],$_GET['to']])->sum('debt');
        return $this->transactions()->sum('debt');
    }

    public function getTCreditAttribute()
    {
        if(isset($_GET['from']) && isset($_GET['to']))
            return $this->transactions()->whereBetween('date',[$_GET['from'],$_GET['to']])->sum('credit');
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
        $balance = $this->normal_balance == 'Db' ? $this->t_debt - $this->t_credit : $this->t_credit - $this->t_debt;
        return $balance;
    }

    public function getTBalanceAttribute()
    {
        $balance = $this->normal_balance == 'Db' ? $this->t_debt - $this->t_credit : $this->t_credit - $this->t_debt;
        return $this->balance + $balance;
    }

    public function getTBalanceFormatAttribute()
    {
        $balance = $this->t_balance;
        return $balance >= 0 ? number_format($balance) : '('.number_format(abs($balance)).')';
    }

    // public function getBalanceAttribute()
    // {
    //     $balance = $this->normal_balance == 'Db' ? $this->debt - $this->credit : $this->credit - $this->debt;
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

    function balance_from_child()
    {
        $balance = 0;
        $childs = $this->childs;
        if(count($childs))
        foreach($childs as $child)
        {
            if(count($child->childs))
            {
                $temp_balance = $child->balance_from_child();
                if($child->normal_balance == 'Db')
                    $balance += $temp_balance;
                else
                {
                    $balance -= $temp_balance;
                }
            }
            else
            {
                if($child->normal_balance == 'Db')
                    $balance += $child->t_balance;
                else
                    $balance -= $child->t_balance;
            }
        }

        return $balance;
    }

    function balance_format()
    {
        $balance_from_child = $this->balance_from_child();
        $balance_from_child_format = $balance_from_child >= 0 ? number_format($balance_from_child) : "(".number_format(abs($balance_from_child)).")";
        return $balance_from_child ? $balance_from_child_format : $this->t_balance_format;
    }
    

}
