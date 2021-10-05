<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        th,td{
            padding:16px;
        }
        tr:first-child,tr:last-child{
            background:#84B0CA !important;
            color:white !important;
        }
        tr:nth-child(odd){
            background:#F3F8FA;
            color:#484B9A;
        }
        tr:nth-child(even){
            background:#fff;
            color:#484B9A;
        }


        table{
            width:100%;
            border-collapse:collapse;
        }

        body{
            font-family:Arial;
            width:1000px;
        }
    </style>

</head>
<body>

<div style="text-align:center">
    <img src="{{asset('storage/'.auth()->user()->installation->logo)}}" width="75" alt="Logo">
    <hr>
</div>

<div style="text-align:center">

    <div style="margin-bottom:24px;margin-top:24px;">
        <h2 style="margin:0;">Kwitansi Pembayaran</h2>
        <h5 style="margin:0;">No. {{strtotime($date)}} - {{$payments[count($payments)-1]->id}}</h5>
    </div>
    
    <table align="center" style="text-align:left;">
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th style="text-align:right">Pembayaran Tagihan</th>
        </tr>
        @foreach($payments as $key => $payment)
        <tr>
            <td>{{++$key}}</td>
            <td>{{$payment->bill->merchant->name}}</td>
            <td style="text-align:right">{{$payment->total_formatted}}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="2">
                Total
            </th>
            <th style="text-align:right">{{number_format($payments->sum('total'))}}</th>
        </tr>
    </table>

    
    <div style="display:flex;justify-content:space-between; margin-top:24px;">
        <div>
            <p>&nbsp;</p>
            <h4>Diterima Dari : {{$usr->name}}</h4>
        </div>
        <div style="text-align:right">
            <p>{{auth()->user()->installation->address}}, {{$date}}</p>
            <h4>Diterima Oleh : {{$payments[0]->staff->name}}</h4>
        </div>
    </div>
</div>



</body>
</html>