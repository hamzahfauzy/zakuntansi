@extends('layouts.app')

@section('template_title')
    Teacher
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Teacher') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ asset("format/format-guru.xlsx") }}" class="btn btn-warning btn-sm" >
                                  {{ __('Format Import') }}
                                </a>
                                <a href="{{ route('teachers.import') }}" class="btn btn-success btn-sm" >
                                  {{ __('Import') }}
                                </a>
                                <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm" >
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
                                        
										<th>Nik</th>
										<th>Name</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teachers as $teacher)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $teacher->NIK }}</td>
											<td>{{ $teacher->name }}</td>

                                            <td>
                                                <form action="{{ route('teachers.destroy',$teacher->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('teachers.show',$teacher->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('teachers.edit',$teacher->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $teachers->links() !!}
            </div>
        </div>
    </div>
@endsection
