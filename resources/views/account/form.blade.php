<div class="box box-info padding-1">
    <div class="box-body">
        <div class="form-group">
            {{ Form::label('Akun Parent') }}
            {{ Form::select('parent_account_id', $all_accounts, $account->parent_account_id, ['class' => 'form-control select2' . ($errors->has('pos') ? ' is-invalid' : ''), 'placeholder' => '- Pilih -']) }}
            {!! $errors->first('pos', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Kode Akun') }}
            {{ Form::text('account_code', $account->account_code, ['class' => 'form-control' . ($errors->has('account_code') ? ' is-invalid' : ''), 'placeholder' => 'Account Code']) }}
            {!! $errors->first('account_code', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Kode Transaksi') }}
            {{ Form::text('account_transaction_code', $account->account_transaction_code, ['class' => 'form-control' . ($errors->has('account_transaction_code') ? ' is-invalid' : ''), 'placeholder' => 'Account Transaction Code']) }}
            {!! $errors->first('account_transaction_code', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Nama') }}
            {{ Form::text('name', $account->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('POS') }}
            {{ Form::select('pos', ['Nrc'=>'Nrc','Lr'=>'Lr'] ,$account->pos, ['required','class' => 'form-control' . ($errors->has('pos') ? ' is-invalid' : ''), 'placeholder' => '- Pilih -']) }}
            {!! $errors->first('pos', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Saldo Normal') }}
            {{ Form::select('normal_balance', ['Db'=>'Db','Cr'=>'Cr'] ,$account->normal_balance, ['required','class' => 'form-control' . ($errors->has('normal_balance') ? ' is-invalid' : ''), 'placeholder' => '- Pilih -']) }}
            {!! $errors->first('normal_balance', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label(__('balance')) }}
            {{ Form::text('balance', $account->balance??0, ['class' => 'form-control' . ($errors->has('balance') ? ' is-invalid' : ''), 'placeholder' => 'balance']) }}
            {!! $errors->first('balance', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>