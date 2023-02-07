@extends('layouts.app')

@section('template_title')
    Import Student
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Import Siswa</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('students.import') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            <div class="box box-info padding-1">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="">File Import</label>
                                        <input type="file" name="import" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label(__('Study Group')) }}
                                        {{ Form::select('group_id', $studyGroups, 0, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : '')]) }}
                                        {!! $errors->first('group_id', '<div class="invalid-feedback">:message</p>') !!}
                                    </div>

                                </div>
                                <div class="box-footer mt-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
