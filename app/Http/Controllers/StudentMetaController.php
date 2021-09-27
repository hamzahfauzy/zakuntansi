<?php

namespace App\Http\Controllers;

use App\Models\StudentMeta;
use Illuminate\Http\Request;

/**
 * Class StudentMetaController
 * @package App\Http\Controllers
 */
class StudentMetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $studentMetas = StudentMeta::paginate();

        return view('student-meta.index', compact('studentMetas'))
            ->with('i', (request()->input('page', 1) - 1) * $studentMetas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $studentMeta = new StudentMeta();
        return view('student-meta.create', compact('studentMeta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(StudentMeta::$rules);

        $studentMeta = StudentMeta::create($request->all());

        return redirect()->route('student-metas.index')
            ->with('success', 'StudentMeta created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studentMeta = StudentMeta::find($id);

        return view('student-meta.show', compact('studentMeta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $studentMeta = StudentMeta::find($id);

        return view('student-meta.edit', compact('studentMeta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  StudentMeta $studentMeta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentMeta $studentMeta)
    {
        request()->validate(StudentMeta::$rules);

        $studentMeta->update($request->all());

        return redirect()->route('student-metas.index')
            ->with('success', 'StudentMeta updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $studentMeta = StudentMeta::find($id)->delete();

        return redirect()->route('student-metas.index')
            ->with('success', 'StudentMeta deleted successfully');
    }
}
