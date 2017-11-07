<div class="form-group table-fields">
    <h4 class="text-center">表字段 <a target="__black" href="https://shimo.im/doc/RWHF4vw1eC87J0M3?r=MQLEYZ">建表规约</a></h4><br>
    @foreach ($generator->fields['fields'] as $key=>$value)
        <div class="entry col-md-12 col-md-offset-1 form-inline">
            <input class="form-control" name="fields[]" type="text" placeholder="字段名称" required="true" value="{{ $value }}">
            <input class="form-control" name="field_descs[]" type="text" placeholder="字段描述" required="true" value="{{ $generator->fields['field_descs'][$key] }}">

            {{ Form::select('fields_type[]', $curdtype,$generator->fields['fields_type'][$key] , ['class'=>'form-control'])
                }}
            <label>必填</label>
            {{ Form::select('fields_required[]', ['0' => 'No', '1' => 'Yes'],$generator->fields['fields_required'][$key] , ['class'=>'form-control'])
                }}
            <label>搜索</label>
            {{ Form::select('fields_search[]', ['0' => 'No', '1' => 'Yes'],$generator->fields['fields_search'][$key] , ['class'=>'form-control'])
                }}
            <label>列表显示</label>
            {{ Form::select('fields_list[]', ['0' => 'No', '1' => 'Yes'],$generator->fields['fields_list'][$key] , ['class'=>'form-control'])
                }}
            <button class="btn btn-success btn-add inline" type="button">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
            <button class="btn btn-success btn-copy inline" type="button">
                <span class="glyphicon glyphicon-copy"></span>
            </button>
        </div>
    @endforeach
</div>