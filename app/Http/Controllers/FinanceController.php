<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;

/**
 * Class FinanceController
 * @package App\Http\Controllers
 */
class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $finances = Finance::paginate();

        return view('finance.index', compact('finances'))
            ->with('i', (request()->input('page', 1) - 1) * $finances->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $finance = new Finance();
        return view('finance.create', compact('finance'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Finance::$rules);

        $finance = Finance::create($request->all());

        return redirect()->route('finances.index')
            ->with('success', 'Finance created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $finance = Finance::find($id);

        return view('finance.show', compact('finance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $finance = Finance::find($id);

        return view('finance.edit', compact('finance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Finance $finance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Finance $finance)
    {
        request()->validate(Finance::$rules);

        $finance->update($request->all());

        return redirect()->route('finances.index')
            ->with('success', 'Finance updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $finance = Finance::find($id)->delete();

        return redirect()->route('finances.index')
            ->with('success', 'Finance deleted successfully');
    }
}
