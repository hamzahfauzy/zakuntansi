@extends('layouts.app')

@section('template_title')
    {{ $account->name ?? 'Show Account' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Account</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('accounts.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Book Id:</strong>
                            {{ $account->book_id }}
                        </div>
                        <div class="form-group">
                            <strong>Ref Account Id:</strong>
                            {{ $account->ref_account_id }}
                        </div>
                        <div class="form-group">
                            <strong>Debt:</strong>
                            {{ $account->debt }}
                        </div>
                        <div class="form-group">
                            <strong>Credit:</strong>
                            {{ $account->credit }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
