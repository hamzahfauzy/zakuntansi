@extends('layouts.app')

@section('template_title')
    {{ $teacherMeta->name ?? 'Show Teacher Meta' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Teacher Meta</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('teacher-metas.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Teacher Id:</strong>
                            {{ $teacherMeta->teacher_id }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $teacherMeta->name }}
                        </div>
                        <div class="form-group">
                            <strong>Content:</strong>
                            {{ $teacherMeta->content }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
