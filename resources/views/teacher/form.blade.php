<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('ID/NIK/NIP') }}
            {{ Form::text('NIK', $teacher->NIK, ['class' => 'form-control' . ($errors->has('NIK') ? ' is-invalid' : ''), 'placeholder' => 'ID/NIK/NIP']) }}
            {!! $errors->first('NIK', '<div class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('name') }}
            {{ Form::text('name', $teacher->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</p>') !!}
        </div>

    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>