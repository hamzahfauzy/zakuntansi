<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class PaymentController
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereHas('roles',function($query){
            $query->whereIn('name',['Siswa','Guru / Pegawai']);
        })
        ->select(DB::Raw('CONCAT(name," - ",email) as user_name'),DB::Raw('id as _user_id'))
        ->get()->pluck('user_name','_user_id');
        $payments = Payment::paginate();

        return view('payment.index', compact('payments','users'))
            ->with('i', (request()->input('page', 1) - 1) * $payments->perPage());
    }

    function cetak($user,$date){
        $usr = User::find($user);
        $payments = Payment::where('user_id',$user)->where('created_at','like','%'.$date.'%')->get();

        return view("payment.cetak",compact('payments','usr','date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::findOrFail($_GET['user_id']);
        $bills = $user->bills()->whereIn('status',['BELUM DIBAYAR','BELUM LUNAS'])->get()->pluck('merchant.name','id');
        $payment = new Payment();
        $payment->user_id = $_GET['user_id'];
        return view('payment.create', compact('payment','bills'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['staff_id'] = auth()->user()->id;
        
        // get payment
        
        foreach($request['payment'] as $p){

            $bill = Bill::where('user_id',$request->user_id)->where('id',$p['bill_id'])->first();
            $payment = Payment::where('user_id',$request->user_id)->where('bill_id',$p['bill_id'])->sum('total');

            $total_payment = $payment+$p['total'];
             
            if($bill->total-$total_payment == 0)
                $bill->update(['status'=>'LUNAS']);
            else
                $bill->update(['status'=>'BELUM LUNAS']);

            $payment = Payment::create([
                'staff_id'=>$request->staff_id,
                'user_id'=>$request->user_id,
                'bill_id'=>$p['bill_id'],
                'total'=>$p['total'],
            ]);
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::find($id);

        return view('payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        return;
        $payment = Payment::find($id);

        return view('payment.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        return;
        request()->validate(Payment::$rules);

        $payment->update($request->all());

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasRole('Bendahara')) return redirect()->back();
        $payment = Payment::find($id)->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully');
    }
}
