@extends('admin::layout')

@section('title', $finder->title() )

@section('header')
<div class="finder-header">
	<div class="row api-top-title">
		@foreach ($finder->cols() as $key=>$col)
			<div class="col-md-{{$col->size}} {{$col->className}}">
				{{$col->label}}
			</div>
		@endforeach
	</div>
</div>
@endsection

@section('action-bar')
	@foreach ($finder->actions() as $action)
		{!! Form::open(['method'=>$action->httpMethod, 'style'=>'display:inline']) !!}
		<input type="hidden" name="method" value="{{$action->method}}" />
		<button type="submit" class="btn btn-default">{{$action->label}}</button>
		{!! Form::close() !!}
	@endforeach
@endsection

@section('content')
<div class="finder-body">
@foreach ($finder->items() as $item)
	<div class="row api-top-title">
		@foreach ($finder->cols() as $key=>$col)
			<div class="col-md-{{$col->size}} {{$col->className}}">
				@if($col->modifier)
				{{ call_user_func_array($col->modifier, [$item->$key, $item]) }}
				@else
				{{$item->$key}}
				@endif
			</div>
		@endforeach
	</div>
@endforeach
</div>
@endsection