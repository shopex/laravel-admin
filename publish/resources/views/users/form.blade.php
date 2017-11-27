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
<div  id="roles">
    <div class="form-group{{ $errors->has('roles') ? ' has-error' : ''}}">
        {!! Form::label('role', '角色: ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            <objectinput type="role"  value="{{$roles}}" multiple="multiple" name="roles"  v-on:input_value="rolesChange"></objectinput>
        </div>
    </div>
    <div class="form-group{{ $errors->has('roles') ? ' has-error' : ''}}" >
        {!! Form::label('role', '数据权限: ', ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6" >
            <div class="row" v-for="data in roles_data">
                <div class="form-group{{ $errors->has('roles') ? ' has-error' : ''}}">
                        <label for="role" class="col-md-3 " v-text="data.name"></label>
                        <div class="col-md-6">
                            <div v-for="(rows,model_name) in data.model">
                                <div v-for="(row,i) in rows">
                                    <span v-text="row.name"></span>
                                    <objectinput 
                                        :type="row.object_name"  
                                        :value="row.value" 
                                        multiple="multiple" 
                                        :name=data_id_key+"["+data.role_id+"]["+model_name+"]["+row.field+"]"
                                        ></objectinput>
                                </div>
                            </div>

                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
<script>
$(function($){
    new Vue({
        el: '#roles',
        data:{
            data_id_key: "role_datas",
            user_id:{{isset($user->id)?$user->id:0}},
            roles_data:[]
        },
        methods: {
            rolesChange : function (values) {
                var than = this;
                if (values.length>0) {
                    var v = {}
                    v.user_id = this.user_id;
                    v.roles = values;
                    $.ajax({
                        url: '{{route('users.roles.data')}}',
                        method:"POST",
                        data: v,
                        success: function(rst){

                            if (rst.status == 'succ') {
                                var Objectinput = Vue.component("objectinput");
                                than.roles_data = rst.data;
                                
                            }
                        }
                    })
                }else{
                    than.roles_data = [];
                }
            }
        }
    })
})
</script>