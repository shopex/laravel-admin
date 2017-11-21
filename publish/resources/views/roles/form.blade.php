<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    {!! Form::label('name', '名称: ', ['class' => 'col-md-1 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
    {!! Form::label('label', '标签: ', ['class' => 'col-md-1 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('label', null, ['class' => 'form-control']) !!}
        {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('permissions') ? ' has-error' : ''}}">
    {!! Form::label('label', '权限: ', ['class' => 'col-md-1 control-label']) !!}
    <div class="col-md-10 ">
        @foreach($data as $row)
            <div class="row panel panel-success">
                <div class="panel-body">
                    <h3 class="panel-title">{{$row['group']}}</h3>
                    菜单权限：
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

                @if(isset($row['datas']) && $row['datas'])
                    <br>
                    数据权限：
                    @foreach($row['datas'] as $modelkey => $model)
                        <label>{{$model['name']}}</label>
                        @foreach($model['permissions'] as $key => $mdl)
                            <label>
                                <input type="checkbox" 
                                    name="datas[{{$modelkey}}][row][]" 
                                    value="{{$key}}"
                                    @if(isset($role->datas[$modelkey]['row']) 
                                    && is_array($role->datas[$modelkey]['row'])
                                    && in_array($key, $role->datas[$modelkey]['row']))
                                    checked="checked"
                                    @endif
                                > 
                                {{$mdl['name']}}
                            </label>
                        @endforeach
                            {{-- <label>
                                <input type="radio" 
                                    name="datas[{{$modelkey}}][type]" 
                                    value="or"
                                    @if(isset($role->datas[$modelkey]["type"])&& $role->datas[$modelkey]["type"] == "or")
                                    checked="checked"
                                    @endif
                                > 
                                或
                            </label>
                            <label>
                                <input type="radio" 
                                    name="datas[{{$modelkey}}][type]" 
                                    value="and"
                                    @if(isset($role->datas[$modelkey]["type"])&& $role->datas[$modelkey]["type"] == "and")
                                    checked="checked"
                                    @endif
                                > 
                                并且
                            </label> --}}
                    @endforeach
                @endif
                </div>
                
            </div>    
        @endforeach
       
    </div>
</div>
 
 
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : '创建', ['class' => 'btn btn-primary']) !!}
    </div>
</div>