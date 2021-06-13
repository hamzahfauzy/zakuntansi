<?php $book = session('book') ?>
<h2 align="center">Buku Besar - {{$book->name}} <br> {{config('app.name', 'Laravel')}}</h2>
<p align="center">
    {{$book->date_from->format('d-m-Y')}} sampai dengan {{$book->date_to->format('d-m-Y')}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead class="thead">
        <tr>
            <th># ID</th>
            
            <th>Tanggal / Deskripsi</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Net</th>
            <th>Saldo Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accounts as $i => $account)
        <?php $saldo_awal = $account->balance; ?>
            <tr>
                <td colspan="6" class="font-weight-bold">{{$account->refAccount->account_code}} - {{$account->refAccount->name}} (Saldo Awal : {{$account->balance_format}})</td>
            </tr>
        @foreach($account->transactions()->orderby('date','asc')->get() as $transaction)
            <tr>
                <td>{{$account->id}}</td>
                <td>{{$transaction->date->format('d/m/Y')}} - {{$transaction->description??$transaction->parent->description}}</td>
                <td>{{$transaction->debt_format}}</td>
                <td>{{$transaction->credit_format}}</td>
                <td></td>
                <td>{{number_format($saldo_awal += $transaction->balance)}}</td>
            </tr>
        @endforeach
            <tr class="font-weight-bold">
                <td></td>
                <td>Total</td>
                <td>{{$account->t_debt_format}}</td>
                <td>{{$account->t_credit_format}}</td>
                <td>{{$account->t_net_format}}</td>
                <td>{{$account->t_balance_format}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
window.print()
</script>