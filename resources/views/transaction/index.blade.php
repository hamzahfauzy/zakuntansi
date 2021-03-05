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
                                  {{ __('Tambah Data') }}
                                </a>
                                <a href="{{ route('transactions.create') }}" class="btn btn-success btn-sm">
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
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Tanggal</th>
										<th>Akun</th>
										<th>Ref.</th>
										<th>Deskripsi</th>
										<th>Debit</th>
										<th>Kredit</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $transaction->date->format('d-m-Y') }}</td>
											<td>{{ $transaction->account->refAccount->account_code }} - {{ $transaction->account->refAccount->name }}</td>
											<td>{{ $transaction->reference }}</td>
											<td>{{ $transaction->description }}</td>
											<td>{{ $transaction->debt_format }}</td>
											<td>{{ $transaction->credit_format }}</td>

                                            <td>
                                                <form action="{{ route('transactions.destroy',$transaction->id) }}" method="POST">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('transactions.show',$transaction->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('transactions.edit',$transaction->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $transactions->links() !!}
            </div>
        </div>
    </div>
@endsection
