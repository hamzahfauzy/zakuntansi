<div class="box box-info padding-1">
    <div class="box-body" id="form-body">
        <input type="hidden" name="user_id" value="{{$payment->user_id}}">
        
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    {{ Form::label('tagihan') }}
                    {{ Form::select('payment[0][bill_id]', $bills, $payment->bill_id, ['class' => 'form-control select2' . ($errors->has('bill_id') ? ' is-invalid' : ''), 'placeholder'=>'Pilih','required'=>true]) }}
                    {!! $errors->first('bill_id', '<div class="invalid-feedback">:message</p>') !!}
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    {{ Form::label('total') }}
                    {{ Form::text('payment[0][total]', $payment->total, ['class' => 'form-control' . ($errors->has('total') ? ' is-invalid' : ''), 'placeholder' => 'Total','required'=>true]) }}
                    {!! $errors->first('total', '<div class="invalid-feedback">:message</p>') !!}
                </div>
            </div>
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-success" id="add-tagihan">Tambah Tagihan</button>
    </div>
</div>

<script>

    $("#add-tagihan").click(function(){
        var rows = $("#form-body .row").get()

        var html = `<div class="row">
            <div class="col-6">
                <div class="form-group">
                    {{ Form::label('tagihan') }}
                    {{ Form::select('payment[${rows.length}][bill_id]', $bills, $payment->bill_id, ['class' => 'form-control select2' . ($errors->has('bill_id') ? ' is-invalid' : ''), 'placeholder'=>'Pilih','required'=>true]) }}
                    {!! $errors->first('bill_id', '<div class="invalid-feedback">:message</p>') !!}
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    {{ Form::label('total') }}
                    {{ Form::text('payment[${rows.length}][total]', $payment->total, ['class' => 'form-control' . ($errors->has('total') ? ' is-invalid' : ''), 'placeholder' => 'Total','required'=>true]) }}
                    {!! $errors->first('total', '<div class="invalid-feedback">:message</p>') !!}
                </div>
            </div>
        </div>`

        $("#form-body").append(html)
    })

</script>