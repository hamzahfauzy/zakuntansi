@extends('layouts.app')

@section('template_title')
    Category Type Account
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Category Type Account') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('category-type-accounts.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>Akun</th>
										<th>Status</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categoryTypeAccounts as $categoryTypeAccount)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $categoryTypeAccount->account->name }}</td>
											<td>{{ $categoryTypeAccount->status }}</td>

                                            <td>
                                                <form action="{{ route('category-type-accounts.destroy',$categoryTypeAccount->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-success" href="{{ route('category-type-accounts.edit',$categoryTypeAccount->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $categoryTypeAccounts->links() !!}
            </div>
        </div>
    </div>
@endsection
