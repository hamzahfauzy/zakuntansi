<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class TeacherController
 * @package App\Http\Controllers
 */
class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::paginate();

        return view('teacher.index', compact('teachers'))
            ->with('i', (request()->input('page', 1) - 1) * $teachers->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teacher = new Teacher();
        return view('teacher.create', compact('teacher'));
    }

    public function import(Request $request)
    {
        if ($request->isMethod("POST")) {
            $file = $request->file('import');
            $extension = $file->extension();
            if ($extension == 'xlsx') {
                $inputFileType = 'Xlsx';
            } else {
                $inputFileType = 'Xls';
            }
            $reader     = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

            $spreadsheet = $reader->load($file->getPathName());
            $worksheet   = $spreadsheet->getActiveSheet();
            $highestRow  = $worksheet->getHighestRow();

            $status = [
                'success' => 'Berhasil import data akun'
            ];

            DB::beginTransaction();
            try {
                
                for ($row = 2; $row <= $highestRow; $row++) {
                    $no = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $nik = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $name = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    
                    if ($name == '' || $nik == '' || $no == '') break;

                    $user = User::create([
                        'name' => $name,
                        'email' => $nik,
                        'password' => $nik,
                    ]);

                    $role = Role::where('name','Guru / Pegawai')->first();
                    $user->roles()->sync([$role->id]);

                    $arr = [
                        'user_id'=>$user->id,
                        'name' => $name,
                        'NIK' => $nik,
                    ];

                    Teacher::create($arr);
                }

                $status = [
                    'success' => 'Sukses import data akun'
                ];

                DB::commit();
            } catch (\Throwable $th) {
                throw $th;
                $status = [
                    'fail' => 'Gagal import data akun'
                ];
                DB::rollback();
            }

            return redirect()->route('teachers.index')->with($status);
        }

        return view('teacher.import');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Teacher::$rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->NIK,
            'password' => $request->NIK,
        ]);

        $role = Role::where('name','Guru / Pegawai')->first();
        $user->roles()->sync([$role->id]);

        $request['user_id'] = $user->id;

        $teacher = Teacher::create($request->all());

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = Teacher::find($id);

        return view('teacher.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacher = Teacher::find($id);

        return view('teacher.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        request()->validate(Teacher::$rules);

        $teacher->update($request->all());

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $teacher = Teacher::find($id)->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully');
    }
}
