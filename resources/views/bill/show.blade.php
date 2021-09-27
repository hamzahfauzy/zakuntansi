@extends('layouts.app')

@section('template_title')
    {{ $bill->name ?? 'Show Bill' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Bill</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('bills.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Student Id:</strong>
                            {{ $bill->student_id }}
                        </div>
                        <div class="form-group">
                            <strong>Merchant Id:</strong>
                            {{ $bill->merchant_id }}
                        </div>
                        <div class="form-group">
                            <strong>Year:</strong>
                            {{ $bill->year }}
                        </div>
                        <div class="form-group">
                            <strong>Total:</strong>
                            {{ $bill->total }}
                        </div>
                        <div class="form-group">
                            <strong>Due Date:</strong>
                            {{ $bill->due_date }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $bill->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
