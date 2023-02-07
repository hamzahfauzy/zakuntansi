<?php

namespace App\Http\Controllers;

use App\Models\StudyGroup;
use Illuminate\Http\Request;

/**
 * Class StudyGroupController
 * @package App\Http\Controllers
 */
class StudyGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studyGroups = StudyGroup::paginate();

        return view('study-group.index', compact('studyGroups'))
            ->with('i', (request()->input('page', 1) - 1) * $studyGroups->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $studyGroup = new StudyGroup();
        return view('study-group.create', compact('studyGroup'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(StudyGroup::$rules);

        $studyGroup = StudyGroup::create($request->all());

        return redirect()->route('study-groups.index')
            ->with('success', 'StudyGroup created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studyGroup = StudyGroup::find($id);

        return view('study-group.show', compact('studyGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $studyGroup = StudyGroup::find($id);

        return view('study-group.edit', compact('studyGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  StudyGroup $studyGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudyGroup $studyGroup)
    {
        request()->validate(StudyGroup::$rules);

        $studyGroup->update($request->all());

        return redirect()->route('study-groups.index')
            ->with('success', 'StudyGroup updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $studyGroup = StudyGroup::find($id)->delete();

        return redirect()->route('study-groups.index')
            ->with('success', 'StudyGroup deleted successfully');
    }
}
