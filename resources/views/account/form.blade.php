<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('book_id') }}
            {{ Form::text('book_id', $account->book_id, ['class' => 'form-control' . ($errors->has('book_id') ? ' is-invalid' : ''), 'placeholder' => 'Book Id']) }}
            {!! $errors->first('book_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('ref_account_id') }}
            {{ Form::text('ref_account_id', $account->ref_account_id, ['class' => 'form-control' . ($errors->has('ref_account_id') ? ' is-invalid' : ''), 'placeholder' => 'Ref Account Id']) }}
            {!! $errors->first('ref_account_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('debt') }}
            {{ Form::text('debt', $account->debt, ['class' => 'form-control' . ($errors->has('debt') ? ' is-invalid' : ''), 'placeholder' => 'Debt']) }}
            {!! $errors->first('debt', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('credit') }}
            {{ Form::text('credit', $account->credit, ['class' => 'form-control' . ($errors->has('credit') ? ' is-invalid' : ''), 'placeholder' => 'Credit']) }}
            {!! $errors->first('credit', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>