@extends('layouts.app')

@section('template_title')
    Create Merchant
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create Merchant</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('merchants.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('merchant.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
