<!DOCTYPE html>
<html lang="en">
<head>
	<title> @yield('title','牛牛')</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/js/layui/css/layui.css">
    @yield('css')
	<script src="/js/layui/layui.js"></script>
	<script src="/js/vue.js"></script>
</head>
<body>
	<div class="content">
		@yield('content')
	</div>
</body>
</html>
