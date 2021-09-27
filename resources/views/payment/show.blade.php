@extends('layouts.app')

@section('template_title')
    {{ $payment->name ?? 'Show Payment' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Payment</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('payments.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Transaction Id:</strong>
                            {{ $payment->transaction_id }}
                        </div>
                        <div class="form-group">
                            <strong>Bill Id:</strong>
                            {{ $payment->bill_id }}
                        </div>
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $payment->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>Staff Id:</strong>
                            {{ $payment->staff_id }}
                        </div>
                        <div class="form-group">
                            <strong>Total:</strong>
                            {{ $payment->total }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
