@extends('layouts.app')

@section('template_title')
    {{ $finance->name ?? 'Show Finance' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Finance</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('finances.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Transaction Id:</strong>
                            {{ $finance->transaction_id }}
                        </div>
                        <div class="form-group">
                            <strong>Category Id:</strong>
                            {{ $finance->category_id }}
                        </div>
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $finance->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>Staff Id:</strong>
                            {{ $finance->staff_id }}
                        </div>
                        <div class="form-group">
                            <strong>Total:</strong>
                            {{ $finance->total }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
