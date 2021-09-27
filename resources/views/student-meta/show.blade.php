@extends('layouts.app')

@section('template_title')
    {{ $studentMeta->name ?? 'Show Student Meta' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Student Meta</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('student-metas.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Student Id:</strong>
                            {{ $studentMeta->student_id }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $studentMeta->name }}
                        </div>
                        <div class="form-group">
                            <strong>Content:</strong>
                            {{ $studentMeta->content }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
