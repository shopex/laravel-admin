<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{$app_name}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <script src="{{ mix('/js/app.js') }}"></script>
  </head>
    <body>
        <div id="app">
            <desktop :menus="menus" :search="searchbar">
                <div slot="topbar">
                    <appsel appinfo_url="{{ route('admin-site-menus') }}"></appsel>
                    <span class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>  
                        <ul class="dropdown-menu-right dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('admin-profile') }}" target="_blank" class="external">
                                    帐号设置
                                </a>                        
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    退出系统
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </span>
                </div>

                <span slot="copyright">&copy; shopex 2017</span>
            </desktop>
        </div>
    </body>

    <script>
    @if (isset($searchbar) and $searchbar)
    window.searchbar = {!! json_encode($searchbar) !!};
    @else
    window.searchbar = [];
    @endif;

    window.menus = {!! json_encode((new Shopex\LubanAdmin\Permission\Menu())->show($app_menus)) !!};

    new Vue({
        el: '#app',
        data: {
            menus: menus,
            searchbar: searchbar
        }
    });
    </script>
</html>