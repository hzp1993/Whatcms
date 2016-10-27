<?php
namespace app\admin\controller;

use app\admin\model\Menus;
use app\common\controller\Indexbase;
class Common extends Indexbase
{
    public function __construct()
    {
        parent::__construct();
        if(!is_login())
        {
            $this->redirect('helper/login');
        }
        //获取缓存数据 不存在查询数据进行缓存
        $config = cache('DB_CONFIG_DATA');
        if(!$config)
        {
            $config = db('config')->where('status',1)->column('name,value');
            cache('DB_CONFIG_DATA', $config);
        }
//        $a = Menus::getMenus();
//        $actionName = get_controller().'/'.get_action();
//        $action = search_array_key_value(Menus::getMenus(),'url',$actionName);
//        if($action)
//        {
//            $this->assign('breadcrumb',breadcrumb(Menus::getMenus(),$action['0']['id']));
//        }
        $categoryMenu = db('category')->where('pid', 0)->select();
        $this->assign('categoryMenu', $categoryMenu);
        $this->assign('__MENU__', Menus::getMenus());
    }

    public function index()
    {
        return $this->fetch();
    }

    public function openWindow()
    {
        return $this->fetch();
    }

//    public function del()
//    {
//
//    }
}