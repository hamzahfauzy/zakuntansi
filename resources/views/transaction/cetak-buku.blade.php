<?php $book = session('book') ?>
<h2 align="center">Buku Besar - {{$book->name}} <br> {{config('app.name', 'Laravel')}}</h2>
<p align="center">
    {{$book->date_from->format('d-m-Y')}} sampai dengan {{$book->date_to->format('d-m-Y')}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead class="thead">
        <tr>
            <th>No</th>
            
            <th>Tanggal</th>
            <th>Deskripsi</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td>Saldo Awal</td>
            <td>{{$selected_account->debt_format}}</td>
            <td>{{$selected_account->credit_format}}</td>
            <td>{{$selected_account->balance_format}}</td>
        </tr>
        @foreach ($transactions as $i => $transaction)
            <tr>
                <td>{{ ++$i }}</td>
                
                <td>{{ $transaction->date->format('d-m-Y') }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ $transaction->debt_format }}</td>
                <td>{{ $transaction->credit_format }}</td>
                <td>{{ $transaction->balance_format }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
window.print()
</script>