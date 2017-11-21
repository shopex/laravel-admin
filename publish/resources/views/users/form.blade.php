<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    {!! Form::label('name', '用户名: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('email') ? ' has-error' : ''}}">
    {!! Form::label('email', '邮箱: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('password') ? ' has-error' : ''}}">
    {!! Form::label('password', '密码: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('roles') ? ' has-error' : ''}}">
    {!! Form::label('role', '角色: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {{ Form::finder_role("roles", $roles, $attributes = array('class'=>'form-control','multiple' => true)) }}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
