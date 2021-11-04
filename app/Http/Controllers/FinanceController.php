<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $categories = Category::get();
        $kas = 0;
        foreach($categories as $category)
        {
            if($category->status == 'Pemasukan')
                $kas += $category->finances()->sum('total');
            else
                $kas -= $category->finances()->sum('total');
        }

        return view('finance.index', compact('finances','kas'))
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
        $categories = Category::select(DB::Raw('CONCAT(name," - ",status) as cat_name'),'id')->get()->pluck('cat_name','id');
        return view('finance.create', compact('finance','categories'));
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
        $request['staff_id'] = auth()->user()->id;
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
        $categories = Category::select(DB::Raw('CONCAT(name," - ",status) as cat_name'),'id')->get()->pluck('cat_name','id');

        return view('finance.edit', compact('finance','categories'));
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
        $finance = Finance::find($id);
        if($finance->payment_id)
            $finance->payment->delete();
        $finance->delete();

        return redirect()->route('finances.index')
            ->with('success', 'Finance deleted successfully');
    }
}
