<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('Akun') }}
            {{ Form::select('account_id', $accounts, $transaction->account_id, ['class' => 'form-control select2 ' . ($errors->has('account_id') ? ' is-invalid' : ''),'placeholder'=>'- Pilih -']) }}
            {!! $errors->first('account_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Akun Penyesuaian') }}
            {{ Form::select('ref_account_id', $accounts, $transaction->ref_account_id, ['class' => 'form-control select2 ' . ($errors->has('account_id') ? ' is-invalid' : ''),'placeholder'=>'- Pilih -']) }}
            {!! $errors->first('ref_account_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Tanggal') }}
            {{ Form::date('date', $transaction->date, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
            {!! $errors->first('date', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Deskripsi') }}
            {{ Form::text('description', $transaction->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Referensi') }}
            {{ Form::text('reference', $transaction->reference, ['class' => 'form-control' . ($errors->has('reference') ? ' is-invalid' : ''), 'placeholder' => 'Reference']) }}
            {!! $errors->first('reference', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Debit') }}
            {{ Form::text('debt', $transaction->debt, ['class' => 'form-control' . ($errors->has('debt') ? ' is-invalid' : ''), 'placeholder' => 'Debt']) }}
            {!! $errors->first('debt', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Kredit') }}
            {{ Form::text('credit', $transaction->credit, ['class' => 'form-control' . ($errors->has('credit') ? ' is-invalid' : ''), 'placeholder' => 'Credit']) }}
            {!! $errors->first('credit', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>