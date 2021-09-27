@extends('layouts.app')

@section('template_title')
    Update Bill
@endsection

@section('content')
    <section class="container">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Tagihan</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('bills.update', $bill->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('bill.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
