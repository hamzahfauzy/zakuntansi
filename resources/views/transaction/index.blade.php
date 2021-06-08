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
                                {{ __('Transaction') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
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
                                        <th>#</th>
                                        
										<!-- <th>Tanggal</th> -->
										<th>Akun / Deskripsi</th>
										<!-- <th>Ref.</th> -->
										<!-- <th>Deskripsi</th> -->
										<th>Debit</th>
										<th>Kredit</th>
										<th>Balance</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transactions as $transaction)
                                        <tr>
                                            <td><button class="btn btn-sm btn-secondary" data-toggle="collapse" data-target="#child-data-{{++$i}}">o</button></td>
                                            
											<!-- <td>{{ $transaction->date->format('d-m-Y') }}</td> -->
											<td>{{ $transaction->account->refAccount->account_code }} - {{ $transaction->account->refAccount->name }}</td>
											<!-- <td>{{ $transaction->reference }}</td> -->
											<td>{{ $transaction->account->t_debt_format }}</td>
											<td>{{ $transaction->account->t_credit_format }}</td>
											<td>{{ $transaction->account->t_balance_format }}</td>

                                            <td>
                                                <form action="{{ route('transactions.delete',$transaction->account_id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="btn btn-sm btn-success" href="{{ route('transactions.edit',$transaction->account_id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @foreach($transaction->account->transactions as $t)
                                        <tr id="child-data-{{$i}}" class="">
                                            <td><small>{{$t->date->format('d/m/Y')}}</small></td>
											<td><span class="ml-3">{{ $t->description }}</span></td>
											<td>{{ $t->debt_format }}</td>
											<td>{{ $t->credit_format }}</td>
											<td>{{ $t->balance_format }}</td>

                                            <td>
                                                <!-- <form action="{{ route('transactions.destroy',$t->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form> -->
                                            </td>
                                        </tr>
                                        @endforeach
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
        </div>
    </div>
@endsection
