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
                                <a href="{{route('accounts.cetak-neraca')}}" class="btn btn-success btn-sm" target="_blank">
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
										<th>Akun</th>
                                        <th>Saldo Awal</th>
                                        <th>Saldo Akhir</th>
                                        <th>Net</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accounts as $account)
                                        <tr>
											<td>
                                                <b>{{ $account->refAccount->account_code }} - {{ $account->refAccount->name }}</b>
                                            </td>
											<td>{{ count($account->childs) ? '-' : $account->balance_format }}</td>
											<td>{{ count($account->childs) ? '-' : $account->t_balance_format }}</td>
											<td>{{ count($account->childs) ? '-' : $account->t_net_format }}</td>
                                        </tr>
                                    @foreach ($account->childs as $child_1)
                                        <tr>
											<td>
                                                {{ $child_1->refAccount->account_code }} - {{ $child_1->refAccount->name }}<br>
                                            </td>
											<td>{{ count($child_1->childs) ? '-' : $child_1->balance_format }}</td>
											<td>{{ count($child_1->childs) ? '-' : $child_1->t_balance_format }}</td>
											<td>{{ count($child_1->childs) ? '-' : $child_1->t_net_format }}</td>
                                        </tr>
                                    @foreach ($child_1->childs as $child_2)
                                        <tr>
											<td>
                                                {{ $child_2->refAccount->account_code }} - {{ $child_2->refAccount->name }}<br>
                                            </td>
											<td>{{ count($child_2->childs) ? '-' : $child_2->balance_format }}</td>
											<td>{{ count($child_2->childs) ? '-' : $child_2->t_balance_format }}</td>
											<td>{{ count($child_2->childs) ? '-' : $child_2->t_net_format }}</td>
                                        </tr>
                                    @foreach ($child_2->childs as $child_3)
                                        <tr>
											<td>
                                                {{ $child_3->refAccount->account_code }} - {{ $child_3->refAccount->name }}<br>
                                            </td>
											<td>{{ count($child_3->childs) ? '-' : $child_3->balance_format }}</td>
											<td>{{ count($child_3->childs) ? '-' : $child_3->t_balance_format }}</td>
											<td>{{ count($child_3->childs) ? '-' : $child_3->t_net_format }}</td>
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
            </div>
        </div>
    </div>
@endsection
