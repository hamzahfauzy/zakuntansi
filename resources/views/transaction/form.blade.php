<div class="box box-info padding-1">
    <div class="box-body">
        @if(isset($transactions))
        <input type="hidden" id="all_transactions" value='@json($transactions)'>
        @endif
        <div class="form-group">
            {{ Form::label('Akun') }}
            {{ Form::select('account_id', $accounts, $transaction->account_id, ['class' => 'form-control select2 ' . ($errors->has('account_id') ? ' is-invalid' : ''),'placeholder'=>'- Pilih -','required']) }}
            {!! $errors->first('account_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>

        <div class="form-group">
            <table class="table table-bordered" id="table-jurnal">
                <thead>
                    <tr>
                        <th></th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Nominal</th>
                        <th style="display:none"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="row_1">
                        <td>
                            @if($transaction->id)
                            <button class="btn btn-sm btn-danger" type="button" onclick="deleteFirstRow()">X</button>
                            @endif
                        </td>
                        <td>
                            {{ Form::select('tipe[]', ['Debt'=>'Debt','Credit'=>'Credit'], $transaction->debt==0?'Credit':'Debt', ['class' => 'form-control','placeholder'=>'- Pilih -','required']) }}
                        </td>
                        <td>
                            {{ Form::date('date[]', $transaction->date, ['class' => 'form-control', 'placeholder' => 'Tanggal','required']) }}
                        </td>
                        <td>
                            {{ Form::text('description[]', $transaction->description, ['class' => 'form-control', 'placeholder' => 'Description','required']) }}
                        </td>
                        <td>
                            {{ Form::number('nominal[]', $transaction->debt==0?$transaction->credit:$transaction->debt, ['class' => 'form-control', 'placeholder' => 'Nominal','required']) }}
                        </td>
                        <td style="display:none">
                            <input type="hidden" name="transaction_id[]" value="{{$transaction->id}}" class="transaction_ids">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button class="btn btn-success" type="button" onclick="addRow()">Tambah Data</button>
    </div>
</div>
<script>
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
        // alert(newcell.childNodes[1].type);
        switch(newcell.childNodes[1].type) {
            case "text":
                    newcell.childNodes[1].value = val[i]??"";
                    break;
            case "checkbox":
                    newcell.childNodes[1].checked = val[i]??false;
                    break;
            case "select-one":
                    newcell.childNodes[1].selectedIndex = val[i]??0;
                    break;
            case "date":
                    newcell.childNodes[1].value = val[i]??'';
                    break;
            default:
                    newcell.childNodes[1].value = val[i];
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
    console.log(table.rows)
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
@if(isset($transactions))
var all_transactions = document.querySelector('#all_transactions').value
all_transactions = JSON.parse(all_transactions)
all_transactions.forEach((transaction,idx) => {
    if(idx==0) return
    addRow(['',parseInt(transaction.debt)==0?2:1,transaction.date.split('T')[0],transaction.description,transaction.debt==0?transaction.credit:transaction.debt,transaction.id])
})
@endif
</script>