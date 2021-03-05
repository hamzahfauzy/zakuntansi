@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Neraca ('.$book->name.')')

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
                                Neraca ({{$book->name}})
                            </span>

                             <div class="float-right">
                                <a href="" class="btn btn-success btn-sm" onclick="if(confirm('Apakah anda yakin akan mengimport seluruh data akun?')){return true}else{return false}">
                                    {{ __('Cetak') }}
                                </a>
                              </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Akun</th>
										<th width="150px">POS</th>
										<th width="150px">Saldo Normal</th>
										<th>Debit</th>
										<th>Kredit</th>
										<th width="120px">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @if(count($accounts) == 0)
                                    <tr>
                                        <td colspan="7"><center>Tidak ada data!</center></td>
                                    </tr>
                                    @endif
                                    <?php $all_db = 0; $all_cr = 0; ?>
                                    @foreach ($accounts as $i => $account)
                                        <?php
                                        $all_db += $account->t_debt;
                                        $all_cr += $account->t_credit;
                                        ?>
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $account->refAccount->account_code }} - {{ $account->refAccount->name }}</td>
											<td>{{ $account->refAccount->pos }}</td>
											<td>{{ $account->refAccount->normal_balance }}</td>
											<td>{{ $account->t_debt_format }}</td>
											<td>{{ $account->t_credit_format }}</td>
											<td>{{ $account->t_balance_format }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" style="text-align: right;"><b>Balance</b></td>
                                        <td>{{number_format($all_db)}}</td>
                                        <td>{{number_format($all_cr)}}</td>
                                        <td>{{number_format($all_db-$all_cr)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
