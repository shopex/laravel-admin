@extends('admin::layout')

@section('title', $finder->title() )

@section('action-bar')
<div class="finder-action-bar"></div>
@endsection

@if (array_key_exists('finder-footer', View::getSections()))
@section('footer')
@yield('finder-footer')
@endsection
@endif

@section('header')
@if (array_key_exists('finder-header', View::getSections()))
	@yield('finder-header')
@endif

<div class="finder-header"></div>
@endsection

@section('content')
<div class="finder-content"></div>
@endsection

@section('scripts')
<script>
$(function(){
	var Finder = Vue.component("finder");
	var component = new Finder({
		data: {
			finder: {!! $finder->json() !!}
		}
	}).$mount();

	$('.main-content .finder-action-bar').replaceWith(component.$refs.actionbar);
	$('.main-content .finder-header').replaceWith(component.$refs.header);
	$('.main-content .finder-content').replaceWith(component.$refs.content);
})
</script>

@yield('scripts')
@endsection