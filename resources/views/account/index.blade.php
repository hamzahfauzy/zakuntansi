@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Akun')

@section('template_title')
    Akun
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Akun
                            </span>

                             <div class="float-right">
                                <a href="{{ route('accounts.create') }}" class="btn btn-primary btn-sm">
                                  {{ __('Tambah Akun') }}
                                </a>
                                <a href="{{ route('accounts.import') }}" class="btn btn-success btn-sm" onclick="if(confirm('Apakah anda yakin akan mengimport seluruh data akun?')){return true}else{return false}">
                                    {{ __('Import Akun') }}
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
                        {{-- <b>Keterangan :</b><br>
                        Nrc = Neraca, 
                        Lr = Laba Rugi, 
                        Debt = Debit, 
                        Cr = Kredit --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Akun</th>
										<th width="150px">POS</th>
										<th width="150px">Saldo Normal</th>
										<th width="120px">Saldo</th>

                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($accounts->total() == 0)
                                    <tr>
                                        <td colspan="7"><center>Tidak ada data!</center></td>
                                    </tr>
                                    @endif
                                    @foreach ($accounts as $account)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>
                                                <b>{{ $account->account_code }} - {{ $account->name }}  ({{$account->account_transaction_code}})</b>
                                            </td>
											<td>{{ $account->pos }}</td>
											<td>{{ $account->normal_balance }}</td>
											<td>{{ $account->balance_format() }}</td>

                                            <td>
                                                <form action="{{ route('accounts.destroy',$account->id) }}" method="POST" onsubmit="if(confirm('Apakah anda yakin akan menghapus akun {{ $account->account_code }} ?')){return true}else{return false}">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('accounts.show',$account->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('accounts.edit',$account->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @foreach ($account->childs as $child_1)
                                        <tr>
                                            <td></td>
                                            
											<td>
                                                &nbsp;&nbsp;&nbsp;&nbsp;{{ $child_1->account_code }} - {{ $child_1->name }} ({{$child_1->account_transaction_code}})<br>
                                            </td>
											<td>{{ $child_1->pos }}</td>
											<td>{{ $child_1->normal_balance }}</td>
											<td>{{ $child_1->balance_format() }}</td>

                                            <td>
                                                <form action="{{ route('accounts.destroy',$child_1->id) }}" method="POST" onsubmit="if(confirm('Apakah anda yakin akan menghapus akun {{ $child_1->account_code }} ?')){return true}else{return false}">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('accounts.show',$child_1->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('accounts.edit',$child_1->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @foreach ($child_1->childs as $child_2)
                                        <tr>
                                            <td></td>
                                            
											<td>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ $child_2->account_code }} - {{ $child_2->name }}  ({{$child_2->account_transaction_code}})<br>
                                            </td>
											<td>{{ $child_2->pos }}</td>
											<td>{{ $child_2->normal_balance }}</td>
											<td>{{ $child_2->balance_format() }}</td>

                                            <td>
                                                <form action="{{ route('accounts.destroy',$child_2->id) }}" method="POST" onsubmit="if(confirm('Apakah anda yakin akan menghapus akun {{ $child_2->account_code }} ?')){return true}else{return false}">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('accounts.show',$child_2->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('accounts.edit',$child_2->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @foreach ($child_2->childs as $child_3)
                                        <tr>
                                            <td></td>
                                            
											<td>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ $child_3->account_code }} - {{ $child_3->name }}  ({{$child_3->account_transaction_code}})<br>
                                            </td>
											<td>{{ $child_3->pos }}</td>
											<td>{{ $child_3->normal_balance }}</td>
											<td>{{ $child_3->balance_format() }}</td>

                                            <td>
                                                <form action="{{ route('accounts.destroy',$child_3->id) }}" method="POST" onsubmit="if(confirm('Apakah anda yakin akan menghapus akun {{ $child_3->account_code }} ?')){return true}else{return false}">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('accounts.show',$child_3->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('accounts.edit',$child_3->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @foreach ($child_3->childs as $child_4)
                                        <tr>
                                            <td></td>
                                            
											<td>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ $child_4->account_code }} - {{ $child_4->name }}  ({{$child_4->account_transaction_code}})<br>
                                            </td>
											<td>{{ $child_4->pos }}</td>
											<td>{{ $child_4->normal_balance }}</td>
											<td>{{ $child_4->balance_format() }}</td>

                                            <td>
                                                <form action="{{ route('accounts.destroy',$child_4->id) }}" method="POST" onsubmit="if(confirm('Apakah anda yakin akan menghapus akun {{ $child_4->account_code }} ?')){return true}else{return false}">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('accounts.show',$child_4->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('accounts.edit',$child_4->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $accounts->links() !!}
            </div>
        </div>
    </div>
    <script>
    async function getName(code)
    {
        document.querySelector('#account_name').removeAttribute('readonly')
        document.querySelector('#account_name').value = ""
        document.querySelector('#account_pos').value = ""
        document.querySelector('#account_normal_balance').value = ""
        var request = await fetch('/api/ref-account/'+code)
        if(request.ok && code != "")
        {
            var response = await request.json()
            document.querySelector('#account_name').value = response.name
            document.querySelector('#account_pos').value = response.pos
            document.querySelector('#account_normal_balance').value = response.normal_balance

            document.querySelector('#account_name').setAttribute('readonly','')
        }
    }

    function simpanAkun()
    {
        const token = '{{ csrf_token() }}'
        var data = {
            'account_code': document.querySelector('#account_code'),
            'name': document.querySelector('#account_name'),
            'pos': document.querySelector('#account_pos'),
            'normal_balance': document.querySelector('#account_normal_balance'),
            'debt': document.querySelector('#account_debt'),
            'credit': document.querySelector('#account_credit')
        }

        fetch("{{route('accounts.insert')}}",{
            method:'POST',
            credentials: "same-origin",
            headers: {
                'Content-Type': 'application/json',
                "X-CSRF-Token": token
            },
            body: JSON.stringify(data)
        }).then(res => {
            return res.json()
        }).then(response => {
            console.log(response)
        })
    }
    </script>
@endsection
