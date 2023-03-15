<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\StudyGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class StudentController
 * @package App\Http\Controllers
 */
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::paginate();

        return view('student.index', compact('students'))
            ->with('i', (request()->input('page', 1) - 1) * $students->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $student = new Student();
        $studyGroups = StudyGroup::get()->pluck('name','id');
        return view('student.create', compact('student','studyGroups'));
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
                    $phone = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $account_number = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $account_holder = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    
                    if ($name == '' || $nik == '' || $no == '') break;

                    $user = User::where('email',$nik)->exists();
                    if($user)
                    {
                        $user = User::where('email',$nik)->first();
                        $user->student->update([
                            'name' => $name,
                            'NIS' => $nik,
                            'group_id' => $request->group_id,
                            'phone' => $phone,
                            'account_number' => $account_number,
                            'account_holder' => $account_holder,
                        ]);
                        // edit data
                        continue;
                    }

                    $user = User::create([
                        'name' => $name,
                        'email' => $nik,
                        'password' => $nik
                    ]);

                    $role = Role::where('name','Siswa')->first();
                    $user->roles()->sync([$role->id]);

                    $arr = [
                        'user_id'=>$user->id,
                        'name' => $name,
                        'NIS' => $nik,
                        'group_id' => $request->group_id,
                        'phone' => $phone,
                        'account_number' => $account_number,
                        'account_holder' => $account_holder,
                    ];

                    Student::create($arr);
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

            return redirect()->route('students.index')->with($status);
        }

        $studyGroups = StudyGroup::get()->pluck('name','id');

        return view('student.import', compact('studyGroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Student::$rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->NIS,
            'password' => $request->NIS,
        ]);

        $role = Role::where('name','Siswa')->first();
        $user->roles()->sync([$role->id]);

        $request['user_id'] = $user->id;

        $student = Student::create($request->all());

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::find($id);

        return view('student.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Student::find($id);
        $studyGroups = StudyGroup::get()->pluck('name','id');

        return view('student.edit', compact('student','studyGroups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Student $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        request()->validate(Student::$rules);

        $student->update($request->all());

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $student = Student::find($id)->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully');
    }
}
