@extends('layouts.app')

@section('template_title')
    {{ $book->name ?? 'Tampil Buku' }}
@endsection

@section('content')
    <section class="content container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Tampil Buku</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('books.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $book->name }}
                        </div>
                        <div class="form-group">
                            <strong>Date From:</strong>
                            {{ $book->date_from }}
                        </div>
                        <div class="form-group">
                            <strong>Date To:</strong>
                            {{ $book->date_to }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $book->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
