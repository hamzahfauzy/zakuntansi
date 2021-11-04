<div class="box box-info padding-1">
    <div class="box-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if(empty($bill->user_id))
        <div class="form-group">
            {{ Form::label('nama') }}
            {{ Form::select('user_id[]', $users, $bill->user_id, ['class' => 'form-control select2' . ($errors->has('user_id') ? ' is-invalid' : ''), 'multiple']) }}
            {!! $errors->first('user_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        @else
        <div class="form-group">
            {{ Form::label('nama') }}
            {{ Form::text('', $bill->user->name.' ('.$bill->user->email.')', ['class' => 'form-control','disabled']) }}
            {{ Form::hidden('user_id', $bill->user_id) }}
        </div>
        @endif
        <div class="form-group">
            {{ Form::label('merchant') }}
            {{ Form::select('merchant_id', $merchants, $bill->merchant_id, ['class' => 'form-control select2' . ($errors->has('merchant_id') ? ' is-invalid' : ''), 'placeholder' => 'Pilih Merchant']) }}
            {!! $errors->first('merchant_id', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('tahun') }}
            {{ Form::text('year', $bill->year, ['class' => 'form-control' . ($errors->has('year') ? ' is-invalid' : ''), 'placeholder' => 'Year']) }}
            {!! $errors->first('year', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('total') }}
            {{ Form::number('total', $bill->total, ['class' => 'form-control' . ($errors->has('total') ? ' is-invalid' : ''), 'placeholder' => 'Total']) }}
            {!! $errors->first('total', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('jatuh tempo') }}
            {{ Form::number('due_date', $bill->due_date, ['class' => 'form-control' . ($errors->has('due_date') ? ' is-invalid' : ''), 'placeholder' => 'Due Date']) }}
            {!! $errors->first('due_date', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        @if($bill->termin)
        <div class="form-group">
            {{ Form::label('termin') }}
            {{ Form::number('termin', $bill->termin, ['class' => 'form-control' . ($errors->has('termin') ? ' is-invalid' : ''), 'placeholder' => 'Termin']) }}
            {!! $errors->first('termin', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        @else
        <div class="form-group">
            {{ Form::label('jumlah termin') }}
            {{ Form::number('jumlah_termin', 1, ['class' => 'form-control' . ($errors->has('jumlah_termin') ? ' is-invalid' : ''), 'placeholder' => 'Jumlah Termin']) }}
            {!! $errors->first('jumlah_termin', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        @endif

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>