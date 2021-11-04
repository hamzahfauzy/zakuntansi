<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Finance
 *
 * @property $id
 * @property $transaction_id
 * @property $category_id
 * @property $user_id
 * @property $staff_id
 * @property $total
 * @property $created_at
 * @property $updated_at
 *
 * @property Category $category
 * @property Transaction $transaction
 * @property User $user
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Finance extends Model
{
    
    static $rules = [
		// 'transaction_id' => 'required',
		'category_id' => 'required',
		// 'staff_id' => 'required',
		'total' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['transaction_id','category_id','user_id','staff_id','total','payment_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transaction()
    {
        return $this->hasOne('App\Models\Transaction', 'id', 'transaction_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function staff()
    {
        return $this->hasOne('App\Models\User', 'id', 'staff_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function getTotalFormattedAttribute()
    {
        return number_format($this->total);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'id', 'payment_id');
    }
    

}
