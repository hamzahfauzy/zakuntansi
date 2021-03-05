<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('name') }}
            {{ Form::text('name', $book->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('date_from') }}
            {{ Form::date('date_from', $book->date_from, ['class' => 'form-control' . ($errors->has('date_from') ? ' is-invalid' : ''), 'placeholder' => 'Date From']) }}
            {!! $errors->first('date_from', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('date_to') }}
            {{ Form::date('date_to', $book->date_to, ['class' => 'form-control' . ($errors->has('date_to') ? ' is-invalid' : ''), 'placeholder' => 'Date To']) }}
            {!! $errors->first('date_to', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        {{ Form::hidden('status', $book->status, ['class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => 'Status']) }}

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>