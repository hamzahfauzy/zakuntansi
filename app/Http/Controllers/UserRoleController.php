<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;

/**
 * Class UserRoleController
 * @package App\Http\Controllers
 */
class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userRoles = UserRole::paginate();

        return view('user-role.index', compact('userRoles'))
            ->with('i', (request()->input('page', 1) - 1) * $userRoles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userRole = new UserRole();
        return view('user-role.create', compact('userRole'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(UserRole::$rules);

        $userRole = UserRole::create($request->all());

        return redirect()->route('user-roles.index')
            ->with('success', 'UserRole created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userRole = UserRole::find($id);

        return view('user-role.show', compact('userRole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userRole = UserRole::find($id);

        return view('user-role.edit', compact('userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  UserRole $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserRole $userRole)
    {
        request()->validate(UserRole::$rules);

        $userRole->update($request->all());

        return redirect()->route('user-roles.index')
            ->with('success', 'UserRole updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $userRole = UserRole::find($id)->delete();

        return redirect()->route('user-roles.index')
            ->with('success', 'UserRole deleted successfully');
    }
}
