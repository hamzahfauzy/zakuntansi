@extends('layouts.app')

@section('template_title')
    {{ $categoryTypeAccount->name ?? 'Show Category Type Account' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Category Type Account</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('category-type-accounts.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Account Id:</strong>
                            {{ $categoryTypeAccount->account_id }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $categoryTypeAccount->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
