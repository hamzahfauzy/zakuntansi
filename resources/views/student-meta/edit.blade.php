@extends('layouts.app')

@section('template_title')
    Update Student Meta
@endsection

@section('content')
    <section class="content container">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Student Meta</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('student-metas.update', $studentMeta->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('student-meta.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
