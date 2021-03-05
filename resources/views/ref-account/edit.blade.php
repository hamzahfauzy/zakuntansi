@extends('layouts.app')

@section('template_title')
    Update Ref Account
@endsection

@section('content')
    <section class="content container">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Akun {{$refAccount->parent_id?'- '.$refAccount->refAccount->name.' ('.$refAccount->refAccount->account_code.')':''}}</span>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('ref-accounts.update', $refAccount->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('ref-account.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
