@extends('layouts.app')

@section('template_title')
    {{ $student->name ?? 'Show Student' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Student</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('students.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Nis:</strong>
                            {{ $student->NIS }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $student->name }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
