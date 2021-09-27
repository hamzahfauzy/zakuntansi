@extends('layouts.app')

@section('template_title')
    {{ $merchant->name ?? 'Show Merchant' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Merchant</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('merchants.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Category Id:</strong>
                            {{ $merchant->category_id }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $merchant->name }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
