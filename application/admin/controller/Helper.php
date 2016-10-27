<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\User;
class Helper extends Controller
{
    public function login()
    {
        if(Request::instance()->isPost())
        {
            $data['username'] = input('post.username');
            $data['password'] = input('post.password');
            $data['captcha'] = input('post.captcha');

            //验证
            $msg =  User::admin_login($data);
            return $msg;
        }
        if(session('user_id')) $this->redirect('index/index');
        return $this->fetch('common/login');
    }

    public function logout()
    {
        if(is_login())
        {
            $msg = User::logout();
            return $msg;
        }else{
            $this->redirect('helper/login');
        }
    }

}