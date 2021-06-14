<h2 align="center">Neraca - {{$book->name}} <br> {{config('app.name', 'Laravel')}}</h2>
<p align="center">
    {{$book->date_from->format('d-m-Y')}} sampai dengan {{$book->date_to->format('d-m-Y')}}
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
                    <b>{{ $account->refAccount->account_code }} - {{ $account->refAccount->name }}</b>
                </td>
                <td>{{ count($account->childs) ? '-' : $account->t_balance_format }}</td>
            </tr>
        @foreach ($account->childs as $child_1)
        <?php 
        if(count($child_1->childs))
        ?>
            <tr>
                <td>
                    {{ $child_1->refAccount->account_code }} - {{ $child_1->refAccount->name }}<br>
                </td>
                <td>{{ count($child_1->childs) ? '-' : $child_1->t_balance_format }}</td>
            </tr>
        @foreach ($child_1->childs as $child_2)
        <?php 
        if(count($child_2->childs))
        ?>
            <tr>
                <td>
                    {{ $child_2->refAccount->account_code }} - {{ $child_2->refAccount->name }}<br>
                </td>
                <td>{{ count($child_2->childs) ? '-' : $child_2->t_balance_format }}</td>
            </tr>
        @foreach ($child_2->childs as $child_3)
        <?php 
        if(count($child_3->childs))
        ?>
            <tr>
                <td>
                    {{ $child_3->refAccount->account_code }} - {{ $child_3->refAccount->name }}<br>
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
<script>
window.print()
</script>