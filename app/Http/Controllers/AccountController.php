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
    public function book_id()
    {
        return session('book')->id;
    }

    public function index()
    {
        $book = session('book');
        $accounts = Account::where('book_id',$book->id)
        ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
        ->where('ref_accounts.parent_id',NULL)
        ->orderBy('ref_accounts.account_code')->select('accounts.*')->paginate();

        return view('account.index', compact('accounts','book'))
            ->with('i', (request()->input('page', 1) - 1) * $accounts->perPage());
    }

    public function neraca()
    {
        $book = session('book');
        $header_account = RefAccount::whereDoesntHave('refAccount')->select(DB::Raw('CONCAT(account_code," - ",name) as account_name'),'id')->orderby('account_code')->get()->pluck('account_name','id');
        // $header_account = RefAccount::whereDoesntHave('refAccount')->get()->pluck('name','id');
        $accounts = [];
        $neraca = [];
        if(isset($_GET['account']))
        {
            // activa = hutang + modal
            $accounts = Account::where('book_id',$book->id)
            ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
            ->where('ref_accounts.parent_id',NULL)
            ->where('ref_accounts.pos','Nrc')
            ->orderBy('ref_accounts.account_code')->select('accounts.*')->get();
            

            $activa = Account::where('book_id',$book->id)->where('ref_account_id',$_GET['account']['activa'])->first();
            $hutang = Account::where('book_id',$book->id)->where('ref_account_id',$_GET['account']['hutang'])->first();
            $modal = Account::where('book_id',$book->id)->where('ref_account_id',$_GET['account']['modal'])->first();

            $neraca = [
                'aktiva' => $activa->balance_format(),
                'hutang' => $hutang->balance_format(),
                'modal' => $modal->balance_format(),
                'saldo' => number_format(($activa->balance_from_child() - ($hutang->balance_from_child()+$modal->balance_from_child())))
            ];
        }

        return view('account.neraca', compact('accounts','book','header_account','neraca'));
    }

    public function cetakNeraca()
    {
        $book = session('book');
        $accounts = Account::where('book_id',$book->id)->whereHas('refAccount',function($q){
            $q->where('pos','Nrc');
        })->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')->orderBy('ref_accounts.account_code')->select('accounts.*')->get();

        return view('account.cetak-neraca', compact('accounts','book'));
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $book_id = $this->book_id();
        // Account::truncate();
        $all_current_accounts = Account::where('book_id',$this->book_id())->get('ref_account_id');
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
        $account->book_id = $this->book_id();

        $all_accounts = RefAccount::select(DB::Raw('CONCAT(account_code," - ",name) as account_name'),'id')->orderby('account_code')->get()->pluck('account_name','id');
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
            'account_code' => 'required',
            'name'         => 'required',
            'pos'          => 'required',
            'balance'      => 'required',
            'normal_balance' => 'required',
            // 'debt'         => 'required',
            // 'credit'       => 'required'
        ]);

        $data = $request->all();
        unset($data['account_code']);
        unset($data['name']);
        unset($data['pos']);
        unset($data['normal_balance']);

        $ref_account = RefAccount::where('account_code',$request->account_code);
        if($ref_account->exists())
            $ref_account = $ref_account->first();
        else
        {
            $create_data = [
                'account_code' => $request->account_code,
                'name'         => $request->name,
                'pos'          => $request->pos,
                'normal_balance' => $request->normal_balance,
            ];
            if($request->parent_id) $create_data['parent_id'] = $request->parent_id;
            $ref_account = RefAccount::create($create_data);
        }
        $data['ref_account_id'] = $ref_account->id;

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

        return view('account.edit', compact('account'));
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
            'balance' => 'required'
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
