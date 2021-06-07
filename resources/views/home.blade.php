@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Daftar Buku') }}
                    <div class="float-right">
                        <a href="{{route('books.create')}}" class="btn btn-primary">+ Tambah Buku</a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif

                    @if(empty($books) || count($books) == 0)
                    <center>
                        Tidak ada buku. silahkan tambah buku dengan klik tombol <a href="{{route('books.create')}}">Tambah Buku</a>
                    </center>
                    @endif

                    

                    <div class="row image-grid">
                        @foreach($books as $book)
                        <div class="col-sm-4 col-md-4 mb-3">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <a href="{{ route('books.show',$book->id) }}">
                                        <img alt="" class="img-thumbnail" src="{{asset('images/books.jpg')}}">
                                    </a>
                                </div>
                                <div class="panel-footer">
                                    <center>
                                    <span class="badge badge-success">{{$book->status}}</span><br>
                                    <b>{{$book->name}}</b><br>
                                    {{$book->date_from->format('d/m/Y')}} - {{$book->date_to->format('d/m/Y')}}<br>
                                    <form action="{{ route('books.destroy',$book->id) }}" method="POST" onsubmit="if(confirm('Apakah anda yakin akan menghapus buku beserta seluruh datanya?')){return true}else{return false}">
                                        <a class="btn btn-sm btn-primary " href="{{ route('books.show',$book->id) }}"><i class="fa fa-fw fa-eye"></i> Lihat</a>
                                        <a class="btn btn-sm btn-success" href="{{ route('books.edit',$book->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                    </form>
                                    </center>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
