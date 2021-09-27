@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <center>
                        Selamat Datang di <b>{{auth()->user()->installation->company_name}}</b>
                    </center>                  
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->hasRoles(['Siswa','Guru / Pegawai']))
<div class="container mt-3">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    Tagihan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>
                                    
                                    <th>Tanggal</th>
                                    <th>Tagihan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (auth()->user()->bills as $i => $bill)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        
                                        <td>{{ $bill->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $bill->merchant->name }} - {{ $bill->year }}</td>
                                        <td>{{ $bill->total_formatted }}</td>
                                        <td>{!! $bill->status_label !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    Pembayaran
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>
                                    
                                    <th>Tanggal</th>
                                    <th>Tagihan</th>
                                    <th>Staff</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (auth()->user()->payments as $i => $payment)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        
                                        <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $payment->bill->merchant->name.' '.$payment->bill->year }}</td>
                                        <td>{{ $payment->staff->name }}</td>
                                        <td>{{ $payment->total_formatted }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
