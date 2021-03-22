<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
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
        $accounts = Account::where('book_id',$this->book_id())->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')->orderBy('ref_accounts.account_code')->select('accounts.*',DB::raw("CONCAT(ref_accounts.account_code,' - ',ref_accounts.name) AS ref_account_name"))->pluck('ref_account_name','id');
        $transactions = Transaction::where('account_id',$id)->paginate();

        return view('transaction.index', compact('transactions'))
            ->with('i', (request()->input('page', 1) - 1) * $transactions->perPage());
    }

    public function bukuBesar()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $accounts = Account::where('book_id',$this->book_id())->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')->orderBy('ref_accounts.account_code')->select('accounts.*',DB::raw("CONCAT(ref_accounts.account_code,' - ',ref_accounts.name) AS ref_account_name"))->pluck('ref_account_name','id');
        $transactions = Transaction::where('account_id',$id)->paginate();
        $selected_account = Account::find($id);
        return view('transaction.buku-besar', compact('transactions','accounts','id','selected_account'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transaction = new Transaction();
        $accounts = Account::where('book_id',$this->book_id())->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')->orderBy('ref_accounts.account_code')->select('accounts.*',DB::raw("CONCAT(ref_accounts.account_code,' - ',ref_accounts.name) AS ref_account_name"))->pluck('ref_account_name','id');
        return view('transaction.create', compact('transaction','accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Transaction::$rules);

        $transaction = Transaction::create($request->all());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::find($id);

        return view('transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = Transaction::find($id);
        $accounts = Account::where('book_id',$this->book_id())->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')->orderBy('ref_accounts.account_code')->select('accounts.*',DB::raw("CONCAT(ref_accounts.account_code,' - ',ref_accounts.name) AS ref_account_name"))->pluck('ref_account_name','id');

        return view('transaction.edit', compact('transaction','accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        request()->validate(Transaction::$rules);

        $transaction->update($request->all());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id)->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully');
    }
}
