<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Bill
 *
 * @property $id
 * @property $student_id
 * @property $merchant_id
 * @property $year
 * @property $total
 * @property $due_date
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property Merchant $merchant
 * @property Student $student
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Bill extends Model
{
    
    static $rules = [
		'user_id.*' => 'required',
		'merchant_id' => 'required',
		'year' => 'required',
		'total' => 'required',
		'due_date' => 'required',
		'jumlah_termin' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','merchant_id','year','total','due_date','status','termin'];

    public function getBillNameAttribute()
    {
        return $this->merchant->name . ' - ' . $this->year;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function merchant()
    {
        return $this->hasOne('App\Models\Merchant', 'id', 'merchant_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function getSumPaymentAttribute(){
        return $this->payments->sum('total');
    }

    public function getSumPaymentFormattedAttribute(){
        return number_format($this->sum_payment);
    }
    
    public function getSisaAttribute()
    {
        return $this->total - $this->payments->sum('total');
    }

    public function getSisaFormattedAttribute()
    {
        return number_format($this->sisa);
    }

    public function getTotalFormattedAttribute()
    {
        return number_format($this->total);
    }

    public function getStatusLabelAttribute()
    {
        $status = 'success';
        if($this->status == 'BELUM DIBAYAR') $status = 'danger';
        if($this->status == 'BELUM LUNAS') $status = 'warning';
        return '<span class="badge badge-'.$status.'">'.$this->status.'</span>';
    }
    

}
