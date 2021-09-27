@extends('layouts.app')

@section('template_title')
    Bill
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Bill') }}
                            </span>

                            <div>
                                <a href="{{ route('bills.create') }}" class="btn btn-primary btn-sm">
                                    {{ __('Create New') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Nama</th>
										<th>Merchant</th>
										<th>Tahun</th>
										<th>Total</th>
										<th>Jatuh Tempo</th>
										<th>Status</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bills as $bill)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $bill->user->name }}</td>
											<td>{{ $bill->merchant->name }}</td>
											<td>{{ $bill->year }}</td>
											<td>{{ $bill->total_formatted }}</td>
											<td>{{ $bill->due_date }}</td>
											<td>{!! $bill->status_label !!}</td>

                                            <td>
                                                <form action="{{ route('bills.destroy',$bill->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-success" href="{{ route('bills.edit',$bill->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $bills->links() !!}
            </div>
        </div>
    </div>
@endsection
