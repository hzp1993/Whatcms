<?php
namespace app\admin\controller;

use think\Request;
use think\Db;
use libs\Tree;
use app\admin\model\Menus as MenusModel;
use app\admin\controller\Common;
class Menus extends Common
{
    /**
     * [ index 菜单列表]
     * @author ItWhat(964114968@qq.com)
     * @param mixed
     */
    public function index()
    {
        if(!cache('menus'))
        {
            $list = MenusModel::order('sort asc')->select();
            cache('menus', $list);
        }else{
            $list = cache('menus');
        }
        $this->assign('list', unlimitedForLevel($list));
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        return $this->fetch();
    }

    /**
     * [ add 添加菜单信息]
     * @author ItWhat(964114968@qq.com)
     * @param array|mixed|void
     */
    public function add()
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->except(['id'], 'post');
            $menusModel = new MenusModel();
            if($menusModel->allowField(true)->validate(true)->save($data))
            {
                //缓存设置为null
                cache('menus', null);
                session('ADMIN_MENU_LIST', null);
                return alert('操作成功', cookie('__forward__'));
            }else{
                return alert($menusModel->getError(), '', 0);
            }

        }
        $tree = new Tree();
        $menus = Db::name('menus')->where(['status' => 1])->select();
        $menus = $tree->toFormatTree($menus);
        $menus = array_merge(array(0=>array('id'=>0,'name'=>'顶级菜单')), $menus);
        $this->assign('menus', $menus);
        return $this->fetch('edit');
    }

    /**
     * [ edit 编辑菜单信息]
     * @author ItWhat(964114968@qq.com)
     * @param array|mixed|void
     */
    public function edit($id)
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->post();
            $menusModel = new MenusModel();
            if($menusModel->allowField(true)->validate(true)->update($data))
            {
                cache('menus', null);
                session('ADMIN_MENU_LIST', null);
                return alert('操作成功', cookie('__forward__'));
            }else{
                return alert($menusModel->getError(), '', 0);
            }
        }
        $tree = new Tree();
        $menus = Db::name('menus')->where(['status' => 1])->select();
        $menus = $tree->toFormatTree($menus);
        $menus = array_merge([0 => ['id'=>0,'name'=>'顶级菜单']], $menus);
        $vo = MenusModel::where(['id' => $id])->find();
        $this->assign('menus', $menus);
        return $this->fetch('edit', ['vo' => $vo]);
    }

    /**
     * [ del 删除菜单信息]
     * @author ItWhat(964114968@qq.com)
     * @param array|void
     */
    public function del($id)
    {
        $reslut = MenusModel::destroy($id);
        if($reslut){
            cache('menus', null);
            session('ADMIN_MENU_LIST', null);
            return alert('操作成功', cookie('__forward__'));
        }else{
            return alert('操作失败', '', 0);
        }
    }
}