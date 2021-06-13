@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Jurnal')

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
                                {{ __('Jurnal') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('transactions.create')}}" class="btn btn-primary btn-sm">
                                  {{ __('Input Jurnal') }}
                                </a>
                                <a href="{{ route('transactions.cetak-jurnal') }}" target="_blank" class="btn btn-success btn-sm">
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
										<th>Akun</th>
										<th>Debit</th>
										<th>Kredit</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $last_code = "" ?>
                                    @forelse ($transactions as $transaction)
                                        @if($transaction->transaction_code != $last_code)
                                        <tr>
											<td colspan="4" class="font-weight-bold">{{ $transaction->date->format('d/m/Y') }} - {{ $transaction->description }}</td>
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
                                        <tr>
                                            <td>{{$transaction->transaction_code}}</td>
                                            
											<td>{{ $transaction->account->refAccount->account_code }} - {{ $transaction->account->refAccount->name }}</td>
											<td>{{ $transaction->debt_format }}</td>
											<td>{{ $transaction->credit_format }}</td>
                                            <td></td>
                                        </tr>
                                        @foreach($transaction->items as $item)
                                        <tr>
                                            <td>{{$transaction->transaction_code}}</td>
                                            
											<td>{{ $item->account->refAccount->account_code }} - {{ $item->account->refAccount->name }}</td>
											<td>{{ $item->debt_format }}</td>
											<td>{{ $item->credit_format }}</td>
                                            <td></td>
                                        </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="8"><i>Tidak ada Data</i></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
