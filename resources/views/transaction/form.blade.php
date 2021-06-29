<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-group">
            <b>Transaksi</b>
            <input type="hidden" id="accounts_code" value='@json($kode)'>
            @if(count($transaction->items))
            <input type="hidden" id="all_items" value='@json($transaction->items)'>
            @endif
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="">Akun</label>
                    {{ Form::select('account_id', $accounts, $transaction->account_id, ['class' => 'form-control akun','placeholder'=>'- Pilih -','required']) }}
                </div>
                <div class="form-group">
                    <label for="">Tanggal</label>
                    {{ Form::date('date', $transaction->date, ['class' => 'form-control form-tanggal', 'placeholder' => 'Tanggal','required']) }}
                </div>
                <div class="form-group">
                    <label for="">Kode</label>
                    {{ Form::text('transaction_code', $transaction->transaction_code, ['class' => 'form-control kode-transaksi', 'placeholder' => 'Kode Transaksi','required']) }}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label for="">Deskripsi</label>
                    {{ Form::text('description', $transaction->description, ['class' => 'form-control', 'placeholder' => 'Description','required']) }}
                </div>
                <div class="form-group">
                    <label for="">Tipe</label>
                    {{ Form::select('tipe', ['Debt'=>'Debt','Credit'=>'Credit'], $transaction->debt==0?'Credit':'Debt', ['class' => 'form-control','placeholder'=>'- Pilih -','required']) }}
                </div>
                <div class="form-group">
                    <label for="">Nominal (<span id="nominal">{{$transaction->debt||$transaction->credit?'Rp '.number_format($transaction->debt>0?$transaction->debt:$transaction->credit):0}}</span>)</label>
                    {{ Form::number('nominal', $transaction->debt||$transaction->credit?($transaction->debt>0?$transaction->debt:$transaction->credit):0, ['class' => 'form-control nominal', 'placeholder' => 'Nominal','required','min'=>0,'onblur'=>'calculateAllNominal()']) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <b>Lawan Transaksi (Sisa : <span id="sisa_nominal">0</span>)</b>
        </div>
        <div class="form-group">
            <table class="table table-bordered" id="table-jurnal">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Akun</th>
                        <th>Nominal</th>
                        <th>Tipe</th>
                        <th style="display:none"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="row_1">
                        <td width="10%">

                        </td>
                        <td width="20%">
                            {{ Form::select('item_account_id[]', $accounts, '', ['class' => 'form-control','placeholder'=>'- Pilih -']) }}
                        </td>
                        <td width="50%">
                            {{ Form::number('item_nominal[]', 0, ['class' => 'form-control all_nominal', 'placeholder' => 'Nominal','onkeyup' => 'calculateAllNominal()','min'=>0, 'onfocus'=>'putSisaNominal(this)']) }}
                        </td>
                        <td width="20%">
                            {{ Form::select('item_tipe[]', ['Debt'=>'Debt','Credit'=>'Credit'], 'Debt', ['class' => 'form-control item_types','placeholder'=>'- Pilih -']) }}
                        </td>
                        <td style="display:none">
                            <input type="hidden" name="item_id[]" value="" class="transaction_item_ids">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary btn-submit">Simpan</button>
        <button class="btn btn-success" type="button" onclick="addRow()">Tambah Data</button>
    </div>
</div>
<div style="display:none" id="accounts_element">
{{ Form::select('all_account_id', $accounts, $transaction->account_id, ['class' => 'form-control','placeholder'=>'- Pilih -']) }}
</div>
@section('script')
<script>
var sisa_nominal = 0
document.querySelector('.form-tanggal').addEventListener('change',e => {
    updateKode()
})
document.querySelector('.akun').addEventListener('change',e => {
    updateKode()
})
document.querySelector('.nominal').addEventListener('change',e => {
    var nominal = e.target.value
    var formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',

        // These options are needed to round to whole numbers if that's what you want.
        minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
    });
    document.querySelector('#nominal').innerHTML = formatter.format(nominal)
    calculateAllNominal()
})
function updateKode()
{
    var akun = document.querySelector('.akun').value
    var tanggal = document.querySelector('.form-tanggal').value
    if(akun == '' || tanggal == '') return
    var accounts_code = document.querySelector('#accounts_code').value
    accounts_code = JSON.parse(accounts_code)
    tanggal = tanggal.substring(5,7)

    fetch('/home/count-transaction/'+accounts_code[akun]+'/'+tanggal)
    .then(res => res.json())
    .then(res => {
        document.querySelector('.kode-transaksi').value = res.code // accounts_code[akun]+'/'+tanggal+'/$count_transaction'
    })

}
function calculateAllNominal()
{
    var all_nominal = document.querySelectorAll('.all_nominal')
    var item_tipe = document.querySelectorAll('.item_types')
    console.log(item_tipe)
    var nominal_trx = document.querySelector('.nominal').value
    var nominal_value = 0
    all_nominal.forEach((el,idx) => {
        var i_tipe = item_tipe[idx].value
        if(i_tipe == 'Debt')
            nominal_value += parseInt(el.value)
        else
            nominal_value -= parseInt(el.value)
    })

    console.log(nominal_value, nominal_trx)
    
    document.querySelector('.btn-submit').disabled = true
    if(nominal_value == nominal_trx)
        document.querySelector('.btn-submit').disabled = false

    var trx_value = parseInt(document.querySelector('.nominal').value)
    var sisa = trx_value - nominal_value
    console.log(sisa)
    if(isNaN(sisa)) sisa = 0
    sisa_nominal = sisa

    updateSisaNominal()
}
function updateSisaNominal()
{
    document.getElementById('sisa_nominal').innerHTML = (new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',

        // These options are needed to round to whole numbers if that's what you want.
        minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
    })).format(sisa_nominal)
}
function putSisaNominal(el)
{
    if(el.value == '' || el.value == 0)
        el.value = sisa_nominal

        calculateAllNominal()
}
function addRow(val = false)
{
    var table = document.getElementById('table-jurnal');

    var rowCount = table.rows.length;
    var row = table.insertRow(rowCount);
    row.id = "row_"+rowCount

    var colCount = table.rows[0].cells.length;
    var newcell	= row.insertCell(0);
    newcell.innerHTML = '<button class="btn btn-sm btn-danger" type="button" onclick="deleteRow('+rowCount+')">X</button>'

    for(var i=1; i<colCount; i++) {

        var newcell	= row.insertCell(i);
        if(i==colCount-1)
            newcell.style.display="none"

        newcell.innerHTML = table.rows[1].cells[i].innerHTML;
        switch(newcell.childNodes[1].type) {
            case "text":
                    newcell.childNodes[1].value = val[i]??"";
                    newcell.childNodes[1].required = true
                    break;
            case "number":
                    newcell.childNodes[1].value = val[i]??0;
                    newcell.childNodes[1].required = true
                    break;
            case "checkbox":
                    newcell.childNodes[1].checked = val[i]??false;
                    newcell.childNodes[1].required = true
                    break;
            case "select-one":
                    newcell.childNodes[1].value = val[i]??newcell.childNodes[1].value;
                    newcell.childNodes[1].required = true
                    break;
                    // if(newcell.childNodes[1].classList.contains('select2'))
                    // {
                    //     newcell.innerHTML = document.getElementById('accounts_element').innerHTML
                    //     if(val == false)
                    //         newcell.childNodes[1].value = ''
                    //     else
                    //         newcell.childNodes[1].value = val[i]??0;

                    //     newcell.childNodes[1].required = true
                    //     newcell.childNodes[1].name = 'item_account_id[]'
                    //     newcell.childNodes[1].classList.add('select2')
                    //     $(newcell.childNodes[1]).select2()
                    //     // select2reinit()
                    // }
                    // else
                    // {
                    //     newcell.childNodes[1].value = val[i];
                    //     newcell.childNodes[1].required = true
                    // }
                    // break;
            case "date":
                    newcell.childNodes[1].value = val[i]??'';
                    newcell.childNodes[1].required = true
                    break;
            default:
                    newcell.childNodes[1].value = val[i];
                    newcell.childNodes[1].required = true
                    break;
        }
    }
}

function deleteRow(row_id) {
    document.getElementById('row_'+row_id).remove()
}

function deleteFirstRow()
{
    var table = document.getElementById('table-jurnal');
    // table.rows[1].cells[i]
    if(table.rows.length == 2)
    {
        var colCount = table.rows[0].cells.length;
        newcell = table.rows[1]
        for(var i=1; i<colCount; i++) {
            //alert(newcell.childNodes);
            switch(newcell.cells[i].childNodes[1].type) {
                case "text":
                        newcell.cells[i].childNodes[1].value = "";
                        break;
                case "date":
                        newcell.cells[i].childNodes[1].value = "";
                        break;
                case "number":
                        newcell.cells[i].childNodes[1].value = "";
                        break;
                case "checkbox":
                        newcell.cells[i].childNodes[1].checked = false;
                        break;
                case "select-one":
                        newcell.cells[i].childNodes[1].selectedIndex = 0;
                        break;
            }
        }
    }
    else
    {
        deleteRow(1)
    }
}

@if(count($transaction->items))
var all_transactions = document.querySelector('#all_items').value
all_transactions = JSON.parse(all_transactions)
all_transactions.forEach((transaction,idx) => {
    addRow(['',transaction.account_id,transaction.debt==0?transaction.credit:transaction.debt,transaction.debt==0?'Credit':'Debt',transaction.id])
})
deleteRow(1)
calculateAllNominal()
@endif
</script>
@endsection