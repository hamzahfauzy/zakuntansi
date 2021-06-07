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
        $accounts = Account::where('book_id',$this->book_id())
            ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
            ->orderBy('ref_accounts.account_code','asc')
            ->select('accounts.*')
            ->get()->pluck('id')->toArray();
        $transactions = Transaction::whereIn('account_id',$accounts)->groupby('account_id')->orderByRaw('FIELD(account_id,'.implode(",",$accounts).')')->get();
        // $transactions = Transaction::whereIn('account_id',$accounts)->orderby('account_id')->paginate();

        return view('transaction.index', compact('transactions'))
            ->with('i', 0); //(request()->input('page', 1) - 1) * $transactions->perPage());
    }

    public function bukuBesar()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $accounts = Account::where('book_id',$this->book_id())->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')->orderBy('ref_accounts.account_code')->select('accounts.*',DB::raw("CONCAT(ref_accounts.account_code,' - ',ref_accounts.name) AS ref_account_name"))->pluck('ref_account_name','id');
        $transactions = Transaction::where('account_id',$id)->paginate();
        $selected_account = Account::find($id);
        return view('transaction.buku-besar', compact('transactions','accounts','id','selected_account'));
    }

    public function cetakBuku($id)
    {
        $accounts = Account::where('book_id',$this->book_id())->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')->orderBy('ref_accounts.account_code')->select('accounts.*',DB::raw("CONCAT(ref_accounts.account_code,' - ',ref_accounts.name) AS ref_account_name"))->pluck('ref_account_name','id');
        $transactions = Transaction::where('account_id',$id)->paginate();
        $selected_account = Account::find($id);
        return view('transaction.cetak-buku', compact('transactions','accounts','id','selected_account'));
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
        // return $request->all();
        $request->validate([
            'account_id' => 'required',
            'tipe.*'     => 'required',
            'date.*'     => 'required',
            'description.*' => 'required',
            'nominal.*'  => 'required',
        ]);

        foreach($request->transaction_id as $key => $transaction_id)
        {
            Transaction::create([
                'account_id' => $request->account_id,
                'description' => $request->description[$key],
                'date' => $request->date[$key],
                'debt' => $request->tipe[$key] == 'Debt' ? $request->nominal[$key] : 0,
                'credit' => $request->tipe[$key] == 'Credit' ? $request->nominal[$key] : 0,
            ]);
        }

        // $transaction = Transaction::create($request->all());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    public function storeOld(Request $request)
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
        $transaction = Transaction::where('account_id',$id)->firstOrFail();
        $transactions = Transaction::where('account_id',$id)->get();
        $accounts = Account::where('book_id',$this->book_id())->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')->orderBy('ref_accounts.account_code')->select('accounts.*',DB::raw("CONCAT(ref_accounts.account_code,' - ',ref_accounts.name) AS ref_account_name"))->pluck('ref_account_name','id');

        return view('transaction.edit', compact('transaction','transactions','accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'account_id' => 'required',
            'tipe.*'     => 'required',
            'date.*'     => 'required',
            'description.*' => 'required',
            'nominal.*'  => 'required',
        ]);
        $transactions = Transaction::where('account_id',$id)->get()->pluck('id');
        foreach($transactions as $t)
        {
            if(!in_array($t, $request->transaction_id))
                Transaction::find($t)->delete();
        }
        foreach($request->transaction_id as $key => $value)
        {
            if($value == 'undefined')
            {
                Transaction::create([
                    'account_id' => $id,
                    'description' => $request->description[$key],
                    'date' => $request->date[$key],
                    'debt' => $request->tipe[$key] == 'Debt' ? $request->nominal[$key] : 0,
                    'credit' => $request->tipe[$key] == 'Credit' ? $request->nominal[$key] : 0,
                ]);
            }
            else
            {
                $transaction = Transaction::find($value);
                $transaction->update([
                    'description' => $request->description[$key],
                    'date' => $request->date[$key],
                    'debt' => $request->tipe[$key] == 'Debt' ? $request->nominal[$key] : 0,
                    'credit' => $request->tipe[$key] == 'Credit' ? $request->nominal[$key] : 0,
                ]);
            }
        }

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

    public function delete(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully');
    }

    public function cetakJurnal()
    {
        $accounts = Account::where('book_id',$this->book_id())
            ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
            ->orderBy('ref_accounts.account_code','asc')
            ->select('accounts.*')
            ->get()->pluck('id')->toArray();
        $transactions = Transaction::whereIn('account_id',$accounts)->groupby('account_id')->orderByRaw('FIELD(account_id,'.implode(",",$accounts).')')->get();
        $book = session('book');

        return view('transaction.cetak-jurnal', compact('transactions','book'))
            ->with('i', 0);
    }
}
