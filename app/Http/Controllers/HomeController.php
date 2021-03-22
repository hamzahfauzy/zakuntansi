<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Installation;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->installation = Installation::first();
        $this->book = new Book;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        session()->forget('book');
        $installation = $this->installation;
        $books = $this->book->get();
        return view('home',compact('installation','books'));
    }

    public function installation(Request $request)
    {
        if($request->method() == 'POST')
        {
            $request->validate([
                'company_name' => 'required',
                'phone_number' => 'required',
                'address' => 'required',
                'company_email' => 'required',
                'postal_code' => 'required',
                'email' => 'required',
                'password' => 'required',
                'logo' => 'required|file|max:500',
            ]);
            $logo = $request->file('logo')->store('logo');
            Installation::create([
                'company_name' => $request->company_name,
                'email' => $request->company_email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'logo' => $logo,
            ]);

            User::create([
                'name' => $request->company_name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            return redirect()->route('home');
        }
        return view('installation');
    }
}
