<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-group">
            {{ Form::label(__('debt')) }}
            {{ Form::text('debt', $account->debt, ['class' => 'form-control' . ($errors->has('debt') ? ' is-invalid' : ''), 'placeholder' => 'Debt']) }}
            {!! $errors->first('debt', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label(__('credit')) }}
            {{ Form::text('credit', $account->credit, ['class' => 'form-control' . ($errors->has('credit') ? ' is-invalid' : ''), 'placeholder' => 'Credit']) }}
            {!! $errors->first('credit', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>