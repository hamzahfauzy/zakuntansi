<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\RefAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Class AccountController
 * @package App\Http\Controllers
 */
class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $accounts = Account::where('parent_account_id',NULL)->orderby('account_code','asc')->paginate();

        return view('account.index', compact('accounts'))
            ->with('i', (request()->input('page', 1) - 1) * $accounts->perPage());
    }

    public function neraca()
    {
        $header_account = Account::doesntHave('parent')->select(DB::Raw('CONCAT(account_code," - ",name) as account_name'),'id')->orderby('account_code')->get()->pluck('account_name','id');
        // $header_account = RefAccount::whereDoesntHave('refAccount')->get()->pluck('name','id');
        $accounts = [];
        $neraca = [];
        if(isset($_GET['account']))
        {
            // activa = hutang + modal
            $accounts = Account::where('pos','Nrc')
            ->orderBy('account_code')->get();
            

            $activa = Account::where('id',$_GET['account']['activa'])->first();
            $hutang = Account::where('id',$_GET['account']['hutang'])->first();
            $modal = Account::where('id',$_GET['account']['modal'])->first();
            $saldo = ($activa ? $activa->balance_from_child() : 0) - (($hutang?$hutang->balance_from_child():0) + ($modal?$modal->balance_from_child():0));

            $neraca = [
                'aktiva' => $activa?$activa->balance_format():0,
                'hutang' => $hutang?$hutang->balance_format():0,
                'modal' => $modal?$modal->balance_format():0,
                'saldo' => number_format($saldo)
            ];
        }

        return view('account.neraca', compact('accounts','header_account','neraca'));
    }

    public function cetakNeraca()
    {
        $book = session('book');
        $accounts = Account::where('book_id',$book->id)
        ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
        ->where('ref_accounts.parent_id',NULL)
        ->where('ref_accounts.pos','Nrc')
        ->orderBy('ref_accounts.account_code')->select('accounts.*')->get();
        

        $activa = Account::where('book_id',$book->id)->where('ref_account_id',$_GET['account']['activa'])->first();
        $hutang = Account::where('book_id',$book->id)->where('ref_account_id',$_GET['account']['hutang'])->first();
        $modal = Account::where('book_id',$book->id)->where('ref_account_id',$_GET['account']['modal'])->first();
        $saldo = ($activa ? $activa->balance_from_child() : 0) - ($hutang?$hutang->balance_from_child():0) + ($modal?$modal->balance_from_child():0);

        $neraca = [
            'aktiva' => $activa?$activa->balance_format():0,
            'hutang' => $hutang?$hutang->balance_format():0,
            'modal' => $modal?$modal->balance_format():0,
            'saldo' => number_format($saldo)
        ];

        return view('account.cetak-neraca', compact('accounts','book','neraca'));
    }

    public function labaRugi()
    {
        $book = session('book');
        $accounts = Account::where('book_id',$book->id)->whereHas('refAccount',function($q){
            $q->where('pos','Lr');
        })->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')->orderBy('ref_accounts.account_code')->select('accounts.*')->get();

        return view('account.laba-rugi', compact('accounts','book'));
    }

    public function cetakLabaRugi()
    {
        $book = session('book');
        $accounts = Account::where('book_id',$book->id)->whereHas('refAccount',function($q){
            $q->where('pos','Lr');
        })->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')->orderBy('ref_accounts.account_code')->select('accounts.*')->get();

        return view('account.cetak-laba-rugi', compact('accounts','book'));
    }

    public function import()
    {
        return;
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // Account::truncate();
        $all_current_accounts = Account::get('id');
        $all_master_accounts = RefAccount::whereNotIn('id',$all_current_accounts)->get();
        foreach($all_master_accounts as $account)
        {
            Account::create([
                'book_id' => $book_id,
                'ref_account_id' => $account->id,
                'debt' => 0,
                'credit' => 0,
            ]);
        }

        return redirect()->route('accounts.index')->with('success','Berhasil import seluruh data akun');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account = new Account();

        $all_accounts = Account::select(DB::Raw('CONCAT(account_code," - ",name) as account_name'),'id')->orderby('account_code')->get()->pluck('account_name','id');
        return view('account.create', compact('account','all_accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_account_id' => 'nullable',
            'account_code' => 'required|unique:accounts',
            'name'         => 'required',
            'pos'          => 'required',
            'balance'      => 'required',
            'normal_balance' => 'required',
            'account_transaction_code' => 'required',
        ]);

        $data = $request->all();
        $account = Account::create($data);

        return redirect()->route('accounts.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    public function insert(Request $request)
    {
        // check existing code
        $account = Account::whereHas('refAccount',function($q) use ($request){
            $q->where('account_code',$request->account_code);
        })->exists();
        if($account) return ['status'=>false,'msg'=>'Kode Akun sudah digunakan','data'=>[]];

        $ref_account = RefAccount::where('account_code',$request->account_code);
        if($ref_account->exists())
            $ref_account = $ref_account->first();
        else
            $ref_account = RefAccount::create([
                'name' => $request->name,
                'account_code' => $request->account_code,
                'pos' => $request->pos,
                'normal_balance' => $request->normal_balance
            ]);

        $account = Account::create([
            'book_id' => $this->book_id(),
            'ref_account_id' => $ref_account->id,
            'debt' => $request->debt,
            'credit' => $request->credit
        ]);

        return ['status'=>true,'msg'=>'Akun berhasil ditambah','data'=>$account]; 
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Account::find($id);

        return view('account.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = Account::find($id);
        $all_accounts = Account::select(DB::Raw('CONCAT(account_code," - ",name) as account_name'),'id')->orderby('account_code')->get()->pluck('account_name','id');
        return view('account.edit', compact('account','all_accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Account $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        request()->validate([
            'parent_account_id' => 'nullable',
            'account_code' => 'required|unique:accounts,account_code,'.$account->id,
            'name'         => 'required',
            'pos'          => 'required',
            'balance'      => 'required',
            'normal_balance' => 'required',
            'account_transaction_code' => 'required',
        ]);

        $account->update($request->all());

        return redirect()->route('accounts.index')
            ->with('success', 'Akun berhasil diedit');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $account = Account::find($id)->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Akun berhasil dihapus');
    }
}
