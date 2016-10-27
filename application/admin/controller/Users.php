<?php
namespace app\admin\controller;

use think\Request;
use app\common\model\User;
use app\admin\controller\Common;
class Users extends Common
{
    public function index()
    {
        $list = cache('user');
        if(!$list)
        {
            $list = User::order('uid asc')->paginate(10);
            cache('user', $list);
        }
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function add()
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->except(['uid','repassword'], 'post');
            $msg = User::add($data);
            return $msg;
        }
        return $this->fetch('edit');
    }

    public function edit($uid)
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->except(['repassword'], 'post');
            $msg = User::edit($data);
            return $msg;
        }
        $vo = User::where(['uid' => $uid])->find();
        return $this->fetch('edit', ['vo' => $vo]);
    }

    public function del()
    {
        $uid = Request::instance()->get('uid');
        $reslut = User::destroy($uid);
        if($reslut){
            cache('user', null);
            return alert(lang('operation success'), cookie('__forward__'));
        }else{
            return alert(lang('operation error'), '', 0);
        }
    }
    
}
?>