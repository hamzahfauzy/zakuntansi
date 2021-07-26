<?php $book = session('book') ?>
<h2 align="center">Buku Besar <br> {{auth()->user()->installation->company_name}}</h2>
<p align="center">
    {{$_GET['from']}} sampai dengan {{$_GET['to']}}
</p>
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead class="thead">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nomor Bukti</th>
            <th>Referensi</th>
            <th>Uraian</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Saldo Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accounts as $i => $account)
        <tr class="pointer" id="parent{{$account->id}}" data-toggle="collapse" data-target=".parent{{$i}}Content">
            <td></td>
            <td></td>
            <td class="font-weight-bold">{{$account->account_code}}</td>
            <td></td>
            <td class="font-weight-bold">{{$account->name}}<br>Saldo Awal : {{$account->balance_format}}</td>
            <td></td>
            <td></td>
            <td class="font-weight-bold">{{$account->balance_format()}}</td>
        </tr>
        @if(count($account->all_transactions()))
            <?php $saldo_awal = $account->balance; ?>
            <?php $t_debt = 0; ?>
            <?php $t_credit = 0; ?>
            @foreach($account->all_transactions() as $key => $transaction)
                @if($transaction->items()->exists())
                    @foreach($transaction->items as $t)
                        <?php
                        if($t->debt > 0)
                            $saldo_awal -= $t->balance;
                        ?>
                        <tr class="parent{{$i}}Content collapse pointer" data-parent="#parent{{$account->id}}">
                            <td>{{++$key}}</td>
                            <td>{{$transaction->date->format('d/m/Y')}}</td>
                            <td>{{$t->parent->transaction_code}}</td>
                            <td>{{$t->account->account_code.' - '.$t->account->name}}</td>
                            <td>{{$t->parent->description}}</td>
                            <td>{{number_format($t->debt)}}</td>
                            <td>{{number_format($t->credit)}}</td>
                            <td>{{number_format($saldo_awal)}}</td>
                        </tr>
                        <?php $t_debt += $t->debt ?>
                        <?php $t_credit += $t->credit ?>
                    @endforeach
                @else
                <tr class="parent{{$i}}Content collapse pointer" data-parent="#parent{{$account->id}}">
                    <td>{{++$key}}</td>
                    <td>{{$transaction->date->format('d/m/Y')}}</td>
                    <td>{{$transaction->parent->transaction_code}}</td>
                    <td>{{$transaction->parent->account->account_code.' - '.$transaction->parent->account->name}}</td>
                    <td>{{$transaction->parent->description}}</td>
                    <td>{{number_format($transaction->debt)}}</td>
                    <td>{{number_format($transaction->credit)}}</td>
                    <td> - </td>
                </tr>
                @endif
            @endforeach
        @else
            @foreach($account->childs as $k => $acc)
                <tr class="parent{{$i}}Content collapse pointer" id="parent{{$acc->id}}" data-parent="#parent{{$account->id}}" data-toggle="collapse" data-target=".childContent{{$acc->id}}">
                    <td></td>
                    <td></td>
                    <td class="font-weight-bold">&nbsp;&nbsp;&nbsp;&nbsp;{{$acc->account_code}}</td>
                    <td></td>
                    <td class="font-weight-bold">{{$acc->name}}<br>Saldo Awal : {{$acc->balance_format}}</td>
                    <td></td>
                    <td></td>
                    <td class="font-weight-bold">{{$acc->balance_format()}}</td>
                </tr>
                @if(count($acc->all_transactions()))
                    <?php $saldo_awal = $acc->balance; ?>
                    <?php $t_debt = 0; ?>
                    <?php $t_credit = 0; ?>
                    @foreach($acc->all_transactions() as $key => $transaction)
                        @if($transaction->items()->exists())
                            @foreach($transaction->items as $t)
                                <?php
                                if($t->debt > 0)
                                    $saldo_awal -= $t->balance;
                                ?>
                                <tr class="childContent{{$acc->id}} collapse pointer" data-parent="#parent{{$acc->id}}">
                                    <td>{{++$key}}</td>
                                    <td>{{$transaction->date->format('d/m/Y')}}</td>
                                    <td>{{$t->parent->transaction_code}}</td>
                                    <td>{{$t->account->account_code.' - '.$t->account->name}}</td>
                                    <td>{{$t->parent->description}}</td>
                                    <td>{{number_format($t->debt)}}</td>
                                    <td>{{number_format($t->credit)}}</td>
                                    <td>{{number_format($saldo_awal)}}</td>
                                </tr>
                                <?php $t_debt += $t->debt ?>
                                <?php $t_credit += $t->credit ?>
                            @endforeach
                        @else
                        <tr class="childContent{{$acc->id}} collapse pointer" data-parent="#parent{{$acc->id}}">
                            <td>{{++$key}}</td>
                            <td>{{$transaction->date->format('d/m/Y')}}</td>
                            <td>{{$transaction->parent->transaction_code}}</td>
                            <td>{{$transaction->parent->account->account_code.' - '.$transaction->parent->account->name}}</td>
                            <td>{{$transaction->parent->description}}</td>
                            <td>{{number_format($transaction->debt)}}</td>
                            <td>{{number_format($transaction->credit)}}</td>
                            <td> - </td>
                        </tr>
                        @endif
                    @endforeach
                @else
                    @foreach($acc->childs as $idx => $acc2)
                        <tr class="childContent{{$acc->id}} collapse pointer" id="parent{{$acc2->id}}" data-toggle="collapse" data-target=".childContent{{$acc2->id}}">
                            <td></td>
                            <td></td>
                            <td class="font-weight-bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$acc2->account_code}}</td>
                            <td></td>
                            <td class="font-weight-bold">{{$acc2->name}}<br>Saldo Awal : {{$acc2->balance_format}}</td>
                            <td></td>
                            <td></td>
                            <td class="font-weight-bold">{{$acc2->balance_format()}}</td>
                        </tr>
                        @if(count($acc2->all_transactions()))
                            <?php $saldo_awal = $acc2->balance; ?>
                            <?php $t_debt = 0; ?>
                            <?php $t_credit = 0; ?>
                            @foreach($acc2->all_transactions() as $key => $transaction)
                                @if($transaction->items()->exists())
                                    @foreach($transaction->items as $t)
                                        <?php
                                        if($t->debt > 0)
                                            $saldo_awal -= $t->balance;
                                        ?>
                                        <tr class="childContent{{$acc2->id}} collapse pointer" data-parent="#parent{{$acc2->id}}">
                                            <td>{{++$key}}</td>
                                            <td>{{$transaction->date->format('d/m/Y')}}</td>
                                            <td>{{$t->parent->transaction_code}}</td>
                                            <td>{{$t->account->account_code.' - '.$t->account->name}}</td>
                                            <td>{{$t->parent->description}}</td>
                                            <td>{{number_format($t->debt)}}</td>
                                            <td>{{number_format($t->credit)}}</td>
                                            <td>{{number_format($saldo_awal)}}</td>
                                        </tr>
                                        <?php $t_debt += $t->debt ?>
                                        <?php $t_credit += $t->credit ?>
                                    @endforeach
                                @else
                                <tr class="childContent{{$acc2->id}} collapse pointer" data-parent="#parent{{$acc2->id}}">
                                    <td>{{++$key}}</td>
                                    <td>{{$transaction->date->format('d/m/Y')}}</td>
                                    <td>{{$transaction->parent->transaction_code}}</td>
                                    <td>{{$transaction->parent->account->account_code.' - '.$transaction->parent->account->name}}</td>
                                    <td>{{$transaction->parent->description}}</td>
                                    <td>{{number_format($transaction->debt)}}</td>
                                    <td>{{number_format($transaction->credit)}}</td>
                                    <td> - </td>
                                </tr>
                                @endif
                            @endforeach
                        @else
                            @foreach($acc2->childs as $idx3 => $acc3)
                                <tr class="childContent{{$acc2->id}} collapse pointer" id="parent{{$acc3->id}}" data-toggle="collapse" data-target=".childContent{{$acc3->id}}">
                                    <td></td>
                                    <td></td>
                                    <td class="font-weight-bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$acc3->account_code}}</td>
                                    <td></td>
                                    <td class="font-weight-bold">{{$acc3->name}}<br>Saldo Awal : {{$acc3->balance_format}}</td>
                                    <td></td>
                                    <td></td>
                                    <td class="font-weight-bold">{{$acc3->balance_format()}}</td>
                                </tr>
                                @if(count($acc3->all_transactions()))
                                    <?php $saldo_awal = $acc3->balance; ?>
                                    <?php $t_debt = 0; ?>
                                    <?php $t_credit = 0; ?>
                                    
                                    @foreach($acc3->all_transactions() as $key => $transaction)
                                        @if($transaction->items()->exists())
                                            @foreach($transaction->items as $t)
                                                <?php
                                                if($t->debt > 0)
                                                    $saldo_awal -= $t->balance;
                                                ?>
                                                <tr class="childContent{{$acc3->id}} collapse pointer" data-parent="#parent{{$acc3->id}}">
                                                    <td>{{++$key}}</td>
                                                    <td>{{$transaction->date->format('d/m/Y')}}</td>
                                                    <td>{{$t->parent->transaction_code}}</td>
                                                    <td>{{$t->account->account_code.' - '.$t->account->name}}</td>
                                                    <td>{{$t->parent->description}}</td>
                                                    <td>{{number_format($t->debt)}}</td>
                                                    <td>{{number_format($t->credit)}}</td>
                                                    <td>{{number_format($saldo_awal)}}</td>
                                                </tr>
                                                <?php $t_debt += $t->debt ?>
                                                <?php $t_credit += $t->credit ?>
                                            @endforeach
                                        @else
                                        <tr class="childContent{{$acc3->id}} collapse pointer" data-parent="#parent{{$acc3->id}}">
                                            <td>{{++$key}}</td>
                                            <td>{{$transaction->date->format('d/m/Y')}}</td>
                                            <td>{{$transaction->parent->transaction_code}}</td>
                                            <td>{{$transaction->parent->account->account_code.' - '.$transaction->parent->account->name}}</td>
                                            <td>{{$transaction->parent->description}}</td>
                                            <td>{{number_format($transaction->debt)}}</td>
                                            <td>{{number_format($transaction->credit)}}</td>
                                            <td> - </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endif
        <tr>
            <td colspan="8">
                <center>---------</center>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
window.print()
</script>