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
    <template v-for="field in fields">
        <div class="entry col-md-12 col-md-offset-1 form-inline">
            <input class="form-control" name="fields[]" type="text" placeholder="字段名称" required="true" v-model="field.field">
            <input class="form-control" name="field_descs[]" type="text" placeholder="字段描述" required="true" v-model="field.name">

            <select class="form-control" name="fields_type[]" v-model="field.type">
                <option value="string">string</option>
                <option value="char">char</option>
                <option value="varchar">varchar</option>
                <option value="text">text</option>
                <option value="mediumtext">mediumtext</option>
                <option value="longtext">longtext</option>
                <option value="json">json</option>
                <option value="jsonb">jsonb</option>
                <option value="binary">binary</option>
                <option value="password">password</option>
                <option value="email">email</option>
                <option value="number">number</option>
                <option value="integer">integer</option>
                <option value="bigint">bigint</option>
                <option value="mediumint">mediumint</option>
                <option value="tinyint">tinyint</option>
                <option value="smallint">smallint</option>
                <option value="decimal">decimal</option>
                <option value="double">double</option>
                <option value="float">float</option>
                <option value="date">date</option>
                <option value="datetime">datetime</option>
                <option value="timestamp">timestamp</option>
                <option value="time">time</option>
                <option value="boolean">boolean</option>
                <option value="enum">enum</option>
                <option value="select">select</option>
                <option value="file">file</option>
            </select>
            <label>必填</label>
            <select class="form-control" name="fields_required[]" v-model="field.required">
                <option value="0">No</option>
                <option value="1" selected="selected">Yes</option>
            </select>
            <label>搜索</label>
            <select class="form-control" name="fields_search[]" v-model="field.search">
                <option value="0">No</option>
                <option value="1" selected="selected">Yes</option>
            </select>
            <label>列表显示</label>
            <select class="form-control" name="fields_list[]" v-model="field.inlist">
                <option value="0">No</option>
                <option value="1" selected="selected">Yes</option>
            </select>
            <button class="btn inline btn-remove btn-danger" type="button">
                <span class="glyphicon glyphicon-minus"></span>
            </button>
            <button class="btn btn-success btn-copy inline" type="button">
                <span class="glyphicon glyphicon-copy"></span>
            </button>
        </div>
    </template>
    
</div>