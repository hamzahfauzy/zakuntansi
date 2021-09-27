<div class="box box-info padding-1">
    <div class="box-body">
        <input type="hidden" name="user_id" value="{{$payment->user_id}}">
        <div class="form-group">
            {{ Form::label('tagihan') }}
            {{ Form::select('bill_id', $bills, $payment->bill_id, ['class' => 'form-control select2' . ($errors->has('bill_id') ? ' is-invalid' : ''), 'placeholder'=>'Pilih']) }}
            {!! $errors->first('bill_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('total') }}
            {{ Form::text('total', $payment->total, ['class' => 'form-control' . ($errors->has('total') ? ' is-invalid' : ''), 'placeholder' => 'Total']) }}
            {!! $errors->first('total', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>