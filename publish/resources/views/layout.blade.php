<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title>@yield('title') - {{$app_name}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">    
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <script src="{{ mix('/js/app.js') }}"></script>
  </head>

<body>
	<div class="admin-page">
		<div class="admin-header" id="app-header">

			<div class="admin-header-title">
				<img class="appbanner" src="{{ url('/appbanner.png') }}" />
			</div>

			@if (Auth::guest())
			<div class="admin-header-content">
				<div>
					<a href="{{ url('/login') }}" type="button" class="btn btn-default external">登陆系统</a>
				</div>
			</div>
			@else

			<div class="admin-header-searchbar">
		      <input type="text" />

			  @if (false and isset($searchbar) and $searchbar)
			  	<searchbar :items="searchbar"></searchbar>
		      @endif      
			</div>

			<div class="admin-header-content">

				<span class="dropdown">
				  <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="glyphicon glyphicon-th"></i>
				  </a>
				  <ul class="dropdown-menu dropdown-menu-right app-sel">
				    <li>
					    <a href="#">
					    	<img src="https://git.shopex.cn/img/favicon.png" width="48px" />
					    	<div>CRM</div>
					    </a>
				    </li>
				    <li>
					    <a href="">
					    	<img src="https://git.shopex.cn/img/favicon.png" width="48px" />
					    	<div>CRM</div>
					    </a>
				    </li>
				    <li>
					    <a href="">
					    	<img src="https://git.shopex.cn/img/favicon.png" width="48px" />
					    	<div>CRM</div>
					    </a>
				    </li>
				    <li>
					    <a href="">
					    	<img src="https://git.shopex.cn/img/favicon.png" width="48px" />
					    	<div>CRM</div>
					    </a>
				    </li>
				  </ul>
				</span>

                <span class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>  
                    <ul class="dropdown-menu-right dropdown-menu" role="menu">
                        <li>
		                    <a href="{{ Luban::config()->get('sso_url') }}/profile" target="_blank" class="external">
		                        帐号设置
		                    </a>                        
                            <a href="{{ url('/logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                退出系统
                            </a>

                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </span>
			</div>
			@endif

		</div>

    	<div class="admin-main">
	    	<div class="admin-sidebar">

		      <ul>
		      	@foreach ($app_menus as $menu)

		  		@if (Auth::guest())
		  			@if (isset($menu['user-only']) and $menu['user-only'])
		  				@continue;
		  			@endif
		  		@else
		  			@if (isset($menu['guest-only']) and $menu['guest-only'])
		  				@continue;
		  			@endif
		  		@endif

		      	@if (isset($menu['items']) and count($menu['items']) > 0)
		        <li>
		          <a>{{$menu['label']}} <span class="caret"></span></a>
		          <ul>
		          	@foreach ($menu['items'] as $item)
			          	@if (isset($item['label']))
				          	<li>
				          	<a href="{{ url($item['link']) }}">
				          	@if (isset($item['icon']))
				          	<i class="glyphicon {{ $item['icon'] }}"></i>
				          	@else
				          	<i style="display:inline-block;width:1em"></i>
				          	@endif
				          	{{$item['label']}}
				          	</a>
				          	</li>
			          	@else
				          	<li role="separator" class="divider"></li>
			          	@endif
		          	@endforeach
		          </ul>
		        </li>
		      	@else	        
				<li><a href="{{ url($menu['link']) }}">{{$menu['label']}}</a></li>	        
		        @endif
				@endforeach
		      </ul>

			</div>

			<div class="main-content">
				<div class="main-header">
					<div class="main-header-basic">
						<ol class="breadcrumb">
						  <li class="active">@yield('title')</li>
						</ol>
						<div class="main-header-action">
							@yield('action-bar')
						</div>
					</div>
					@if (array_key_exists('header', View::getSections()))
					<div class="main-header-custom">@yield('header')</div>
					@endif
				</div>

				<div class="main-body">@yield('content')</div>
				@if (array_key_exists('footer', View::getSections()))
				<div class="main-footer">@yield('footer')</div>
				@endif
			</div>
	    </div>

    </div>

    <div class="main-script">
    	@yield('scripts')
    </div>

    <div id="indicator" style="display: none">
    	<div class="indicator-container">
    		<div class="indicator-process"></div>
    	</div>
    </div>

    </body>

    <script>
    @if (isset($searchbar) and $searchbar)
    var searchbar = {!! json_encode($searchbar) !!};
    @else
    var searchbar = [];
    @endif;

  	var app = new Vue({ 
  		el: '#app-header',
  		data: {
  			searchbar: searchbar
  		}
  	});
    </script>
</html>