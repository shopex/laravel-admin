<div class="row">
    <div class="col-md-6">
    <div class="form-group">
        <label for="crud_name" class="col-md-4 control-label" >模型名称:</label>
        <div class="col-md-8">
            <input type="text" name="model_title" class="form-control" id="model_title" placeholder="用户" required="true" v-model="model_title">
        </div>
    </div>

    <div class="form-group">
        <label for="crud_name" class="col-md-4 control-label" >模型:</label>
        <div class="col-md-3">
            <input type="text" name="crud_name" class="form-control" id="crud_name" placeholder="Posts" required="true" v-model="crud_name">
        </div>
        <div class="col-md-5">
            <label class="form-control">
                类型：
                {{ Form::select('model_type', ['entity' => '实体', 'logic' => '逻辑','transaction'=>'事务'], 'entity', ['v-model'=>'model_type'])
                }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="model_namespace" class="col-md-4 control-label">模型命名空间:</label>
        <div class="col-md-8">
            <input type="text" name="model_namespace" class="form-control" id="model_namespace" placeholder="Admin" 
            v-model="model_namespace"
            >
        </div>
    </div>
    <div class="form-group">
        <label for="controller_namespace" class="col-md-4 control-label">控制器命名空间:</label>
        <div class="col-md-8">
            <input type="text" name="controller_namespace" class="form-control" id="controller_namespace" placeholder="Admin" 
            v-model="controller_namespace"
            >
        </div>
    </div>
    <div class="form-group">
        <label for="route_group" class="col-md-4 control-label">路由组前缀:</label>
        <div class="col-md-8">
            <input type="text" name="route_group" class="form-control" id="route_group" placeholder="admin" v-model="route_group">
        </div>
    </div>
    <div class="form-group">
        <label for="view_path" class="col-md-4 control-label">视图路径:</label>
        <div class="col-md-8">
            <input type="text" name="view_path" class="form-control" id="view_path" placeholder="admin"  v-model="view_path">
        </div>
    </div>
    <div class="form-group">
        <label for="route" class="col-md-4 control-label">
        是否生成路由:
        </label>
        <div class="col-md-8">
            <label class="form-control">
                {{ Form::checkbox('is_route', 'on', $generator->is_route == 'yes' ?true:false) }}路由 
                {{ Form::checkbox('is_view', 'on', $generator->is_view == 'yes' ?true:false) }}视图 
                {{ Form::checkbox('is_controller', 'on', $generator->is_controller == 'yes' ?true:false) }}控制器 
                {{ Form::checkbox('is_migration', 'on', $generator->is_migration == 'yes' ?true:false) }}migration 
            </label>
        </div>
    </div>
     </div>
     <div class="col-md-6">
        <ul class="list-group">
            <li class="list-group-item" v-if="crud_name">
                模型 : <code>app/@{{model_namespace_show}}/@{{crud_name}}.php</code>
            </li>
            <li class="list-group-item" v-if="controller_namespace">
                控制器 : <code>app/Http/Controllers/@{{controller_namespace}}/@{{crud_name}}Controller.php</code>
            </li>
              <li  class="list-group-item"  v-if="route_group">
                路由 : <code>Route::resource('@{{route_group}}/@{{lower_crud_name}}', '@{{controller_namespace}}\\@{{crud_name}}Controller');</code>
            </li>
            <li class="list-group-item" v-if="view_path">
                视图 : <code>resources/views/@{{view_path_show}}/@{{lower_crud_name}}</code> 
            </li>
            <li class="list-group-item" v-if="crud_name">
                表名 : <code>@{{lower_crud_name}}</code>
            </li>
        </ul>  
     </div>                            
</div>