<h2 align="center">Jurnal - {{$book->name}} <br> {{config('app.name', 'Laravel')}}</h2>
<p align="center">
    {{$book->date_from->format('d-m-Y')}} sampai dengan {{$book->date_to->format('d-m-Y')}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead class="thead">
        <tr>
            <th>#</th>
            
            <th>Tanggal</th>
            <th>Akun</th>
            <!-- <th>Ref.</th> -->
            <th>Deskripsi</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Balance</th>

        </tr>
    </thead>
    <tbody>
        @forelse ($transactions as $transaction)
            <tr>
                <td>{{++$i}}</td>
                
                <td>{{ $transaction->date->format('d-m-Y') }}</td>
                <td>{{ $transaction->account->refAccount->account_code }} - {{ $transaction->account->refAccount->name }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ $transaction->debt_format }}</td>
                <td>{{ $transaction->credit_format }}</td>
                <td>{{ $transaction->balance_format }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7"><i>Tidak ada Data</i></td>
            </tr>
        @endforelse
    </tbody>
</table>
<script>
window.print()
</script>