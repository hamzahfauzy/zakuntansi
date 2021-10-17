@extends('layouts.app')

@section('template_title')
    Import Student
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Import Tagihan</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('bills.import') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            <div class="box box-info padding-1">
                                <div class="box-body">

                                    <input type="file" name="import" class="form-control">

                                </div>
                                <div class="box-footer mt-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
