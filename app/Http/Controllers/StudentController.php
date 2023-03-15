<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\StudyGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function export()
    {
        $students = Student::get();

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new Spreadsheet(); 

        $sheet = $spreadsheet->getActiveSheet();

        $column_header=["No","Kelas","Nama Siswa","Nama Pemegang Rekening","No Rekening"];
        foreach($column_header as $key => $x_value) {
            $sheet->setCellValueByColumnAndRow($key+1,1,$x_value);   
        }
        
        //set value row

        foreach($students as $key => $student){
            $nis = $student->NIS;
            $kelas = $student->studyGroup?$student->studyGroup->name:'';
            $name = $student->name;
            $account_number = $student->account_number;
            $account_holder = $student->account_holder;

            $new = [$key+1,$kelas,$name,$account_holder,$account_number];

            foreach($new as $x => $x_value) {
                $sheet->setCellValueByColumnAndRow($x+1,$key+2,$x_value);
            }
        }

        $date = date('Y-m-d-H-i-s');
        $filename = "Export_Data_Siswa_".$date.".xlsx";

        $writer = new Xlsx($spreadsheet); 
        $writer->save("files/".$filename);
        
        $content = file_get_contents("files/".$filename);
        header("Content-Disposition: attachment; filename=".$filename);

        return $content;
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
