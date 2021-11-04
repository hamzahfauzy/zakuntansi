<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\User;
use App\Models\Finance;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class PaymentController
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereHas('roles',function($query){
            $query->whereIn('name',['Siswa','Guru / Pegawai']);
        })
        ->select(DB::Raw('CONCAT(name," - ",email) as user_name'),DB::Raw('id as _user_id'))
        ->get()->pluck('user_name','_user_id');
        $payments = Payment::paginate();

        return view('payment.index', compact('payments','users'))
            ->with('i', (request()->input('page', 1) - 1) * $payments->perPage());
    }

    function export(){
        $payments = Payment::get();

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new Spreadsheet(); 

        $sheet = $spreadsheet->getActiveSheet();

        $column_header=["No","Email","Nama","Merchant","Tahun","Total"];
        foreach($column_header as $key => $x_value) {
            $sheet->setCellValueByColumnAndRow($key+1,1,$x_value);   
        }
        
        //set value row

        foreach($payments as $key => $value){

            $nis = $value->user->email;
            $name = $value->user->name;
            $merchant = $value->bill->merchant->name;
            $year = $value->year;
            $total = $value->total;

            $new = [$key+1,$nis,$name,$merchant,$year,$total];

            foreach($new as $x => $x_value) {
                $sheet->setCellValueByColumnAndRow($x+1,$key+2,$x_value);
            }
        }

        $date = date('Y-m-d-H-i-s');
        $filename = "Export_Pembayaran_".$date.".xlsx";

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
                    
                    if ($name == '' || $nis == '' || $no == '' || $merchant_s == '' || $year == '' || $total == '') continue;

                    $user = User::where('email',$nis)->first();

                    if($user){
                        
                        $merchant = Merchant::where('name',$merchant_s)->first();
                        
                        if(!$merchant) continue;
                        
                        $bill = $user->bills()->where('merchant_id',$merchant->id)->where('year',$year)->orderBy("id","desc")->first();
                        
                        if(!$bill) continue;
                        
                        $payments = $user->payments()->create([
                            'bill_id'=>$bill->id,
                            'staff_id'=>auth()->id(),
                            'total'=>$total,
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

                        if(!$merchant) continue;

                        $bill = $user->bills()->where('year',$year)->where('merchant_id',$merchant->id)->orderBy("id","desc")->first();

                        if(!$bill) continue;

                        $payments = $user->payments()->create([
                            'bill_id'=>$bill->id,
                            'staff_id'=>auth()->id(),
                            'total'=>$total,
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

            return redirect()->route('payments.index')->with($status);
        }

        return view('payment.import');
    }

    function cetak($user,$date){
        $usr = User::find($user);
        $payments = Payment::where('user_id',$user)->where('created_at','like','%'.$date.'%')->get();

        return view("payment.cetak",compact('payments','usr','date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::findOrFail($_GET['user_id']);
        $bills = $user->bills()->whereIn('status',['BELUM DIBAYAR','BELUM LUNAS'])->get()->pluck('bill_name','id');
        $payment = new Payment();
        $payment->user_id = $_GET['user_id'];
        return view('payment.create', compact('payment','bills'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['staff_id'] = auth()->user()->id;
        
        // get payment
        
        foreach($request['payment'] as $p){

            $bill = Bill::where('user_id',$request->user_id)->where('id',$p['bill_id'])->first();
            $payment = Payment::where('user_id',$request->user_id)->where('bill_id',$p['bill_id'])->sum('total');

            $total_payment = $payment+$p['total'];
             
            if($bill->total-$total_payment == 0)
                $bill->update(['status'=>'LUNAS']);
            else
                $bill->update(['status'=>'BELUM LUNAS']);

            $payment = Payment::create([
                'staff_id'=>$request->staff_id,
                'user_id'=>$request->user_id,
                'bill_id'=>$p['bill_id'],
                'total'=>$p['total'],
            ]);

            if($payment)
            {
                $category = $bill->merchant->category_id;
                Finance::create([
                    'staff_id'=>$request->staff_id,
                    'user_id'=>$request->user_id,
                    'payment_id'=>$payment->id,
                    'category_id'=>$category,
                    'total'=>$p['total'],
                ]);
            }
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::find($id);

        return view('payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        return;
        $payment = Payment::find($id);

        return view('payment.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        return;
        request()->validate(Payment::$rules);

        $payment->update($request->all());

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasRole('Bendahara')) return redirect()->back();
        $payment = Payment::find($id)->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully');
    }
}
