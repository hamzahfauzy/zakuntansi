@extends('layouts.app')

@section('template_title')
    Create Student Meta
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create Student Meta</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('student-metas.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('student-meta.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
