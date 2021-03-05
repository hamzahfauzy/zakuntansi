@extends('layouts.app')

@section('template_title')
    {{ $transaction->name ?? 'Show Transaction' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Transaction</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('transactions.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Account Id:</strong>
                            {{ $transaction->account_id }}
                        </div>
                        <div class="form-group">
                            <strong>Ref Account Id:</strong>
                            {{ $transaction->ref_account_id }}
                        </div>
                        <div class="form-group">
                            <strong>Date:</strong>
                            {{ $transaction->date }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $transaction->description }}
                        </div>
                        <div class="form-group">
                            <strong>Reference:</strong>
                            {{ $transaction->reference }}
                        </div>
                        <div class="form-group">
                            <strong>Debt:</strong>
                            {{ $transaction->debt }}
                        </div>
                        <div class="form-group">
                            <strong>Credit:</strong>
                            {{ $transaction->credit }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
