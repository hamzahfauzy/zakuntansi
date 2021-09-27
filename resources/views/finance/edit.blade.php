@extends('layouts.app')

@section('template_title')
    Update Finance
@endsection

@section('content')
    <section class="content container">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Finance</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('finances.update', $finance->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('finance.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
