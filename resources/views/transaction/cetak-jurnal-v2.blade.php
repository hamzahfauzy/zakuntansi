<h2 align="center">Jurnal <br> {{auth()->user()->installation->company_name}}</h2>
<p align="center">
    {{$_GET['from']}} sampai dengan {{$_GET['to']}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead class="thead">
        <tr>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Akun / Deskripsi</th>
            <th>Debit</th>
            <th>Kredit</th>
        </tr>
    </thead>
    <tbody>
        <?php $account_id = "" ?>
        @forelse ($transactions as $transaction)
            @if($transaction->account_id != $account_id)
            <tr>
                <td colspan="5">{{ $transaction->account->account_code }} - {{ $transaction->account->name }}</td>
            </tr>
            @php($account_id=$transaction->account_id)
            @endif
            @if($transaction->parent)
            <tr>
                <td>{{$transaction->parent->transaction_code}}</td>
                
                <td>{{ $transaction->date->format('d/m/Y') }}</td>
                <td>{{ $transaction->parent->description }}</td>
                <td>{{ $transaction->debt_format }}</td>
                <td>{{ $transaction->credit_format }}</td>
            </tr>
            @else
            <tr>
                <td>{{$transaction->transaction_code}}</td>
                
                <td>{{ $transaction->date->format('d/m/Y') }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ $transaction->debt_format }}</td>
                <td>{{ $transaction->credit_format }}</td>
            </tr>
            @endif
        @empty
            <tr>
                <td colspan="5"><i>Tidak ada Data</i></td>
            </tr>
        @endforelse
    </tbody>
</table>
<script>
window.print()
</script>