<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Merchant;
use Illuminate\Http\Request;

/**
 * Class MerchantController
 * @package App\Http\Controllers
 */
class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $merchants = Merchant::paginate();

        return view('merchant.index', compact('merchants'))
            ->with('i', (request()->input('page', 1) - 1) * $merchants->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get()->pluck('name','id');
        $merchant = new Merchant();
        return view('merchant.create', compact('merchant','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Merchant::$rules);

        $merchant = Merchant::create($request->all());

        return redirect()->route('merchants.index')
            ->with('success', 'Merchant created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $merchant = Merchant::find($id);

        return view('merchant.show', compact('merchant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::get()->pluck('name','id');
        $merchant = Merchant::find($id);

        return view('merchant.edit', compact('merchant','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Merchant $merchant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Merchant $merchant)
    {
        request()->validate(Merchant::$rules);

        $merchant->update($request->all());

        return redirect()->route('merchants.index')
            ->with('success', 'Merchant updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $merchant = Merchant::find($id)->delete();

        return redirect()->route('merchants.index')
            ->with('success', 'Merchant deleted successfully');
    }
}
