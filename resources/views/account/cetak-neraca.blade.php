<h2 align="center">Neraca <br> {{auth()->user()->installation->company_name}}</h2>
<p align="center">
    {{$_GET['from']}} sampai dengan {{$_GET['to']}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
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
                <td>{{ count($account->childs) ? '-' : $account->t_balance_format }}</td>
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
                <td>
                    Total Saldo
                </td>
                <td>{{$account->balance_format()}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
Total Activa = {{$neraca['aktiva']}}<br>
Total Hutang = {{$neraca['hutang']}}<br>
Total Modal = {{$neraca['modal']}}<br>
{{$neraca['saldo'] == 0 ? 'Balance' : 'Tidak Balance'}}<br>
<script>
window.print()
</script>