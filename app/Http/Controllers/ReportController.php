<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\User;
use App\Models\Payment;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $model = null;

        $merchants = Merchant::get();
        $users = User::get()->pluck('name','id');

        if(isset($_GET)){
            extract($_GET);

            if(isset($_GET['type']) && $type == "Pembayaran"){

                $model = new Payment();

                if(isset($merchant) && !empty($merchant) && isset($year) && !empty($year)){
                    
                    $bill = new Bill();

                    if($merchant){
                        $bill = $bill->where('merchant_id',$merchant);
                    }

                    if($year){
                        $bill = $bill->where('year',$year);
                    }

                    if($bill = $bill->first()){
                        $model = $model->where('bill_id',$bill->id);
                    }
                    
                }

                if(isset($user_id) && !empty($user_id))
                {
                    $model = $model->where('user_id',$user_id);
                }

                $model = $model->get();
                
            }else{
                $model = new Bill();

                if(isset($merchant) && !empty($merchant) && isset($year) && !empty($year)){

                    if($merchant){
                        $model = $model->where('merchant_id',$merchant);
                    }

                    if($year){
                        $model = $model->where('year',$year);
                    }
                    
                }

                if(isset($user_id) && !empty($user_id))
                {
                    $model = $model->where('user_id',$user_id);
                }

                $model = $model->get();
            }

            return view('report.index', compact('model','merchants','users'));
        }

        return view('report.index', compact('model','merchants','users'));
    }
}
