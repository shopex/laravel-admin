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

	<nav class="navbar navbar-default navbar-fixed-top" id="app-header">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <span class="navbar-brand">{{$app_name}}</span>
	    </div>
	    <div id="navbar" class="navbar-collapse collapse">
	      <ul class="nav navbar-nav hide-in-search">
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
	        <li class="dropdown">
	          <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	          {{$menu['label']}} <span class="caret"></span></a>
	          <ul class="dropdown-menu">
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

            @if (Auth::guest())
			<a href="{{ url('/login') }}" type="button" class="btn btn-default navbar-btn navbar-right external">登陆系统</a>
            @else			
	      	<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
				  <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="glyphicon glyphicon-th"></i>
				  </a>
				  <ul class="dropdown-menu app-sel">
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
				</li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>  
                    <ul class="dropdown-menu" role="menu">
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
                </li>
	      </ul>
	      @if (isset($searchbar) and $searchbar)
		  	<searchbar :items="searchbar"></searchbar>
	      @endif
        @endif
	  </div>
	</nav>

    <div class="container main-content">
		@yield('content')
    </div>

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