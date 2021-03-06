@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Jurnal')

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
                                <label for="">From</label>
                                <input type="date" name="from" value="{{isset($_GET['from'])?$_GET['from']:''}}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">To</label>
                                <input type="date" name="to" value="{{isset($_GET['to'])?$_GET['to']:''}}" class="form-control">
                            </div>
                            <button class="btn btn-success">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
            @if($transactions)
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Jurnal') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('transactions.cetak-jurnal',$_GET) }}" target="_blank" class="btn btn-success btn-sm">
                                    {{ __('Cetak') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>Kode</th>
										<th>Tanggal</th>
										<th>Akun / Deskripsi</th>
										<th>Debit</th>
										<th>Kredit</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $account_id = "" ?>
                                    @forelse ($transactions as $transaction)
                                        @if($transaction->account_id != $account_id)
                                        <tr>
											<td colspan="5">{{ $transaction->account->account_code }} - {{ $transaction->account->name }}</td>
                                        </tr>
                                        @php($account_id=$transaction->account_id)
                                        @endif
                                        @if($transaction->parent)
                                        <tr>
                                            <td>{{$transaction->parent->transaction_code}}</td>
                                            
											<td>{{ $transaction->date->format('d/m/Y') }}</td>
											<td>{{ $transaction->parent->description }}</td>
											<td>{{ $transaction->debt_format }}</td>
											<td>{{ $transaction->credit_format }}</td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td>{{$transaction->transaction_code}}</td>
                                            
											<td>{{ $transaction->date->format('d/m/Y') }}</td>
											<td>{{ $transaction->description }}</td>
											<td>{{ $transaction->debt_format }}</td>
											<td>{{ $transaction->credit_format }}</td>
                                            <td>
                                                <form action="{{ route('transactions.destroy',$transaction->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-sm btn-success" href="{{ route('transactions.edit',$transaction->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endif
                                        {{--
                                        @foreach($transaction->items as $item)
                                        <tr>
                                            <td>{{$transaction->transaction_code}}</td>
                                            
											<td>{{ $item->account->account_code }} - {{ $item->account->name }}</td>
											<td>{{ $item->debt_format }}</td>
											<td>{{ $item->credit_format }}</td>
                                            <td></td>
                                        </tr>
                                        @endforeach
                                        --}}
                                    @empty
                                        <tr>
                                            <td colspan="6"><i>Tidak ada Data</i></td>
                                        </tr>
                                    @endforelse
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
