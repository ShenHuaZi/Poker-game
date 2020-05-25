<?php
namespace App\Http\Controllers;
use GatewayClient\Gateway;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class IndexController extends Controller
{
    public function __construct()
    {
        require_once '../vendor/workerman/GatewayClient/Gateway.php';
        Gateway::$registerAddress = '127.0.0.1:1238';
    }

    //首页
    public function index(Request $request){
        return view('index.index',['user'=>$request->session()->get('user')]);
    }
    //设置游戏名称
    public function setGameName(Request $request){
        $name = strip_tags($request->input('name'));
        $userID = $request->session()->get('user.id');
        $res = User::where('id','=',$userID)->update(['game_name'=>$name]);
        if($res){
            $request->session()->put('user.game_name',$name);
            return ['code'=>1,'msg'=>'设置名称成功'];
        }

        return ['code'=>0,'msg'=>'设置名称失败'];
    }
    //设置用户clinet_ID
    public function setClinetID(Request $request){
        $client_id = $request->input('client_id');
        //clinet_id和用户id进行绑定,以便后面推送
        Gateway::bindUid($client_id, $request->session()->get('user.id'));
        // 加入房间1群组
        Gateway::joinGroup($client_id, 1);
        $msg = ['type'=>'message','msg'=>$request->session()->get('user.game_name').'加入游戏 ─=≡Σ(((つ•̀ω•́)つ'];
        if(Gateway::isUidOnline($request->session()->get('user.id'))){
          $msg = ['type'=>'message','msg'=>$request->session()->get('user.game_name').'重新加入游戏 (ノ▽｀*)ノ[你回来啦♪]=з=з=з'];
        }
        Gateway::sendToGroup(1,json_encode($msg));
    }
}
