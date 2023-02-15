<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('NIS') }}
            {{ Form::text('NIS', $student->NIS, ['class' => 'form-control' . ($errors->has('NIS') ? ' is-invalid' : ''), 'placeholder' => 'Nis']) }}
            {!! $errors->first('NIS', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('name') }}
            {{ Form::text('name', $student->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('phone') }}
            {{ Form::text('phone', $student->phone, ['class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : ''), 'placeholder' => 'phone']) }}
            {!! $errors->first('phone', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Account Number') }}
            {{ Form::text('account_number', $student->account_number, ['class' => 'form-control' . ($errors->has('account_number') ? ' is-invalid' : ''), 'placeholder' => 'No rekening']) }}
            {!! $errors->first('account_number', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Account Holder') }}
            {{ Form::text('account_holder', $student->account_holder, ['class' => 'form-control' . ($errors->has('account_holder') ? ' is-invalid' : ''), 'placeholder' => 'Pemegang rekening']) }}
            {!! $errors->first('account_holder', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>