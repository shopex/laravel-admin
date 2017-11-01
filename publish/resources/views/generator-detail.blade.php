<div class="table-responsive">
    @php
        $fields_array = json_decode($generator->fields ,1);
    @endphp
    <table class="table table-borderless">
        <tbody>
            <tr>
                @if(is_array($files_array))
                <tr>
                    <th> 文件 </th>
                    <td> 
                        <table class="table table-borderless">
                            <tr>
                                <td>类型</td>
                                <td>路径</td>
                                <td>HASH</td>
                                <td>当前HASH</td>
                            </tr>
                            <tr>
                                <td>controller</td>
                                <td><code>{{ $files_array['controller']['path'] }}</code></code></td>
                                <td><code>{{ $files_array['controller']['hash'] }}</code></td>
                                <td><code>{{ $files_array['controller']['now_hash'] }}</code></td>
                            </tr>
                            <tr>
                                <td>migration</td>
                                <td><code>{{ $files_array['migration']['path'] }}</code></td>
                                <td><code>{{ $files_array['migration']['hash'] }}</code></td>
                                <td><code>{{ $files_array['migration']['now_hash'] }}</td>
                            </tr>
                            <tr>
                                <td>model</td>
                                <td><code>{{ $files_array['model']['path'] }}</code></td>
                                <td><code>{{ $files_array['model']['hash'] }}</code></td>
                                <td><code>{{ $files_array['model']['now_hash'] }}</code></td>
                            </tr>
                            @foreach($files_array['view'] as $k=>$v)
                                <tr>
                                <td>view</td>
                                <td><code>{{ $v['path'] }}</code></td>
                                <td><code>{{ $v['hash'] }}</code></td>
                                <td><code>{{ $v['now_hash'] }}</code></td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
                @endif
                <tr>
                    <th> 字段 </th>
                    <td>
                        <table class="table table-borderless">
                            <tr>
                                <td>编码</td>
                                <td>名称</td>
                                <td>类型</td>
                                <td>是否搜索</td>
                                <td>是否必填</td>
                                <td>是否在列表</td>
                            </tr>
                            
                            @foreach($fields_array['fields'] as $k=>$v)
                                <tr>
                                    <td>{{ $v }}</td>
                                    <td>{{ $fields_array['field_descs'][$k] }}</td>
                                    <td>{{ $fields_array['fields_type'][$k] }}</td>
                                    <td>{{ $fields_array['fields_required'][$k] }}</td>
                                    <td>{{ $fields_array['fields_search'][$k] }}</td>
                                    <td>{{ $fields_array['fields_list'][$k] }}</td>
                                </tr>
                            @endforeach
                        </table> 

                    </td>
                </tr>
        </tbody>
    </table>
</div>
