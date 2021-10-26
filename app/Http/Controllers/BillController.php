<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\Category;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    function export(){
        $bills = Bill::get();

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new Spreadsheet(); 

        $sheet = $spreadsheet->getActiveSheet();

        $column_header=["No","Email","Nama","Merchant","Tahun","Total","Jatuh Tempo","Status"];
        foreach($column_header as $key => $x_value) {
            $sheet->setCellValueByColumnAndRow($key+1,1,$x_value);   
        }
        
        //set value row

        foreach($bills as $key => $value){

            $nis = $value->user->email;
            $name = $value->user->name;
            $merchant = $value->merchant->name;
            $year = $value->year;
            $total = $value->total;
            $due_date = $value->due_date;
            $status = $value->status;

            $new = [$key+1,$nis,$name,$merchant,$year,$total,$due_date,$status];

            foreach($new as $x => $x_value) {
                $sheet->setCellValueByColumnAndRow($x+1,$key+2,$x_value);
            }
        }

        $date = date('Y-m-d-H-i-s');
        $filename = "Export_Tagihan_".$date.".xlsx";

        $writer = new Xlsx($spreadsheet); 
        $writer->save("files/".$filename);
        
        $content = file_get_contents("files/".$filename);
        header("Content-Disposition: attachment; filename=".$filename);

        return $content;
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

            DB::beginTransaction();
            try {
                
                for ($row = 2; $row <= $highestRow; $row++) {
                    $no = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $nis = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $name = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $merchant_s = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $year = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $total = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $due_date = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $status = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    
                    if ($name == '' || $nis == '' || $no == '' || $merchant_s == '' || $year == '' || $total == '' || $due_date == '' || $status == '') continue;

                    $user = User::where('email',$nis)->first();

                    if($user){
                        
                        $merchant = Merchant::where('name',$merchant_s)->first();

                        if(!$merchant){
                            $category = Category::where('name',"Siswa")->first();

                            $merchant = Merchant::create([
                                'category_id'=>$category->id,
                                'name'=>$merchant_S
                            ]);
                        }

                        $bills = $user->bills()->create([
                            'merchant_id'=>$merchant->id,
                            'year'=>$year,
                            'total'=>$total,
                            'due_date'=>$due_date,
                            'status'=>strtoupper($status),
                        ]);

                    }else{
    
                        $user = User::create([
                            'name' => $name,
                            'email' => $nis,
                            'password' => $nis,
                        ]);
    
                        $role = Role::where('name','Siswa')->first();
                        $user->roles()->sync([$role->id]);
    
                        $arr = [
                            'user_id'=>$user->id,
                            'name' => $name,
                            'NIS' => $nis,
                        ];
    
                        Student::create($arr);

                        $merchant = Merchant::where('name',$merchant_s)->first();

                        if(!$merchant){
                            $category = Category::where('name',"Siswa")->first();

                            $merchant = Merchant::create([
                                'category_id'=>$category->id,
                                'name'=>$merchant_s
                            ]);
                        }

                        $bills = $user->bills()->create([
                            'merchant_id'=>$merchant->id,
                            'year'=>$year,
                            'total'=>$total,
                            'due_date'=>$due_date,
                            'status'=>strtoupper($status),
                        ]);

                    }
                }

                $status = [
                    'success' => 'Sukses import data'
                ];

                DB::commit();
            } catch (\Throwable $th) {
                throw $th;
                $status = [
                    'fail' => 'Gagal import data'
                ];
                DB::rollback();
            }

            return redirect()->route('bills.index')->with($status);
        }

        return view('bill.import');
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