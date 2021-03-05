@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Master Akun')

@section('template_title')
    Master Akun
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Master Akun') }} {!!$parent?'- <a href="'.route('ref-accounts.index',['parent_id'=>$parent->parent_id]).'">'.$parent->name.' ('.$parent->account_code.')</a>':''!!}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('ref-accounts.create',['parent_id'=>$parent_id]) }}" class="btn btn-primary btn-sm">
                                  {{ __('Tambah Akun') }}
                                </a>
                                <a href="{{ route('ref-accounts.import') }}" class="btn btn-success btn-sm">
                                    {{ __('Import Akun') }}
                                </a>
                                <a href="{{ route('ref-accounts.download') }}" class="btn btn-success btn-sm">
                                    {{ __('Download Format Import') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        {{-- <th>Id</th> --}}
                                        
										{{-- <th>Parent Id</th> --}}
										<th>Akun</th>
										<th>Pos</th>
										<th>Saldo Normal</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($refAccounts->total()==0)
                                    <tr>
                                        <td colspan="6">
                                            <center>
                                                Tidak ada data!
                                            </center>
                                        </td>
                                    </tr>
                                    @endif
                                    @foreach ($refAccounts as $refAccount)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											{{-- <td>{{ $refAccount->id }}</td>
											<td>{{ $refAccount->parent_id }}</td> --}}
											<td><a href="{{route('ref-accounts.index',['parent_id'=>$refAccount->id])}}">{{ $refAccount->account_code }} - {{ $refAccount->name }}</a></td>
											<td>{{ $refAccount->pos }}</td>
											<td>{{ $refAccount->normal_balance }}</td>

                                            <td>
                                                <form action="{{ route('ref-accounts.destroy',$refAccount->id) }}" method="POST">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('ref-accounts.show',$refAccount->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('ref-accounts.edit',$refAccount->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $refAccounts->links() !!}
            </div>
        </div>
    </div>
@endsection
