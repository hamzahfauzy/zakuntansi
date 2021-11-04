@extends('layouts.app')

@section('template_title')
    Finance
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Finance') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('finances.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <h2>Jumlah Kas Anda : {{number_format($kas)}}</h2>
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
										<th>Category</th>
										<th>Staff</th>
										<th>Total</th>
                                        @if(!isset($_GET['category']))
										<th>Saldo</th>
                                        @endif

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($saldo=0)
                                    @foreach ($finances as $finance)
                                        @if(isset($_GET['category']) && !empty($_GET['category']) && ($_GET['category'] != $finance->category->status))
                                        @continue
                                        @endif
                                        <?php 
                                            $saldo = $finance->category->status == 'Pemasukan' ? $saldo + $finance->total : $saldo - $finance->total; 
                                        ?>
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $finance->category->name }} - {{ $finance->category->status }}</td>
											<td>{{ $finance->staff->name }}</td>
											<td>{{ $finance->total_formatted }}</td>
                                            @if(!isset($_GET['category']))
											<td>{{ number_format($saldo) }}</td>
                                            @endif

                                            <td>
                                                <form action="{{ route('finances.destroy',$finance->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-success" href="{{ route('finances.edit',$finance->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $finances->links() !!}
            </div>
        </div>
    </div>
@endsection
