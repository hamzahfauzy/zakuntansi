@extends('layouts.app')

@section('template_title')
    Finance
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Finance') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('finances.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Transaction Id</th>
										<th>Category Id</th>
										<th>User Id</th>
										<th>Staff Id</th>
										<th>Total</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($finances as $finance)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $finance->transaction_id }}</td>
											<td>{{ $finance->category_id }}</td>
											<td>{{ $finance->user_id }}</td>
											<td>{{ $finance->staff_id }}</td>
											<td>{{ $finance->total }}</td>

                                            <td>
                                                <form action="{{ route('finances.destroy',$finance->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('finances.show',$finance->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('finances.edit',$finance->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $finances->links() !!}
            </div>
        </div>
    </div>
@endsection
