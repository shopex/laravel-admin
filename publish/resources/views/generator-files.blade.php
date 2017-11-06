@if(is_array($files_array) && $files_array)
<div class="panel-body">
    <table class="table table-borderless">
        <tr>
            <td>类型</td>
            <td>路径</td>
            <td>HASH</td>
            <td>当前HASH</td>
        </tr>
        <tr @if($files_array['controller']['hash'] == $files_array['controller']['now_hash']) class="success" @else class="danger"  @endif>
            <td>controller</td>
            <td><code>{{ $files_array['controller']['path'] }}</code></code></td>
            <td><code>{{ $files_array['controller']['hash'] }}</code></td>
            <td><code>{{ $files_array['controller']['now_hash'] }}</code></td>
        </tr>
        <tr @if($files_array['migration']['hash'] == $files_array['migration']['now_hash']) class="success" @else class="danger" @endif>
            <td>migration</td>
            <td><code>{{ $files_array['migration']['path'] }}</code></td>
            <td><code>{{ $files_array['migration']['hash'] }}</code></td>
            <td><code>{{ $files_array['migration']['hash'] }}</code></td>
        </tr>
        <tr @if($files_array['model']['hash'] == $files_array['model']['now_hash']) class="success" @else class="danger" @endif>
            <td>model</td>
            <td><code>{{ $files_array['model']['path'] }}</code></td>
            <td><code>{{ $files_array['model']['hash'] }}</code></td>
            <td><code>{{ $files_array['model']['now_hash'] }}</code></td>
        </tr>
        @foreach($files_array['view'] as $k=>$v)
            <tr @if($v['hash'] == $v['now_hash']) class="success" @else class="danger" @endif>
            <td>view</td>
            <td><code>{{ $v['path'] }}</code></td>
            <td><code>{{ $v['hash'] }}</code></td>
            <td><code>{{ $v['now_hash'] }}</code></td>
            </tr>
        @endforeach
    </table>
</div>
@endif