<h2 align="center">Laba Rugi <br> {{auth()->user()->installation->company_name}}</h2>
<p align="center">
    {{$_GET['from']}} sampai dengan {{$_GET['to']}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead class="thead">
        <tr>
            <th>No</th>
            
            <th>Akun</th>
            <th width="150px">POS</th>
            <th width="150px">Saldo Normal</th>
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
                
                <td>{{ $account->account_code }} - {{ $account->name }}</td>
                <td>{{ $account->pos }}</td>
                <td>{{ $account->normal_balance }}</td>
                <td>{{ $account->t_debt_format }}</td>
                <td>{{ $account->t_credit_format }}</td>
                <td>{{ $account->t_balance_format }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6" style="text-align: right;"><b>Laba Rugi</b></td>
            <td>{{number_format($all_cr-$all_db)}}</td>
        </tr>
    </tbody>
</table>
<script>
window.print()
</script>