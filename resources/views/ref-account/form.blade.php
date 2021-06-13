<div class="box box-info padding-1">
    <div class="box-body">
        @if(!isset($_GET['parent_id']))
        <div class="form-group">
            {{ Form::label('Akun Parent') }}
            {{ Form::select('parent_id', $all_accounts, $refAccount->parent_id, ['class' => 'form-control select2' . ($errors->has('pos') ? ' is-invalid' : ''), 'placeholder' => '- Pilih -']) }}
            {!! $errors->first('parent_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        @else
        {{ Form::hidden('parent_id', $refAccount->parent_id) }}
        @endif
        <div class="form-group">
            {{ Form::label('Kode Akun') }}
            {{ Form::text('account_code', $refAccount->account_code, ['class' => 'form-control' . ($errors->has('account_code') ? ' is-invalid' : ''), 'placeholder' => 'Account Code']) }}
            {!! $errors->first('account_code', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Nama') }}
            {{ Form::text('name', $refAccount->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('POS') }}
            {{ Form::select('pos', ['Nrc'=>'Nrc','Lr'=>'Lr'] ,$refAccount->pos, ['required','class' => 'form-control' . ($errors->has('pos') ? ' is-invalid' : ''), 'placeholder' => '- Pilih -']) }}
            {!! $errors->first('pos', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Saldo Normal') }}
            {{ Form::select('normal_balance', ['Db'=>'Db','Cr'=>'Cr'] ,$refAccount->normal_balance, ['required','class' => 'form-control' . ($errors->has('normal_balance') ? ' is-invalid' : ''), 'placeholder' => '- Pilih -']) }}
            {!! $errors->first('normal_balance', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>