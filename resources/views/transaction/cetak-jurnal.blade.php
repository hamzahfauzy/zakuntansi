<h2 align="center">Jurnal - {{$book->name}} <br> {{config('app.name', 'Laravel')}}</h2>
<p align="center">
    {{$book->date_from->format('d-m-Y')}} sampai dengan {{$book->date_to->format('d-m-Y')}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead class="thead">
        <tr>
            <th>#</th>
            <th>Akun / Deskripsi</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->account->refAccount->account_code }}</td>
                
                <!-- <td>{{ $transaction->date->format('d-m-Y') }}</td> -->
                <td>{{ $transaction->account->refAccount->name }}</td>
                <!-- <td>{{ $transaction->reference }}</td> -->
                <td>{{ $transaction->account->t_debt_format }}</td>
                <td>{{ $transaction->account->t_credit_format }}</td>
                <td>{{ $transaction->account->t_balance_format }}</td>
            </tr>
            @foreach($transaction->account->transactions as $key => $t)
            <tr id="child-data-{{$i}}" class="">
                <td>{{ $transaction->account->refAccount->account_code }} - {{++$key}}</td>
                <td><span class="ml-3">{{ $t->description }}</span></td>
                <td>{{ $t->debt_format }}</td>
                <td>{{ $t->credit_format }}</td>
                <td>{{ $t->balance_format }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
<script>
window.print()
</script>