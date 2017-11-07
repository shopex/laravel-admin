<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ mix('/css/desktop.css') }}">
    <script src="{{ mix('/js/desktop.js') }}"></script>
  </head>
	<body>
		<div id="app">
			<desktop></desktop>
		</div>
    </body>

    <script>
    	new Vue({
    		el: '#app'
    	});
    </script>
</html>