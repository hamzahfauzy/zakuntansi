<h2 align="center">Jurnal <br> {{auth()->user()->installation->company_name}}</h2>
<p align="center">
    {{$_GET['from']}} sampai dengan {{$_GET['to']}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead class="thead">
        <tr>
            <th>Kode</th>
            <th>Akun</th>
            <th>Debit</th>
            <th>Kredit</th>
        </tr>
    </thead>
    <tbody>
        <?php $last_code = "" ?>
        @forelse ($transactions as $transaction)
            @if($transaction->transaction_code != $last_code)
            <tr>
                <td colspan="4" class="font-weight-bold">{{ $transaction->date->format('d/m/Y') }} - {{ $transaction->description }}</td>
            </tr>
            @endif
            <tr>
                <td>{{$transaction->transaction_code}}</td>
                
                <td>{{ $transaction->account->account_code }} - {{ $transaction->account->name }}</td>
                <td>{{ $transaction->debt_format }}</td>
                <td>{{ $transaction->credit_format }}</td>
            </tr>
            @foreach($transaction->items as $item)
            <tr>
                <td>{{$transaction->transaction_code}}</td>
                
                <td>{{ $item->account->account_code }} - {{ $item->account->name }}</td>
                <td>{{ $item->debt_format }}</td>
                <td>{{ $item->credit_format }}</td>
            </tr>
            @endforeach
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