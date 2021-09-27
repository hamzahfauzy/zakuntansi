<?php

namespace App\Http\Controllers;

use App\Models\TeacherMeta;
use Illuminate\Http\Request;

/**
 * Class TeacherMetaController
 * @package App\Http\Controllers
 */
class TeacherMetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacherMetas = TeacherMeta::paginate();

        return view('teacher-meta.index', compact('teacherMetas'))
            ->with('i', (request()->input('page', 1) - 1) * $teacherMetas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teacherMeta = new TeacherMeta();
        return view('teacher-meta.create', compact('teacherMeta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(TeacherMeta::$rules);

        $teacherMeta = TeacherMeta::create($request->all());

        return redirect()->route('teacher-metas.index')
            ->with('success', 'TeacherMeta created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacherMeta = TeacherMeta::find($id);

        return view('teacher-meta.show', compact('teacherMeta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacherMeta = TeacherMeta::find($id);

        return view('teacher-meta.edit', compact('teacherMeta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  TeacherMeta $teacherMeta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeacherMeta $teacherMeta)
    {
        request()->validate(TeacherMeta::$rules);

        $teacherMeta->update($request->all());

        return redirect()->route('teacher-metas.index')
            ->with('success', 'TeacherMeta updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $teacherMeta = TeacherMeta::find($id)->delete();

        return redirect()->route('teacher-metas.index')
            ->with('success', 'TeacherMeta deleted successfully');
    }
}
