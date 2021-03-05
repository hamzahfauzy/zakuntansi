@extends('layouts.app')

@section('template_title')
    {{ $refAccount->name ?? 'Show Ref Account' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Ref Account</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('ref-accounts.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Parent Id:</strong>
                            {{ $refAccount->parent_id }}
                        </div>
                        <div class="form-group">
                            <strong>Account Code:</strong>
                            {{ $refAccount->account_code }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $refAccount->name }}
                        </div>
                        <div class="form-group">
                            <strong>Pos:</strong>
                            {{ $refAccount->pos }}
                        </div>
                        <div class="form-group">
                            <strong>Normal Balance:</strong>
                            {{ $refAccount->normal_balance }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
