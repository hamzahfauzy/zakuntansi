@extends('layouts.app')

@section('title',config('app.name', 'Laravel').' - Neraca')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Neraca
                            </span>

                            @if($accounts)
                             <div class="float-right">
                                <a href="{{route('accounts.cetak-neraca',$_GET)}}" class="btn btn-success btn-sm" target="_blank">
                                    {{ __('Cetak') }}
                                </a>
                              </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="">
                            <div class="form-group">
                                <label for="">Aktiva</label>
                                {{ Form::select('account[activa]', $header_account, isset($_GET['account']) ? $_GET['account']['activa'] : '', ['class' => 'form-control select2', 'placeholder' => '- Pilih -']) }}
                            </div>

                            <div class="form-group">
                                <label for="">Hutang</label>
                                {{ Form::select('account[hutang]', $header_account, isset($_GET['account']) ? $_GET['account']['hutang'] : '', ['class' => 'form-control select2', 'placeholder' => '- Pilih -']) }}
                            </div>

                            <div class="form-group">
                                <label for="">Modal</label>
                                {{ Form::select('account[modal]', $header_account, isset($_GET['account']) ? $_GET['account']['modal'] : '', ['class' => 'form-control select2', 'placeholder' => '- Pilih -']) }}
                            </div>

                            <div class="form-group">
                                <label for="">From</label>
                                <input type="date" name="from" value="{{isset($_GET['from'])?$_GET['from']:''}}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="">To</label>
                                <input type="date" name="to" value="{{isset($_GET['to'])?$_GET['to']:''}}" class="form-control" required>
                            </div>

                            <button class="btn btn-success">Submit</button>
                            <p></p>
                        </form>
                        @if($accounts)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
										<th>Akun</th>
                                        <th>Saldo Akhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accounts as $account)
                                    <?php 
                                    $total_saldo = 0;
                                    if(count($account->childs))
                                    ?>
                                        <tr>
											<td>
                                                <b>{{ $account->account_code }} - {{ $account->name }}</b>
                                            </td>
											<td>{{ $account->balance_format() }}</td>
                                        </tr>
                                    @foreach ($account->childs as $child_1)
                                    <?php 
                                    if(count($child_1->childs))
                                    ?>
                                        <tr>
											<td>
                                                {{ $child_1->account_code }} - {{ $child_1->name }}<br>
                                            </td>
											<td>{{ count($child_1->childs) ? '-' : $child_1->t_balance_format }}</td>
                                        </tr>
                                    @foreach ($child_1->childs as $child_2)
                                    <?php 
                                    if(count($child_2->childs))
                                    ?>
                                        <tr>
											<td>
                                                {{ $child_2->account_code }} - {{ $child_2->name }}<br>
                                            </td>
											<td>{{ count($child_2->childs) ? '-' : $child_2->t_balance_format }}</td>
                                        </tr>
                                    @foreach ($child_2->childs as $child_3)
                                    <?php 
                                    if(count($child_3->childs))
                                    ?>
                                        <tr>
											<td>
                                                {{ $child_3->account_code }} - {{ $child_3->name }}<br>
                                            </td>
											<td>{{ count($child_3->childs) ? '-' : $child_3->t_balance_format }}</td>
                                        </tr>
                                    @endforeach
                                    @endforeach
                                    @endforeach
                                        <tr>
											<td colspan="2">
                                            <center>
                                                ---------
                                            </center>
                                            </td>
                                            {{-- <td>{{$account->balance_format()}}</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        Total Activa = {{$neraca['aktiva']}}<br>
                        Total Hutang = {{$neraca['hutang']}}<br>
                        Total Modal = {{$neraca['modal']}}<br>
                        {{$neraca['saldo'] == 0 ? 'Balance' : 'Tidak Balance'}}<br>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
