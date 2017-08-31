@extends('admin::layout')

@section('title', 'Generator')

@section('content')
<div class="finder-preview">
    <div class="finder-action-bar"></div>
    <div class="finder-header"></div>
</div>

    <div class="container">
        <div class="row">

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Generator</div>
                    <div class="panel-body">

                        <form class="form-horizontal" method="post" action="{{ url('/admin/generator') }}">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="crud_name" class="col-md-4 control-label">Crud Name:</label>
                                <div class="col-md-6">
                                    <input type="text" name="crud_name" class="form-control" id="crud_name" placeholder="Posts" required="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="controller_namespace" class="col-md-4 control-label">Controller Namespace:</label>
                                <div class="col-md-6">
                                    <input type="text" name="controller_namespace" class="form-control" id="controller_namespace" placeholder="Admin">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="route_group" class="col-md-4 control-label">Route Group Prefix:</label>
                                <div class="col-md-6">
                                    <input type="text" name="route_group" class="form-control" id="route_group" placeholder="admin">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="view_path" class="col-md-4 control-label">View Path:</label>
                                <div class="col-md-6">
                                    <input type="text" name="view_path" class="form-control" id="view_path" placeholder="admin">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="route" class="col-md-4 control-label">Want to add route?</label>
                                <div class="col-md-6">
                                    <select name="route" class="form-control" id="route">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group table-fields">
                                <h4 class="text-center">Table Fields:</h4><br>
                                <div class="entry col-md-10 col-md-offset-2 form-inline">
                                    <input class="form-control" name="fields[]" type="text" placeholder="field_name" required="true">
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

                                    <label>Required</label>
                                    <select name="fields_required[]" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <button class="btn btn-success btn-add inline" type="button">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </div>
                            </div>
                            <p class="help text-center">It will automatically assume form fields based on the migration field type.</p>
                            <br>
                            <div class="form-group">
                                <div class="col-md-offset-4 col-md-4">
                                    <button type="submit" class="btn btn-primary" name="generate">Generate</button>
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
        $( document ).ready(function() {
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
            });

        });


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
    </script>
@endsection