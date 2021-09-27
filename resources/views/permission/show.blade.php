@extends('layouts.app')

@section('template_title')
    {{ $permission->name ?? 'Show Permission' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Permission</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('permissions.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Role Id:</strong>
                            {{ $permission->role_id }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $permission->name }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
