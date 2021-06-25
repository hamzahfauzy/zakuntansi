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
                                <a href="{{ route('transactions.cetak-buku') }}" target="_blank" class="btn btn-success btn-sm">
                                    {{ __('Cetak') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th># ID</th>
                                        
										<th>Tanggal / Deskripsi</th>
										<th>Debit</th>
										<th>Kredit</th>
										<th>Net</th>
										<th>Saldo Akhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accounts as $i => $account)
                                    <?php $saldo_awal = $account->balance; ?>
                                        <tr>
                                            <td colspan="6" class="font-weight-bold">{{$account->account_code}} - {{$account->name}} (Saldo Awal : {{$account->balance_format}})</td>
                                        </tr>
                                    @foreach($account->transactions as $transaction)
                                        <tr>
                                            <td>{{$transaction->parent?$transaction->parent->transaction_code:$transaction->transaction_code}}</td>
                                            <td>{{$transaction->date->format('d/m/Y')}} - {{$transaction->description??$transaction->parent->description}}</td>
                                            <td>{{$transaction->debt_format}}</td>
                                            <td>{{$transaction->credit_format}}</td>
                                            <td></td>
                                            <td>{{number_format($saldo_awal += $transaction->balance)}}</td>
                                        </tr>
                                    @endforeach
                                        <tr class="font-weight-bold">
                                            <td></td>
                                            <td>Total</td>
                                            <td>{{$account->t_debt_format}}</td>
                                            <td>{{$account->t_credit_format}}</td>
                                            <td>{{$account->t_net_format}}</td>
                                            <td>{{$account->t_balance_format}}</td>
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
