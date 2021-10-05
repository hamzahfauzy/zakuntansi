@extends('layouts.app')

@section('template_title')
    Payment
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pilih Nama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('payments.create')}}">
                <div class="modal-body">
                    <div class="form-group">
                        {{ Form::select('user_id', $users, 0, ['class' => 'form-control select2','placeholder'=>'Pilih']) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Buat Pembayaran</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Payment') }}
                            </span>

                             <div class="float-right">
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right"  data-placement="left" data-toggle="modal" data-target="#exampleModal">
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
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Tagihan</th>
										<th>Nama</th>
										<th>Staff</th>
										<th>Total</th>
										<th>Tanggal</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $payment->bill->merchant->name.' '.$payment->bill->year }}</td>
											<td>{{ $payment->user->name.' - '.$payment->user->email }}</td>
											<td>{{ $payment->staff->name }}</td>
											<td>{{ $payment->total }}</td>
											<td>{{ $payment->created_at }}</td>

                                            <td>
                                                @if(auth()->user()->hasRole('Kasir'))
                                                <a href="{{route('payments.cetak',['user'=>$payment->user->id,'date'=>$payment->created_at->format('Y-m-d')])}}" class="btn btn-success btn-sm btn-block mb-2"><i class="fa fa-fw fa-print"></i> Cetak</a>
                                                @endif
                                                
                                                @if(auth()->user()->hasRole('Bendahara'))
                                                <form action="{{ route('payments.destroy',$payment->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-block btn-sm"><i class="fa fa-fw fa-trash"></i> Delete</button>
                                                </form>
                                                @else
                                                -
                                                @endif
                                            </td>
                                        </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $payments->links() !!}
            </div>
        </div>
    </div>
@endsection
