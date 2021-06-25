@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <center>
                        Selamat Datang di <b>{{auth()->user()->installation->company_name}}</b>
                    </center>                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
