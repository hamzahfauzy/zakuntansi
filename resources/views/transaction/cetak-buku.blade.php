<?php $book = session('book') ?>
<h2 align="center">Buku Besar <br> {{auth()->user()->installation->company_name}}</h2>
<p align="center">
    {{$_GET['from']}} sampai dengan {{$_GET['to']}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead class="thead">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nomor Bukti</th>
            <th>Referensi</th>
            <th>Uraian</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Saldo Akhir</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($accounts as $i => $account)
    <?php $saldo_awal = $account->balance; ?>
    <?php $t_debt = 0; ?>
    <?php $t_credit = 0; ?>
        <tr>
            <td></td>
            <td></td>
            <td>{{$account->account_code}}</td>
            <td></td>
            <td class="font-weight-bold">{{$account->name}}<br>Saldo Awal : {{$account->balance_format}}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @foreach($account->transactions as $key => $transaction)
        @foreach($transaction->items as $t)
        <tr>
            <td>{{++$key}}</td>
            <td>{{$transaction->date->format('d/m/Y')}}</td>
            <td>{{$t->parent->transaction_code}}</td>
            <td>{{$t->account->account_code.' - '.$t->account->name}}</td>
            <td>{{$t->parent->description}}</td>
            <td>{{number_format($t->debt)}}</td>
            <td>{{number_format($t->credit)}}</td>
            <td>{{number_format($saldo_awal += $transaction->balance)}}</td>
        </tr>
        <?php $t_debt += $t->debt ?>
        <?php $t_credit += $t->credit ?>
        @endforeach
        @if($transaction->parent)
        <tr>
            <td>{{++$key}}</td>
            <td>{{$transaction->date->format('d/m/Y')}}</td>
            <td>{{$transaction->parent->transaction_code}}</td>
            <td>{{$transaction->account->account_code.' - '.$transaction->account->name}}</td>
            <td>{{$transaction->parent->description}}</td>
            <td>{{number_format($transaction->debt)}}</td>
            <td>{{number_format($transaction->credit)}}</td>
            <td>{{number_format($saldo_awal += $transaction->balance)}}</td>
        </tr>
        <?php $t_debt += $transaction->debt ?>
        <?php $t_credit += $transaction->credit ?>
        @endif
    @endforeach
        <tr class="font-weight-bold">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td>{{number_format($t_debt)}}</td>
            <td>{{number_format($t_credit)}}</td>
            <td>{{$account->t_balance_format}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
window.print()
</script>