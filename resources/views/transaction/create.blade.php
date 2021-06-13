@extends('layouts.app')

@section('template_title')
    Create Transaction
@endsection

@section('content')
    <section class="container fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Panel Jurnal</span>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                            </ul>
                        @endif
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
