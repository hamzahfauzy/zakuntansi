@extends('layouts.app')

@section('template_title')
    Update Transaction
@endsection

@section('content')
    <section class="content container">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Transaction</span>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                            </ul>
                        @endif
                        <form method="POST" action="{{ route('transactions.update', $transaction->account_id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('transaction.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
