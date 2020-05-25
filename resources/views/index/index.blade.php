{{-- 继承公共文件 --}}
@extends('common.common')

@section('title','首页')
@section('css')
<link rel="stylesheet" type="text/css" href="/css/index.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection
{{-- 编写主体 --}}
@section('content')


<div id="game">
	<div id="time">@{{times}}</div>
	<ul>
		<div style="position: absolute;bottom:30px;" >
			<div class="bottomDiv" v-for="(item,index) in moneyList"  @click="select(item,$event)" v-show="money.money >= item" >
				@{{item}}
			</div>
		</div>
		<div style="position: absolute;right:0px;bottom:30px;">
			<div class="bottomDivTwo" >
				游戏币：@{{money.money}}
			</div>
		</div>
		<li @click="Bet('li1')"><span class='linum'>1</span><span v-text="all.li1" v-show="all.li1"></span></li>
		<li @click="Bet('li2')"><span class='linum'>2</span><span v-text="all.li2" v-show="all.li2"></span></li>
		<li @click="Bet('li3')"><span class='linum'>3</span><span v-text="all.li3" v-show="all.li3"></span></li>
		<li @click="Bet('li4')"><span class='linum'>4</span><span v-text="all.li4" v-show="all.li4"></span></li>
	</ul>
</div>


<script type="text/javascript">

layui.use('form', function(){
  var form = layui.form,$ = layui.jquery;
  var gameName = "{{ $user['game_name'] }}"
  //定义下单金额
  var moneyList = [100,1000,3000,5000,8000,10000];
  var setInt={};
  vue = new Vue({
  		el:'#game',
  		data:{
  			ws:'ws://127.0.0.1:8282',
  			gameName:gameName,
  			all:{
  				li1:0,
  				li2:0,
  				li3:0,
  				li4:0
  			},
  			money:{
  				money:1500,
  			},
  			betNumber:0,
  			moneyList:moneyList,
  			times:0,
  		},
  		mounted:function(){
  			if(this.gameName == 'undefined' || !this.gameName)
  				this.setGameName();
  			this.init();
  		},
  		methods:{
  			init: function () {
                // 实例化socket
                this.socket = new WebSocket(this.ws)
                // 监听socket错误信息
                this.socket.onerror = this.error
                // 监听socket消息
                this.socket.onmessage = this.getMessage
	        },
	        error: function () {
	            console.log("连接错误")
	        },
	        getMessage: function (msg) {
	        	var data = eval("("+msg.data+")");
	        	var type = data.type || '';
	        	switch(type){
	        		case 'login':
	        			this.setClinetID(data.client_id);
	        			break;
	        		case 'message':
	        			layer.msg(data.msg);
	        			break;	
	        		case 'time':
	        			console.log(111);
	        			this.forTimeout();
	        			break;	
	        	}
	            
	        },
	        send: function () {
	            this.socket.send(params)
	        },
	        close: function () {
	            console.log("socket已经关闭")
	        },
	        setClinetID:function(client_id){
	        	$.post("{{url('/index/setClinetID')}}",{client_id:client_id,_token:'{{csrf_token()}}'},function(){})
	        },
	        setGameName:function(){
  				layer.prompt({title:"设置游戏名称",btn:'确定',closeBtn:0},function(value, index, elem){
  					if(value.replace(/\s+/g,"")  == 'undefined' || !value.replace(/\s+/g,"")){
  						layer.msg('游戏名称不能为空');return false;
  					}
				    $.post("{{url('/index/setGameName')}}",{name:value,_token:'{{csrf_token()}}'},function(res){
				    	if(res.code){
				    		layer.msg(res.msg,function(){
				    			this.gameName = value;
				    			layer.close(index);
				    		});
				    	}else{
				    		layer.msg(res.msg);
				    	}
				    })
				});
  			},
  			Bet:function(key){
  				if(this.betNumber == 0){layer.msg('请选择下注点数');return false;}
  				if(this.money.money - this.betNumber < 0)return false;
  				this.all[key] = this.all[key] + this.betNumber;
  				this.money.money = this.money.money - this.betNumber;
  				
  			},
  			select:function(number,event){
  				var em = event.currentTarget;
  				$('.bottomDiv').css('background-color','#009688');
  				$(em).css('background-color','#1E9FFF');
  				this.betNumber = number;
  			},
  			forTimeout:function(){
  				//计时方法，到事件就结束下注
  				this.times = this.times + 1;
  				
  			},
  		},
  		watch:{
  			times(val){
  				// 监听times计时变量，如果到了15秒就请求后台
  				if(val == 15)
  					clearInterval(setInt);
  			}
  		}
  })
});
// ws = new WebSocket("ws://127.0.0.1:8282");
// ws.onmessage = function(e){
//     // json数据转换成js对象
//     var data = eval("("+e.data+")");
//     var type = data.type || '';
//     switch(type){
//     	case 'login':
//     		console.log(data);	
//     		break;
//     	default :
//     		console.log(data);	
//     }
//     console.log(vue.times);
// };
</script>
@endsection
