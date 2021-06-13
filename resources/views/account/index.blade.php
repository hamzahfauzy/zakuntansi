@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Akun ('.$book->name.')')

@section('template_title')
    Akun ({{$book->name}})
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Akun ({{$book->name}})
                            </span>

                             <div class="float-right">
                                <a href="{{ route('accounts.create') }}" class="btn btn-primary btn-sm">
                                  {{ __('Tambah Akun') }}
                                </a>
                                <a href="{{ route('accounts.import') }}" class="btn btn-success btn-sm" onclick="if(confirm('Apakah anda yakin akan mengimport seluruh data akun?')){return true}else{return false}">
                                    {{ __('Import Semua Akun') }}
                                </a>
                                {{--
                                <a href="" class="btn btn-success btn-sm">
                                    {{ __('Import dari Buku') }}
                                </a>
                                --}}
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
										<th width="120px">Saldo Awal</th>

                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- <tr>
                                        <td>-</td>
                                        
                                        <td><input type="text" pattern=".{4,4}" maxlength="4" class="form-control" name="account_code" id="account_code" onblur="getName(this.value)" ></td>
                                        <td><input type="text" class="form-control" name="name" id="account_name" ></td>
                                        <td><select name="pos" class="form-control"  id="account_pos">
                                            <option value="">- Pilih -</option>
                                            <option value="Nrc">Neraca</option>
                                            <option value="Lr">Laba Rugi</option>
                                        </select></td>
                                        <td><select name="normal_balance" class="form-control"  id="account_normal_balance">
                                            <option value="">- Pilih -</option>
                                            <option value="Debt">Debit</option>
                                            <option value="Cr">Kredit</option>
                                        </select></td>
                                        <td><input type="number" class="form-control" name="debt" id="account_debt" ></td>
                                        <td><input type="number" class="form-control" name="credit" id="account_credit" ></td>
                                        <td>-</td>

                                        <td>
                                            <button class="btn btn-primary btn-sm" onclick="simpanAkun()">Simpan</button>
                                        </td>
                                    </tr> --}}
                                    @if($accounts->total() == 0)
                                    <tr>
                                        <td colspan="7"><center>Tidak ada data!</center></td>
                                    </tr>
                                    @endif
                                    @foreach ($accounts as $account)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>
                                                <b>{{ $account->refAccount->account_code }} - {{ $account->refAccount->name }}</b>
                                            </td>
											<td>{{ $account->refAccount->pos }}</td>
											<td>{{ $account->refAccount->normal_balance }}</td>
											<td>{{ count($account->childs) ? '-' : $account->balance_format }}</td>

                                            <td>
                                                @if(count($account->childs))
                                                -
                                                @else
                                                <form action="{{ route('accounts.destroy',$account->id) }}" method="POST" onsubmit="if(confirm('Apakah anda yakin akan menghapus akun {{ $account->refAccount->account_code }} ?')){return true}else{return false}">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('accounts.show',$account->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('accounts.edit',$account->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @foreach ($account->childs as $child_1)
                                        <tr>
                                            <td></td>
                                            
											<td>
                                                &nbsp;&nbsp;&nbsp;&nbsp;{{ $child_1->refAccount->account_code }} - {{ $child_1->refAccount->name }}<br>
                                            </td>
											<td>{{ $child_1->refAccount->pos }}</td>
											<td>{{ $child_1->refAccount->normal_balance }}</td>
											<td>{{ count($child_1->childs) ? '-' : $child_1->balance_format }}</td>

                                            <td>
                                                @if(count($child_1->childs))
                                                -
                                                @else
                                                <form action="{{ route('accounts.destroy',$child_1->id) }}" method="POST" onsubmit="if(confirm('Apakah anda yakin akan menghapus akun {{ $child_1->refAccount->account_code }} ?')){return true}else{return false}">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('accounts.show',$child_1->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('accounts.edit',$child_1->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @foreach ($child_1->childs as $child_2)
                                        <tr>
                                            <td></td>
                                            
											<td>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ $child_2->refAccount->account_code }} - {{ $child_2->refAccount->name }}<br>
                                            </td>
											<td>{{ $child_2->refAccount->pos }}</td>
											<td>{{ $child_2->refAccount->normal_balance }}</td>
											<td>{{ count($child_2->childs) ? '-' : $child_2->balance_format }}</td>

                                            <td>
                                                @if(count($child_2->childs))
                                                -
                                                @else
                                                <form action="{{ route('accounts.destroy',$child_2->id) }}" method="POST" onsubmit="if(confirm('Apakah anda yakin akan menghapus akun {{ $child_2->refAccount->account_code }} ?')){return true}else{return false}">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('accounts.show',$child_2->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('accounts.edit',$child_2->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @foreach ($child_2->childs as $child_3)
                                        <tr>
                                            <td></td>
                                            
											<td>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                {{ $child_3->refAccount->account_code }} - {{ $child_3->refAccount->name }}<br>
                                            </td>
											<td>{{ $child_3->refAccount->pos }}</td>
											<td>{{ $child_3->refAccount->normal_balance }}</td>
											<td>{{ count($child_3->childs) ? '-' : $child_3->balance_format }}</td>

                                            <td>
                                                @if(count($child_3->childs))
                                                -
                                                @else
                                                <form action="{{ route('accounts.destroy',$child_3->id) }}" method="POST" onsubmit="if(confirm('Apakah anda yakin akan menghapus akun {{ $child_3->refAccount->account_code }} ?')){return true}else{return false}">
                                                    {{-- <a class="btn btn-sm btn-primary " href="{{ route('accounts.show',$child_3->id) }}"><i class="fa fa-fw fa-eye"></i> Show</a> --}}
                                                    <a class="btn btn-sm btn-success" href="{{ route('accounts.edit',$child_3->id) }}"><i class="fa fa-fw fa-edit"></i> Edit</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Hapus</button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
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
