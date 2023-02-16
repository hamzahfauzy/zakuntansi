@extends('layouts.app')

@section('template_title')
    Rombongan Belajar
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Study Group') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('study-groups.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
										<th>Level</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($studyGroups as $studyGroup)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $studyGroup->name }}</td>
											<td>{{ $studyGroup->level }}</td>

                                            <td>
                                                <form action="{{ route('study-groups.destroy',$studyGroup->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('study-groups.show',$studyGroup->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('study-groups.edit',$studyGroup->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
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
                {!! $studyGroups->links() !!}
            </div>
        </div>
    </div>
@endsection
