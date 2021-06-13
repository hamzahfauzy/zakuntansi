<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-group">
            {{ Form::label(__('balance')) }}
            {{ Form::text('balance', $account->balance, ['class' => 'form-control' . ($errors->has('balance') ? ' is-invalid' : ''), 'placeholder' => 'balance']) }}
            {!! $errors->first('balance', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>