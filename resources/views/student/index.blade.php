@extends('layouts.app')

@section('template_title')
    Student
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Student') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ asset("format/format-siswa.xlsx") }}" class="btn btn-warning btn-sm" >
                                  {{ __('Format Import') }}
                                </a>
                                <a href="{{ route('students.import') }}" class="btn btn-success btn-sm" >
                                  {{ __('Import') }}
                                </a>
                                <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
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
                                        
										<th>Nis</th>
										<th>Name</th>
										<th>Phone</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $student->NIS }}</td>
											<td>{{ $student->name }}</td>
											<td>{{ $student->phone }}</td>

                                            <td>
                                                <form action="{{ route('students.destroy',$student->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-success" href="{{ route('students.edit',$student->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $students->links() !!}
            </div>
        </div>
    </div>
@endsection
