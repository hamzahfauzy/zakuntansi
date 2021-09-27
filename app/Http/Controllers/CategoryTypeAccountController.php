<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryTypeAccount;

/**
 * Class CategoryTypeAccountController
 * @package App\Http\Controllers
 */
class CategoryTypeAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoryTypeAccounts = CategoryTypeAccount::paginate();

        return view('category-type-account.index', compact('categoryTypeAccounts'))
            ->with('i', (request()->input('page', 1) - 1) * $categoryTypeAccounts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::doesntHave('childs')->where('pos','Nrc')->select(DB::Raw('CONCAT(account_code," - ",name) as account_name'),'id')->orderby('account_code')->get()->pluck('account_name','id');
        $categoryTypeAccount = new CategoryTypeAccount();
        return view('category-type-account.create', compact('categoryTypeAccount','accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(CategoryTypeAccount::$rules);

        $categoryTypeAccount = CategoryTypeAccount::create($request->all());

        return redirect()->route('category-type-accounts.index')
            ->with('success', 'CategoryTypeAccount created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoryTypeAccount = CategoryTypeAccount::find($id);

        return view('category-type-account.show', compact('categoryTypeAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accounts = Account::doesntHave('childs')->where('pos','Nrc')->select(DB::Raw('CONCAT(account_code," - ",name) as account_name'),'id')->orderby('account_code')->get()->pluck('account_name','id');
        $categoryTypeAccount = CategoryTypeAccount::find($id);

        return view('category-type-account.edit', compact('categoryTypeAccount','accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  CategoryTypeAccount $categoryTypeAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryTypeAccount $categoryTypeAccount)
    {
        request()->validate(CategoryTypeAccount::$rules);

        $categoryTypeAccount->update($request->all());

        return redirect()->route('category-type-accounts.index')
            ->with('success', 'CategoryTypeAccount updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $categoryTypeAccount = CategoryTypeAccount::find($id)->delete();

        return redirect()->route('category-type-accounts.index')
            ->with('success', 'CategoryTypeAccount deleted successfully');
    }
}
