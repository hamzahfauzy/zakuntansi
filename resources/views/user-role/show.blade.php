@extends('layouts.app')

@section('template_title')
    {{ $userRole->name ?? 'Show User Role' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show User Role</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('user-roles.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $userRole->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>Role Id:</strong>
                            {{ $userRole->role_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
