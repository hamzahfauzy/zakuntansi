@extends('layouts.app')

@section('template_title')
    Create User Role
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create User Role</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('user-roles.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('user-role.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
