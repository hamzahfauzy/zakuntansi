@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Buku Besar')

@section('template_title')
    Transaction
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Buku Besar') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('transactions.create') }}" class="btn btn-success btn-sm">
                                    {{ __('Cetak') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form name="formfilter">
                            {{ Form::select('id', $accounts, $id, ['class' => 'form-control select2','placeholder'=>'- Pilih -','onchange'=>'formfilter.submit()']) }}
                        </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Tanggal</th>
										{{-- <th>Akun</th> --}}
										<th>Ref.</th>
										<th>Deskripsi</th>
										<th>Debit</th>
										<th>Kredit</th>
										<th>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($id)
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            {{-- <td></td> --}}
                                            <td></td>
                                            <td>Saldo Awal</td>
                                            <td>{{$selected_account->debt_format}}</td>
                                            <td>{{$selected_account->credit_format}}</td>
                                            <td>{{$selected_account->balance_format}}</td>
                                        </tr>
                                    @endif
                                    @foreach ($transactions as $i => $transaction)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $transaction->date->format('d-m-Y') }}</td>
											{{-- <td>{{ $transaction->account->refAccount->account_code }} - {{ $transaction->account->refAccount->name }}</td> --}}
											<td>{{ $transaction->reference }}</td>
											<td>{{ $transaction->description }}</td>
											<td>{{ $transaction->debt_format }}</td>
											<td>{{ $transaction->credit_format }}</td>
											<td>{{ $transaction->balance_format }}</td>
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
@endsection
