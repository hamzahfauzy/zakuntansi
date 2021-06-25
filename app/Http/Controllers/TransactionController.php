<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\RefAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::doesntHave('parent')->orderBy('date')->get();
        return view('transaction.index', compact('transactions'))
            ->with('i', 0); //(request()->input('page', 1) - 1) * $transactions->perPage());
    }

    public function bukuBesar()
    {
        // $all_ref_accounts = RefAccount::doesntHave('childs')->get()->pluck('id');
        $accounts = Account::get();
        
        return view('transaction.buku-besar', compact('accounts'));
    }

    public function cetakBuku()
    {
        $all_ref_accounts = RefAccount::doesntHave('childs')->get()->pluck('id');
        $accounts = Account::where('book_id',$this->book_id())
                    ->whereIn('ref_account_id',$all_ref_accounts)
                    ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
                    ->orderBy('ref_accounts.account_code')
                    ->get();

        return view('transaction.cetak-buku', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transaction = new Transaction();
        $accounts = Account::doesntHave('childs')->select('accounts.*',DB::raw("CONCAT(account_code,' - ',name) AS account_name"))
                    ->pluck('account_name','id');
        $kode = [];
        foreach(Account::doesntHave('childs')->get() as $account)
            $kode[$account->id] = $account->account_transaction_code;
        return view('transaction.create', compact('transaction','accounts','kode'));
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
            'date'        => 'required',
            'account_id'  => 'required',
            'description' => 'required',
            'tipe'        => 'required',
            'nominal'     => 'required',
            'transaction_code'  => 'required',
            // 'item_account_id.*' => 'required',
            // 'item_nominal.*'    => 'required',
        ]);

        DB::beginTransaction();
        try {
            //code...
            $transaction = Transaction::create([
                'transaction_code' => $request->transaction_code,
                'account_id' => $request->account_id,
                'description' => $request->description,
                'date' => $request->date,
                'debt' => $request->tipe == 'Debt' ? $request->nominal : 0,
                'credit' => $request->tipe == 'Credit' ? $request->nominal : 0,
            ]);
    
            // insert item
            foreach($request->item_account_id as $key => $account_id)
            {
                if($account_id == null) continue;
                Transaction::create([
                    'parent_transaction_id' => $transaction->id,
                    'account_id' => $account_id,
                    'date' => $request->date,
                    'debt' => $request->item_tipe[$key] == 'Debt' ? $request->item_nominal[$key] : 0,
                    'credit' => $request->item_tipe[$key] == 'Credit' ? $request->item_nominal[$key] : 0,
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
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
    public function edit(Transaction $transaction)
    {
        $accounts = Account::doesntHave('childs')->select('accounts.*',DB::raw("CONCAT(account_code,' - ',name) AS account_name"))
                    ->pluck('account_name','id');
        $count_transaction = Transaction::count()+1;
        $count_transaction = $count_transaction < 10 ? '00'.$count_transaction : ($count_transaction < 100 ? '0'.$count_transaction : $count_transaction);
        $kode = [];
        foreach(Account::doesntHave('childs')->get() as $account)
            $kode[$account->id] = $account->account_transaction_code;
        return view('transaction.edit', compact('transaction','accounts','count_transaction','kode'));
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
        // return $request->all();
        $request->validate([
            'date'        => 'required',
            'account_id'  => 'required',
            'description' => 'required',
            'tipe'        => 'required',
            'nominal'     => 'required',
            'transaction_code'  => 'required',
            // 'item_account_id.*' => 'required',
            // 'item_nominal.*'    => 'required',
        ]);
        // $accs = Account::where('book_id',$this->book_id())->get()->pluck('id')->toArray();
        // $transactions = Transaction::whereIn('account_id',$accs)->get()->pluck('id');

        DB::beginTransaction();
        try {
            //code...
            $transaction->update([
                'transaction_code' => $request->transaction_code,
                'account_id' => $request->account_id,
                'description' => $request->description,
                'date' => $request->date,
                'debt' => $request->tipe == 'Debt' ? $request->nominal : 0,
                'credit' => $request->tipe == 'Credit' ? $request->nominal : 0,
            ]);
            if(is_array($request->item_id))
            {
                if($transaction->items()->exists())
                {
                    $items = $transaction->items()->pluck('id');
                    foreach($items as $t)
                    {
                        if(!in_array($t, $request->item_id))
                        Transaction::find($t)->delete();
                    }
                }
            }
            else
            {
                if($transaction->items()->exists())
                {
                    $items = $transaction->items()->pluck('id');
                    Transaction::whereIn('id',$items)->delete();
                }
            }


            if(is_array($request->item_account_id))
            foreach($request->item_account_id as $key => $value)
            {
                $item_id = $request->item_id[$key];
                if($item_id)
                {
                    $item = Transaction::find($item_id);
                    $item->update([
                        'account_id' => $request->item_account_id[$key],
                        'date' => $request->date,
                        'debt' => $request->item_tipe[$key] == 'Debt' ? $request->item_nominal[$key] : 0,
                        'credit' => $request->item_tipe[$key] == 'Credit' ? $request->item_nominal[$key] : 0,
                    ]);
                }
                else
                {
                    if($value == null || $value == 'undefined') continue;
                    Transaction::create([
                        'parent_transaction_id' => $transaction->id,
                        'account_id' => $value,
                        'date' => $request->date,
                        'debt' => $request->item_tipe[$key] == 'Debt' ? $request->item_nominal[$key] : 0,
                        'credit' => $request->item_tipe[$key] == 'Credit' ? $request->item_nominal[$key] : 0,
                    ]);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
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

    public function delete($account_id)
    {
        Transaction::where('account_id',$account_id)->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully');
    }

    public function cetakJurnal()
    {
        $accounts = Account::where('book_id',$this->book_id())
            // ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
            // ->orderBy('ref_accounts.account_code','asc')
            // ->select('accounts.*')
            ->get()->pluck('id')->toArray();
        $transactions = Transaction::whereIn('account_id',$accounts)->orderby('date')->get();
        $book = session('book');

        return view('transaction.cetak-jurnal', compact('transactions','book'))
            ->with('i', 0);
    }
}
