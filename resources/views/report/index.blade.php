@extends('layouts.app')

@section('template_title')
    Reports
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <span id="card_title">
                                {{ __('Reports') }}
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <form class="form-inline">
                                <select name="type" class="form-control mr-2">
                                    <option value="">- Pilih Tipe -</option>
                                    <option value="Tagihan" {{isset($_GET['type']) ? $_GET['type'] == 'Tagihan' ? 'selected' : '' : ''}}>Tagihan</option>
                                    <option value="Pembayaran" {{isset($_GET['type']) ? $_GET['type'] == 'Pembayaran' ? 'selected' : '' : ''}}>Pembayaran</option>
                                </select>
                                <select name="merchant" class="form-control mr-2">
                                    <option value="">- Pilih Merchant -</option>
                                    @foreach($merchants as $merchant)
                                    <option value="{{$merchant->id}}" {{isset($_GET['merchant']) ? $_GET['merchant'] == $merchant->id ? 'selected' : '' : ''}}>{{$merchant->name}}</option>
                                    @endforeach
                                </select>
                                <select name="year" class="form-control mr-2">
                                    <option value="">- Pilih Tahun -</option>
                                    @for($i=2000;$i<=2027;$i++)
                                        <option value="{{$i}}" {{isset($_GET['year']) ? $_GET['year'] == $i ? 'selected' : '' : ''}}>{{$i}}</option>
                                    @endfor
                                </select>
                                <input type="text" name="name" class="form-control mr-2" value="{{isset($_GET['name']) ? $_GET['name'] : ''}}" placeholder="Nama">
                                <input type="text" name="NIS" class="form-control mr-2" value="{{isset($_GET['NIS']) ? $_GET['NIS'] : ''}}" placeholder="NIS">
                                
                                <button class="btn btn-success mr-2">Filter</button>
                                <button type="button" id="btn-print" class="btn btn-primary">Cetak</button>
                            </form>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    @if(isset($_GET['type']))

                        @if($_GET['type'] == 'Pembayaran')
                            <div class="card-body">
                                <h3 class="text-center mb-3 d-none" id="title-report">Laporan Pembayaran</h2>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table-report">
                                        <thead class="thead">
                                            <tr>
                                                <th>No</th>
                                                
                                                <th>Tagihan</th>
                                                <th>NIS</th>
                                                <th>Nama</th>
                                                <th>Staff</th>
                                                <th>Total</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($total=0)
                                            @foreach ($model as $key => $payment)
                                                @php($total+=$payment->total)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    
                                                    <td>{{ $payment->bill->merchant->name.' '.$payment->bill->year }}</td>
                                                    <td>{{ $payment->user->student->NIS }}</td>
                                                    <td>{{ $payment->user->name.' - '.$payment->user->email }}</td>
                                                    <td>{{ $payment->staff->name }}</td>
                                                    <td>{{ $payment->total_formatted }}</td>
                                                    <td>{{ $payment->created_at }}</td>
                                                </tr>

                                            @endforeach
                                            <tr>
                                                <td></td>
                                                
                                                <td colspan="3">Total</td>
                                                <td>{{ number_format($total) }}</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @elseif($_GET['type'] == 'Tagihan')
                        <div class="card-body">
                            <h3 class="text-center mb-3 d-none" id="title-report">Laporan Tagihan</h2>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="table-report">
                                    <thead class="thead">
                                        <tr>
                                            <th>No</th>
                                            
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Merchant</th>
                                            <th>Tahun</th>
                                            <th>Total</th>
                                            <th>Bayar</th>
                                            <th>Sisa</th>
                                            <th>Jatuh Tempo</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($total=0)
                                        @php($sisa=0)
                                        @php($bayar=0)
                                        @foreach ($model as $key => $bill)
                                            @if($bill->sisa == 0)
                                                @continue
                                            @endif
                                            @php($total+=$bill->total)
                                            @php($sisa+=$bill->sisa)
                                            @php($bayar+=$bill->sum_payment)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                
                                                <td>{{ $bill->user->student->NIS }}</td>
                                                <td>{{ $bill->user->name }}</td>
                                                <td>{{ $bill->merchant->name }}</td>
                                                <td>{{ $bill->year }}</td>
                                                <td>{{ $bill->total_formatted }}</td>
                                                <td>{{ $bill->sum_payment_formatted }}</td>
                                                <td>{{ $bill->sisa_formatted }}</td>
                                                <td>{{ $bill->due_date }}</td>
                                                <td>{!! $bill->status_label !!}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            
                                            <td colspan="3">Total</td>
                                            <td>{{ number_format($total) }}</td>
                                            <td>{{ number_format($bayar) }}</td>
                                            <td>{{ number_format($sisa) }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>

    (function() {

        var beforePrint = function() {
            $(".card-header").addClass('d-none')
            $("#title-report").removeClass('d-none')

            // console.log('Functionality to run before printing');
        };

        var afterPrint = function() {
            $(".card-header").removeClass('d-none')
            $("#title-report").addClass('d-none')

            // console.log('Functionality to run after printing');
        };

        if (window.matchMedia) {
            var mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql) {
                if (mql.matches) {
                    beforePrint();
                } else {
                    afterPrint();
                }
            });
        }

        window.onbeforeprint = beforePrint;
        window.onafterprint = afterPrint;

    }());

    $("#btn-print").click(function(){
        window.print()
    });

</script>
@endsection