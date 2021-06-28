@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Buku Besar')

@section('template_title')
    Transaction
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Filter') }}
                            </span>

                        </div>
                    </div>

                    <div class="card-body">
                        <form action="">
                            <div class="form-group">
                                <label for="">Akun</label>
                                {{ Form::select('account_id', $all_accounts, isset($_GET['account_id'])?$_GET['account_id']:'', ['class' => 'form-control select2','placeholder'=>'- Pilih -']) }}
                            </div>
                            <div class="form-group">
                                <label for="">From</label>
                                <input type="date" name="from" value="{{isset($_GET['from'])?$_GET['from']:''}}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">To</label>
                                <input type="date" name="to" value="{{isset($_GET['to'])?$_GET['to']:''}}" class="form-control" required>
                            </div>
                            <button class="btn btn-success">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
            @if($accounts)
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Buku Besar') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('transactions.cetak-buku',$_GET) }}" target="_blank" class="btn btn-success btn-sm">
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
                                        <th>No</th>
										<th>Tanggal</th>
                                        <th>Nomor Bukti</th>
                                        <th>Referensi</th>
                                        <th>Uraian</th>
										<th>Debit</th>
										<th>Kredit</th>
										<th>Saldo Akhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accounts as $i => $account)
                                    <?php $saldo_awal = $account->balance; ?>
                                    <?php $t_debt = 0; ?>
                                    <?php $t_credit = 0; ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="font-weight-bold">{{$account->account_code}}</td>
                                            <td></td>
                                            <td class="font-weight-bold">{{$account->name}}<br>Saldo Awal : {{$account->balance_format}}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="font-weight-bold">{{$account->balance_format()}}</td>
                                        </tr>
                                    @foreach($account->transactions as $key => $transaction)
                                        @foreach($transaction->items as $t)
                                        <?php
                                        if($t->debt > 0)
                                            $saldo_awal -= $t->balance;
                                        // if($t->credit > 0)
                                        //     $saldo_awal += $t->balance;
                                        // else
                                        //     $saldo_awal -= $t->balance;
                                        ?>
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$transaction->date->format('d/m/Y')}}</td>
                                            <td>{{$t->parent->transaction_code}}</td>
                                            <td>{{$t->account->account_code.' - '.$t->account->name}}</td>
                                            <td>{{$t->parent->description}}</td>
                                            <td>{{number_format($t->debt)}}</td>
                                            <td>{{number_format($t->credit)}}</td>
                                            <td>{{number_format($saldo_awal)}}</td>
                                        </tr>
                                        <?php $t_debt += $t->debt ?>
                                        <?php $t_credit += $t->credit ?>
                                        @endforeach
                                        @if($transaction->parent)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$transaction->date->format('d/m/Y')}}</td>
                                            <td>{{$transaction->parent->transaction_code}}</td>
                                            <td>{{$transaction->parent->account->account_code.' - '.$transaction->parent->account->name}}</td>
                                            <td>{{$transaction->parent->description}}</td>
                                            <td>{{number_format($transaction->debt)}}</td>
                                            <td>{{number_format($transaction->credit)}}</td>
                                            <td> - {{--$transaction->balance_format--}} {{--number_format($saldo_awal += $transaction->balance)--}}</td>
                                        </tr>
                                        <?php // $t_debt += $transaction->debt ?>
                                        <?php // $t_credit += $transaction->credit ?>
                                        @endif
                                    @endforeach
                                        {{--
                                        @if(!$account->childs()->exists())
                                        <tr class="font-weight-bold">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td>{{number_format($t_debt)}}</td>
                                            <td>{{number_format($t_credit)}}</td>
                                            <td>{{$account->t_balance_format}}</td>
                                        </tr>
                                        @endif
                                        --}}
                                        <tr>
                                            <td colspan="8">
                                                <center>---------</center>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
