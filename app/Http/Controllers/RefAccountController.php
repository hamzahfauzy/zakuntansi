<?php

namespace App\Http\Controllers;

use App\Models\RefAccount;
use Illuminate\Http\Request;

/**
 * Class RefAccountController
 * @package App\Http\Controllers
 */
class RefAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parent_id = isset($_GET['parent_id']) ? $_GET['parent_id'] : 0;
        $refAccounts = RefAccount::where('parent_id',NULL)->orderby('account_code')->paginate();
        $parent = [];
        if($parent_id)
        {
            $refAccounts = RefAccount::where('parent_id',$parent_id)->orderby('account_code')->paginate();
            $parent = RefAccount::find($parent_id);
        }

        return view('ref-account.index', compact('refAccounts','parent_id','parent'))
            ->with('i', (request()->input('page', 1) - 1) * $refAccounts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $refAccount = new RefAccount();
        $parent_id = $_GET['parent_id'];
        $refAccount->parent_id = $parent_id;
        return view('ref-account.create', compact('refAccount'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(RefAccount::$rules);

        
        $data = $request->all();
        $data['parent_id'] = $data['parent_id'] == 0 ? NULL : $data['parent_id'];

        $refAccount = RefAccount::create($data);

        return redirect()->route('ref-accounts.index',['parent_id'=>$data['parent_id']])
            ->with('success', 'Akun berhasil di tambah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $refAccount = RefAccount::find($id);

        return view('ref-account.show', compact('refAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $refAccount = RefAccount::find($id);

        return view('ref-account.edit', compact('refAccount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  RefAccount $refAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefAccount $refAccount)
    {
        $rules = RefAccount::$rules;
        $rules['account_code'] = $rules['account_code'] . ',id,' . $refAccount->id;
        request()->validate($rules);

        $data = $request->all();
        $data['parent_id'] = $data['parent_id'] == 0 ? NULL : $data['parent_id'];

        $refAccount->update($data);

        return redirect()->route('ref-accounts.index',['parent_id'=>$data['parent_id']])
            ->with('success', 'Akun berhasil di edit');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $refAccount = RefAccount::find($id)->delete();

        return redirect()->route('ref-accounts.index')
            ->with('success', 'Akun berhasil di hapus');
    }
}
