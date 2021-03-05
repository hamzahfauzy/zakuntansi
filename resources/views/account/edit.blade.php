@extends('layouts.app')

@section('template_title')
    Update Account
@endsection

@section('content')
    <section class="content container">
        <div class="">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Update Akun ({{$account->refAccount->account_code}} - {{$account->refAccount->name}})</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('accounts.update', $account->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('account.form-edit')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
