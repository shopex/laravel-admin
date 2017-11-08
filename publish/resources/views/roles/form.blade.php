<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    {!! Form::label('name', '名称: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
    {!! Form::label('label', '标签: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('label', null, ['class' => 'form-control']) !!}
        {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('permissions') ? ' has-error' : ''}}">
    {!! Form::label('label', '权限: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        <div class="checkbox">
            @foreach($data as $row)
                <h5>{{$row['group']}}</h5>
                @foreach($row['permissions'] as $per => $router)
                    <label>
                      <input type="checkbox" 
                            name="permissions[]" 
                            value="{{$per}}"
                            @if(isset($role) && is_array($role->permissions) && in_array($per, $role->permissions))
                            checked="checked"
                            @endif
                        > 
                            {{$router['name']}}

                    </label>
                @endforeach
            @endforeach
      </div>
    </div>
</div>
 
 
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : '创建', ['class' => 'btn btn-primary']) !!}
    </div>
</div>