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
            <desktop :menus="menus" :search="searchbar"></desktop>
        </div>
    </body>

    <script>
    @if (isset($searchbar) and $searchbar)
    window.searchbar = {!! json_encode($searchbar) !!};
    @else
    window.searchbar = [];
    @endif;

    window.appinfo_url = "{{ route('admin-site-menus') }}";
    window.menus = {!! json_encode($app_menus) !!};

    new Vue({
        el: '#app',
        data: {
            menus: menus,
            searchbar: searchbar
        }
    });
    </script>
</html>