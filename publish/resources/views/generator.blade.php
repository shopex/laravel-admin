@extends('admin::layout')

@section('title', '代码生成器')

@section('content')
    <div class="finder-preview">
        <div class="finder-action-bar"></div>
        <div class="finder-header"></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" id="form" action="{{ url('/admin/generator') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="crud_name" class="col-md-4 control-label" >模型名称:</label>
                                    <div class="col-md-8">
                                        <input type="text" name="model_title" class="form-control" id="model_title" placeholder="用户" required="true" v-model="model_title">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="crud_name" class="col-md-4 control-label" >模型名称:</label>
                                    <div class="col-md-8">
                                        <input type="text" name="crud_name" class="form-control" id="crud_name" placeholder="Posts" required="true" v-model="crud_name">
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
                                    <label for="route" class="col-md-4 control-label">是否生成路由:</label>
                                    <div class="col-md-8">
                                        <select name="route" class="form-control" id="route">
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                 </div>
                                 <div class="col-md-6">
                                    <ul class="list-group">
                                        <li class="list-group-item" v-if="crud_name">
                                            模型 : <code>app/@{{crud_name}}.php</code>
                                        </li>
                                        <li class="list-group-item" v-if="controller_namespace">
                                            控制器 : <code>app/Http/Controllers/@{{controller_namespace}}/@{{crud_name}}Controller.php</code>
                                        </li>
                                          <li  class="list-group-item"  v-if="route_group">
                                            路由 : <code>Route::resource('@{{route_group}}/@{{crud_name}}', '@{{controller_namespace}}\\@{{crud_name}}Controller');</code>
                                        </li>
                                        <li class="list-group-item" v-if="view_path">
                                            视图 : <code>resources/views/@{{view_path}}/@{{lower_crud_name}}</code> 
                                        </li>
                                        <li class="list-group-item" v-if="crud_name">
                                            表名:<code>@{{crud_name}}s</code>
                                        </li>

                                    </ul>
                                    
                                 </div>
                            
                            </div>
                            <div class="form-group table-fields">
                                <h4 class="text-center">表字段 <a target="__black" href="https://shimo.im/doc/RWHF4vw1eC87J0M3?r=MQLEYZ">建表规约</a></h4><br>
                                <div class="entry col-md-12 col-md-offset-1 form-inline">
                                    <input class="form-control" name="fields[]" type="text" placeholder="字段名称" required="true">
                                    <input class="form-control" name="field_descs[]" type="text" placeholder="字段描述" required="true">
                                    <select name="fields_type[]" class="form-control">
                                        <option value="string">string</option>
                                        <option value="char">char</option>
                                        <option value="varchar">varchar</option>
                                        <option value="password">password</option>
                                        <option value="email">email</option>
                                        <option value="date">date</option>
                                        <option value="datetime">datetime</option>
                                        <option value="time">time</option>
                                        <option value="timestamp">timestamp</option>
                                        <option value="text">text</option>
                                        <option value="mediumtext">mediumtext</option>
                                        <option value="longtext">longtext</option>
                                        <option value="json">json</option>
                                        <option value="jsonb">jsonb</option>
                                        <option value="binary">binary</option>
                                        <option value="number">number</option>
                                        <option value="integer">integer</option>
                                        <option value="bigint">bigint</option>
                                        <option value="mediumint">mediumint</option>
                                        <option value="tinyint">tinyint</option>
                                        <option value="smallint">smallint</option>
                                        <option value="boolean">boolean</option>
                                        <option value="decimal">decimal</option>
                                        <option value="double">double</option>
                                        <option value="float">float</option>
                                    </select>

                                    <label>必填</label>
                                    <select name="fields_required[]" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                    <label>搜索</label>
                                    <select name="fields_search[]" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                    <label>列表显示</label>
                                    <select name="fields_list[]" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                    <button class="btn btn-success btn-add inline" type="button">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                    <button class="btn btn-success btn-copy inline" type="button">
                                        <span class="glyphicon glyphicon-copy"></span>
                                    </button>
                                </div>
                            </div>
                            <p class="help text-center">It will automatically assume form fields based on the migration field type.</p>
                            <br>
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-4">
                                    <button type="submit" class="btn btn-primary" name="generate">生成</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

        (function (original) {  
            jQuery.fn.clone = function (all) {  
                    var result = original.apply(this, arguments);
                    if (all) {
                    var my_textareas = this.find('textarea').add(this.filter('textarea')),  
                    result_textareas = result.find('textarea').add(result.filter('textarea')),  
                    my_selects = this.find('select').add(this.filter('select')),  
                    result_selects = result.find('select').add(result.filter('select'));  
                       
                    for (var i = 0, l = my_textareas.length; i < l; ++i) $(result_textareas[i]).val($(my_textareas[i]).val());  
                    for (var i = 0, l = my_selects.length; i < l; ++i) result_selects[i].selectedIndex = my_selects[i].selectedIndex;  
                    }
                    return result;  
            };  
        }) (jQuery.fn.clone);  

        $( document ).ready(function() {
            var app = new Vue({
              el: '#form',
              data: {
                crud_name: '',
                controller_namespace:'',
                route_group:'',
                view_path:'',
                model_title:''
              },
              computed: {
                 lower_crud_name:function(){
                    return this.crud_name.toLowerCase();
                 }
              }
            })
            $(document).on('click', '.btn-add', function(e) {
                e.preventDefault();
                var tableFields = $('.table-fields'),
                    currentEntry = $(this).parents('.entry:first'),
                    newEntry = $(currentEntry.clone()).appendTo(tableFields);
                newEntry.find('input').val('');
                tableFields.find('.entry:not(:last) .btn-add')
                    .removeClass('btn-add').addClass('btn-remove')
                    .removeClass('btn-success').addClass('btn-danger')
                    .html('<span class="glyphicon glyphicon-minus"></span>');
            }).on('click', '.btn-remove', function(e) {
                $(this).parents('.entry:first').remove();
                e.preventDefault();
                return false;
            }).on('click', '.btn-copy', function(e) {
                e.preventDefault();
                var tableFields = $('.table-fields');
                    currentEntry = $(this).parents('.entry:first');
                    newEntry = $(currentEntry.clone(true)).appendTo(tableFields);
                tableFields.find('.entry:not(:last) .btn-add')
                    .removeClass('btn-add').addClass('btn-remove')
                    .removeClass('btn-success').addClass('btn-danger')
                    .html('<span class="glyphicon glyphicon-minus"></span>');
            });
        });
        /*
        $(function(){
            var Finder = Vue.component("finder");
            var component = new Finder({
                data: {
                    finder: {
                        "baseUrl": "\/home",
                        "title": "标题",
                        "tabs": [
                            {
                                "label": "低优先级"
                            },
                            {
                                "label": "高优先级"
                            },
                            {
                                "label": "考试不及格"
                            }
                        ],
                        "cols": [
                            {
                                "key": "name",
                                "label": "姓名",
                                "sortAble": null,
                                "default": null,
                                "size": 2,
                                "className": null,
                                "html": false
                            },
                            {
                                "key": "name",
                                "label": "姓名",
                                "sortAble": null,
                                "default": null,
                                "size": 2,
                                "className": null,
                                "html": true
                            },
                            {
                                "key": "email",
                                "label": "邮箱",
                                "sortAble": null,
                                "default": null,
                                "size": 6,
                                "className": null,
                                "html": false
                            },
                            {
                                "key": "created_at",
                                "label": "创建时间",
                                "sortAble": null,
                                "default": null,
                                "size": 2,
                                "className": null,
                                "html": false
                            }
                        ],
                        "sorts": [
                            {
                                "label": "按修改时间倒排"
                            },
                            {
                                "label": "按修改时间正排"
                            }
                        ],
                        "infoPanels": [
                            {
                                "label": "家庭地址"
                            }
                        ],
                        "actions": [
                            {
                                "label": "旋转",
                                "url": null,
                                "target": "_blank"
                            },
                            {
                                "label": "跳跃",
                                "url": null,
                                "target": "#modal-normal"
                            },
                            {
                                "label": "奔跑",
                                "url": null,
                                "target": "#modal-small"
                            },
                            {
                                "label": "卧倒",
                                "url": null,
                                "target": "#modal-large"
                            },
                            {
                                "label": "打滚",
                                "url": null,
                                "target": null
                            }
                        ],
                        "searchs": [
                            {
                                "label": "姓名",
                                "mode": "=",
                                "value": null,
                                "type": "string"
                            },
                            {
                                "label": "邮箱",
                                "mode": "=",
                                "value": null,
                                "type": "string"
                            },
                            {
                                "label": "年龄",
                                "mode": "=",
                                "value": null,
                                "type": "number"
                            },
                            {
                                "label": "邮箱",
                                "mode": "=",
                                "value": null,
                                "type": {}
                            }
                        ],
                        "batchActions": [
                            {
                                "label": "批量操作1",
                                "url": null,
                                "target": "_blank"
                            },
                            {
                                "label": "批量操作2",
                                "url": null,
                                "target": null
                            }
                        ],
                        "data": {
                            "count": 2,
                            "currentPage": 1,
                            "hasMorePages": false,
                            "lastPage": 1,
                            "perPage": 20,
                            "total": 2,
                            "items": [
                                {
                                    "$id": 2,
                                    "0": "WANGLEI",
                                    "1": "<a href=\"asdf\">asdfs<\/a>",
                                    "2": "flaboy.cn@gmail.com",
                                    "3": {
                                        "date": "2017-08-21 06:18:11.000000",
                                        "timezone_type": 3,
                                        "timezone": "UTC"
                                    }
                                },
                                {
                                    "$id": 1,
                                    "0": "WANGLEI",
                                    "1": "<a href=\"asdf\">asdfs<\/a>",
                                    "2": "flaboy.cn@gmail.com",
                                    "3": {
                                        "date": "2017-08-18 02:51:10.000000",
                                        "timezone_type": 3,
                                        "timezone": "UTC"
                                    }
                                }
                            ]
                        },
                        "tab_id": 0,
                        "sort_id": 0
                    }
                }
            }).$mount();

            $('.finder-preview .finder-action-bar').replaceWith(component.$refs.actionbar);
            $('.finder-preview .finder-header').replaceWith(component.$refs.header);

            component.finder.tabs = [
                    {"label": "aasdf"},
                    {"label": "bbbbb"}
            ];
        })
        */
    </script>
@endsection