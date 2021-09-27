<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Role;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Installation;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->installation = Installation::first();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $installation = $this->installation;
        return view('home',compact('installation'));
    }

    public function installation(Request $request)
    {
        if($request->method() == 'POST')
        {
            $request->validate([
                'company_name' => 'required',
                'phone_number' => 'required',
                'address' => 'required',
                'company_email' => 'required',
                'postal_code' => 'required',
                'email' => 'required',
                'password' => 'required',
                'logo' => 'required|file|max:500',
            ]);
            $logo = $request->file('logo')->store('logo');
            Installation::create([
                'company_name' => $request->company_name,
                'email' => $request->company_email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'logo' => $logo,
            ]);

            $user = User::create([
                'name' => $request->company_name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $role = Role::where('name','Master')->first();

            $user->roles()->sync([$role->id]);

            return redirect()->route('home');
        }
        return view('installation');
    }

    function count_transaction($transaction_code, $month)
    {
        $transaction_code = $transaction_code .'/'.$month;
        $count_transaction = Transaction::doesntHave('parent')->where('transaction_code','LIKE','%'.$transaction_code.'%')->count()+1;
        $count_transaction = $count_transaction < 10 ? '00'.$count_transaction : ($count_transaction < 100 ? '0'.$count_transaction : $count_transaction);
        return ['code'=>$transaction_code.'/'.$count_transaction];
    }
}
