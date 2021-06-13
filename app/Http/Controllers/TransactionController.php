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
            // ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
            // ->orderBy('ref_accounts.account_code','asc')
            // ->select('accounts.*')
            ->get()->pluck('id')->toArray();
        $transactions = [];
        if(!empty($accounts))
            $transactions = Transaction::whereIn('account_id',$accounts)->whereDoesntHave('parent')->orderBy('date')->get();
            // $transactions = Transaction::whereIn('account_id',$accounts)->groupby('account_id')->orderByRaw('FIELD(account_id,'.implode(",",$accounts).')')->get();
        // $transactions = Transaction::whereIn('account_id',$accounts)->orderby('account_id')->paginate();

        return view('transaction.index', compact('transactions'))
            ->with('i', 0); //(request()->input('page', 1) - 1) * $transactions->perPage());
    }

    public function bukuBesar()
    {
        $all_ref_accounts = RefAccount::doesntHave('childs')->get()->pluck('id');
        $accounts = Account::where('book_id',$this->book_id())
                    ->whereIn('ref_account_id',$all_ref_accounts)
                    ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
                    ->orderBy('ref_accounts.account_code')
                    ->get();
        
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
        $all_ref_accounts = RefAccount::doesntHave('childs')->get()->pluck('id');
        $accounts = Account::where('book_id',$this->book_id())->whereIn('ref_account_id',$all_ref_accounts)
                    ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
                    ->orderBy('ref_accounts.account_code')
                    ->select('accounts.*',DB::raw("CONCAT(ref_accounts.account_code,' - ',ref_accounts.name) AS ref_account_name"))
                    ->pluck('ref_account_name','id');
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
        $request->validate([
            'date'        => 'required',
            'account_id'  => 'required',
            'description' => 'required',
            'tipe'        => 'required',
            'nominal'     => 'required',
            'transaction_code'  => 'required',
            'item_account_id.*' => 'required',
            'item_nominal.*'    => 'required',
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
                Transaction::create([
                    'parent_id' => $transaction->id,
                    'account_id' => $account_id,
                    'date' => $request->date,
                    'debt' => $request->item_tipe[$key] == 'Debt' ? $request->item_nominal[$key] : 0,
                    'credit' => $request->item_tipe[$key] == 'Credit' ? $request->item_nominal[$key] : 0,
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
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
        $all_ref_accounts = RefAccount::doesntHave('childs')->get()->pluck('id');
        $accounts = Account::where('book_id',$this->book_id())->whereIn('ref_account_id',$all_ref_accounts)
                    ->join('ref_accounts', 'accounts.ref_account_id', '=', 'ref_accounts.id')
                    ->orderBy('ref_accounts.account_code')
                    ->select('accounts.*',DB::raw("CONCAT(ref_accounts.account_code,' - ',ref_accounts.name) AS ref_account_name"))
                    ->pluck('ref_account_name','id');

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
        // return $request->all();
        $request->validate([
            'date'        => 'required',
            'account_id'  => 'required',
            'description' => 'required',
            'tipe'        => 'required',
            'nominal'     => 'required',
            'transaction_code'  => 'required',
            'item_account_id.*' => 'required',
            'item_nominal.*'    => 'required',
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
            $items = $transaction->items()->pluck('id');
            if(is_array($request->item_id))
            {
                foreach($items as $t)
                {
                    if(!in_array($t, $request->item_id))
                        Transaction::find($t)->delete();
                }
                foreach($request->item_id as $key => $value)
                {
                    if($value == 'undefined')
                    {
                        Transaction::create([
                            'parent_id' => $transaction->id,
                            'account_id' => $request->item_account_id[$key],
                            'debt' => $request->item_tipe[$key] == 'Debt' ? $request->item_nominal[$key] : 0,
                            'credit' => $request->item_tipe[$key] == 'Credit' ? $request->item_nominal[$key] : 0,
                        ]);
                    }
                    else
                    {
                        $item = Transaction::find($value);
                        $item->update([
                            'account_id' => $request->item_account_id[$key],
                            'date' => $request->date,
                            'debt' => $request->item_tipe[$key] == 'Debt' ? $request->item_nominal[$key] : 0,
                            'credit' => $request->item_tipe[$key] == 'Credit' ? $request->item_nominal[$key] : 0,
                        ]);
                    }
                }
            }
            else
                Transaction::whereIn('id',$items)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
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
