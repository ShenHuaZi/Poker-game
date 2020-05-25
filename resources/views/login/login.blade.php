{{-- 继承公共文件 --}}
@extends('common.common')
@section('title','登录')

@section('css')
<link rel="stylesheet" type="text/css" href="/css/login.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

{{-- 编写主体 --}}
@section('content')

<div class="loginDiv layui-container">
	<form class="layui-form" action=""> 
		<div class="layui-form-item">
		    <div class="layui-input-block">
		      <input type="text" name="username" required  lay-verify="required" placeholder="用户名" autocomplete="off" class="layui-input">
		    </div>
		</div>
		<div class="layui-form-item">
		    <div class="layui-input-block">
		      <input type="text" name="password" required  lay-verify="required" placeholder="密码" autocomplete="off" class="layui-input">
		    </div>
		</div>
		<div class="layui-form-item" style="text-align:center;">
		    <div class="layui-input-block">
		      <button class="layui-btn" lay-submit lay-filter="formDemo">登录/注册</button>
		    </div>
		</div>
	</form>
</div>




<script type="text/javascript">
layui.use('form', function(){
  var form = layui.form,$ = layui.jquery;
  
	form.on('submit(formDemo)', function(data){
		$.ajax({
			type:'POST',
			url:"{{url('/login/validate')}}",
			data:'data='+JSON.stringify(data.field)+'&_token='+'{{csrf_token()}}',
			success:function(res){
				console.log(res);
				if(res.code){
					layer.msg(res.msg,{icon:1},function(){
						window.location.href="/";
					})
				}else{
					layer.msg(res.msg,{icon:2})
				}
			},
			error:function(e){
				if(e.status == 429)
					layer.msg('点击次数过多');
				
			}
		})
		
		return false;
	});
});
</script>
@endsection
