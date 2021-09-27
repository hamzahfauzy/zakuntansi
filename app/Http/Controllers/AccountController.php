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
        $header_account = Account::doesntHave('parent')->where('pos','Nrc')->select(DB::Raw('CONCAT(account_code," - ",name) as account_name'),'id')->orderby('account_code')->get()->pluck('account_name','id');
        // $header_account = RefAccount::whereDoesntHave('refAccount')->get()->pluck('name','id');
        $accounts = [];
        $neraca = [];
        if(isset($_GET['account']))
        {
            // activa = hutang + modal
            $accounts = Account::where('pos','Nrc')
            ->orderBy('account_code')->get();
            

            $activa = Account::with(['transactions'=>function($q) {
                $q->whereBetween('transactions.date',[$_GET['from'],$_GET['to']]);
            }])->where('id',$_GET['account']['activa'])->first();
            $hutang = Account::with(['transactions'=>function($q) {
                $q->whereBetween('transactions.date',[$_GET['from'],$_GET['to']]);
            }])->where('id',$_GET['account']['hutang'])->first();
            $modal = Account::with(['transactions'=>function($q) {
                $q->whereBetween('transactions.date',[$_GET['from'],$_GET['to']]);
            }])->where('id',$_GET['account']['modal'])->first();
            $saldo = ($activa ? $activa->balance_plain() : 0) - (($hutang?$hutang->balance_plain():0) + ($modal?$modal->balance_plain():0));

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
        $accounts = [];
        $neraca = [];
        if(isset($_GET['account']))
        {
            // activa = hutang + modal
            $accounts = Account::where('pos','Nrc')
            ->orderBy('account_code')->get();
            

            $activa = Account::with(['transactions'=>function($q) {
                $q->whereBetween('transactions.date',[$_GET['from'],$_GET['to']]);
            }])->where('id',$_GET['account']['activa'])->first();
            $hutang = Account::with(['transactions'=>function($q) {
                $q->whereBetween('transactions.date',[$_GET['from'],$_GET['to']]);
            }])->where('id',$_GET['account']['hutang'])->first();
            $modal = Account::with(['transactions'=>function($q) {
                $q->whereBetween('transactions.date',[$_GET['from'],$_GET['to']]);
            }])->where('id',$_GET['account']['modal'])->first();
            $saldo = ($activa ? $activa->balance_from_child() : 0) - (($hutang?$hutang->balance_from_child():0) + ($modal?$modal->balance_from_child():0));

            $neraca = [
                'aktiva' => $activa?$activa->balance_format():0,
                'hutang' => $hutang?$hutang->balance_format():0,
                'modal' => $modal?$modal->balance_format():0,
                'saldo' => number_format($saldo)
            ];
        }

        return view('account.cetak-neraca', compact('accounts','neraca'));
    }

    public function labaRugi()
    {
        $accounts = [];
        if(isset($_GET['from']) && isset($_GET['to']))
            $accounts = Account::with(['transactions'=>function($q) {
                $q->whereBetween('transactions.date',[$_GET['from'],$_GET['to']]);
            }])->where('pos','Lr')->orderBy('account_code')->get();

        return view('account.laba-rugi', compact('accounts'));
    }

    public function cetakLabaRugi()
    {
        $accounts = Account::with(['transactions'=>function($q) {
            $q->whereBetween('transactions.date',[$_GET['from'],$_GET['to']]);
        }])->where('pos','Lr')->orderBy('account_code')->get();

        return view('account.cetak-laba-rugi', compact('accounts'));
    }

    public function import(Request $request)
    {
        $file = $request->file;
        $extension = $file->extension();
        if($extension=='xlsx'){
            $inputFileType = 'Xlsx';
        }else{
            $inputFileType = 'Xls';
        }
        $reader     = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
         
        $spreadsheet = $reader->load($file->getPathName());
        $worksheet   = $spreadsheet->getActiveSheet();
        $highestRow  = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        $status = [
            'success' => 'Berhasil import data akun'
        ];

        DB::beginTransaction();
        try {
            for ($row = 2; $row <= $highestRow; $row++) {
                if($worksheet->getCellByColumnAndRow(1, $row)->getValue() == '') break;
                $parent_account_id = NULL;
                $parent_account = Account::where('account_code',$worksheet->getCellByColumnAndRow(2, $row)->getValue());
                if($parent_account->exists())
                    $parent_account_id = $parent_account->first()->id;

                Account::updateOrCreate([
                    'account_code' => $worksheet->getCellByColumnAndRow(1, $row)->getValue()
                ],[
                    'parent_account_id' => $parent_account_id,
                    'account_code' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                    'account_transaction_code' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                    'name' => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
                    'pos' => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                    'normal_balance' => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                    'balance' => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            $status = [
                'fail' => 'Gagal import data akun'
            ];
            DB::rollback();
        }

        return redirect()->route('accounts.index')->with($status);
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
