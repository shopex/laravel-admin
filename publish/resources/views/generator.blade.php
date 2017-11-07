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
                            {!! Form::hidden('id', $generator->id, ['class' => 'form-control', 'required' => 'required']) !!}
                            @include('admin::generator-header')
                            @include('admin::generator-body')
                            <br>
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-4">
                                    <button type="submit" class="btn btn-primary" name="generate">生成</button>
                                    <input type="checkbox" name="staging">暂存
                                    <input type="checkbox" name="regen">重新生成
                                    <input type="checkbox" name="remove_file">移除文件
                                </div>
                            </div>
                        </form>

                    </div>
                    @include('admin::generator-files')
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
                crud_name: '{{ $generator->crud_name }}',
                controller_namespace:'{{ $generator->controller_namespace }}',
                model_type:'{{ $generator->model_type }}',
                route_group:'{{ $generator->route_group }}',
                view_path:'{{ $generator->view_path }}',
                model_title:'{{ $generator->view_path }}'
              },
              computed: {
                 lower_crud_name:function(){
                    return this.crud_name.toLowerCase();
                 },
                 model_namespace:function(){
                    return "Models\\"+this.model_type.toLowerCase().replace(/( |^)[a-z]/g, (L) => L.toUpperCase());
                 },
                 model_namespace_show:function(){
                    return this.model_namespace.replace('\\','/');
                 },
                 view_path_show:function(){
                    return this.view_path.replace(/\./g,'/');
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