<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\User;
use App\Models\Student;
use App\Models\Merchant;
use Illuminate\Http\Request;

/**
 * Class BillController
 * @package App\Http\Controllers
 */
class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = Bill::paginate();

        return view('bill.index', compact('bills'))
            ->with('i', (request()->input('page', 1) - 1) * $bills->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::get()->pluck('name','id');
        $merchants = Merchant::get()->pluck('name','id');
        $bill = new Bill();
        return view('bill.create', compact('bill','users','merchants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['status'] = 'BELUM DIBAYAR';
        request()->validate(Bill::$rules);

        // $bill = Bill::create($request->all());

        foreach($request->user_id as $user_id)
        {
            $data = $request->all();
            $data['user_id'] = $user_id;
            Bill::create($data);
        }

        return redirect()->route('bills.index')
            ->with('success', 'Bill created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = Bill::find($id);

        return view('bill.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $merchants = Merchant::get()->pluck('name','id');
        $bill = Bill::find($id);

        return view('bill.edit', compact('bill','merchants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Bill $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
        request()->validate(Bill::$rules);

        $bill->update($request->all());

        return redirect()->route('bills.index')
            ->with('success', 'Bill updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $bill = Bill::find($id)->delete();

        return redirect()->route('bills.index')
            ->with('success', 'Bill deleted successfully');
    }
}
