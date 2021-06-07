<h2 align="center">Neraca - {{$book->name}} <br> {{config('app.name', 'Laravel')}}</h2>
<p align="center">
    {{$book->date_from->format('d-m-Y')}} sampai dengan {{$book->date_to->format('d-m-Y')}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead class="thead">
        <tr>
            <th>No</th>
            
            <th style="white-space:nowrap;">Akun</th>
            <th>POS</th>
            <th style="white-space:nowrap;">Saldo Normal</th>
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
                
                <td style="white-space:nowrap;">{{ $account->refAccount->account_code }} - {{ $account->refAccount->name }}</td>
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
<script>
window.print()
</script>