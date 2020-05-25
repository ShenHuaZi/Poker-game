<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use mysql_xdevapi\Exception;

//登录类
class LoginController extends Controller
{
    //登录页面
    public function login(){
        return view('login.login');
    }

    //登录//注册验证
    public function validateLogon(Request $request){
        try{
            $userData = json_decode($request->input('data'),true);
            $user = User::where('username',$userData['username'])->first()->toArray();
            switch ($user){
                case true:
                    if($user['password'] == $userData['password']){
                        $code = 1;$msg = '登录成功ヾ(◍°∇°◍)ﾉﾞ';
                        $request->session()->put('user',$user);
                    }else{
                        $code = 0;$msg = '密码错误！┭┮﹏┭┮';
                    }
                    break;
                case false:
                    User::insert([
                        'username'=>$userData['username'],
                        'password'=>$userData['password'],
                        'created_at'=>date('Y-m-d H:i:s',time())
                    ]);
                    $code = 1;$msg = '注册成功ヾ(◍°∇°◍)ﾉﾞ';
                    session('user',$userData);
                    break;
                default:
                    $code = 0;$msg = '服务器出现问题了呢！┭┮﹏┭┮';
            }
            return ['code'=>$code,'msg'=>$msg];
        }catch (Exception $e){
            return ['code'=>0,'msg'=>$e->getMessage()];
        }
    }
}
