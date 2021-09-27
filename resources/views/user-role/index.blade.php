@extends('layouts.app')

@section('template_title')
    User Role
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('User Role') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('user-roles.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
										<th>User Id</th>
										<th>Role Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userRoles as $userRole)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $userRole->user_id }}</td>
											<td>{{ $userRole->role_id }}</td>

                                            <td>
                                                <form action="{{ route('user-roles.destroy',$userRole->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('user-roles.show',$userRole->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('user-roles.edit',$userRole->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $userRoles->links() !!}
            </div>
        </div>
    </div>
@endsection
