@extends('layouts.app')

@section('template_title')
    Create Teacher
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Tambah Guru / Pegawai</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('teachers.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('teacher.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
