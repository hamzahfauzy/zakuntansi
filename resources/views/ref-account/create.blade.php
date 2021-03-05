@extends('layouts.app')

@section('template_title')
    Buat Akun
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Buat Akun {{$refAccount->parent_id?'- '.$refAccount->refAccount->name.' ('.$refAccount->refAccount->account_code.')':''}}</span>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('ref-accounts.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('ref-account.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
