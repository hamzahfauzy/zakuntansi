@extends('layouts.app')

@section('template_title')
    Create Transaction
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Create Transaction</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('transactions.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('transaction.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
