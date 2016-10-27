<?php
namespace app\admin\controller;

use think\Db;
use think\Request;
use libs\Tree;
use app\common\model\Category as CategoryModel;
use app\admin\controller\Common;
class Category extends Common
{
    /**
     * [ index 栏目列表]
     * @author ItWhat(964114968@qq.com)
     * @param  mixed
     */
    public function index()
    {
        $list = CategoryModel::order('sort desc')->select();
        $this->assign('list', unlimitedForLevel($list));
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        return $this->fetch();
    }

    /**
     * [ add 添加栏目信息]
     * @author ItWhat(964114968@qq.com)
     * @param  array|mixed
     */
    public function add()
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->except(['id'], 'post');
            $categoryModel = new CategoryModel();
            if($categoryModel->allowField(true)->validate(true)->save($data))
            {
                return alert('操作成功', cookie('__forward__'));
            }else{
                return alert($categoryModel->getError(), '', 0);
            }
        }
        $tree = new Tree();
        $category = CategoryModel::get_category_tree();
        $this->assign('categorylist', $category);
        return $this->fetch('edit');
    }

    /**
     * [ edit 编辑栏目信息]
     * @author ItWhat(964114968@qq.com)
     * @param  array|mixed
     */
    public function edit($id)
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->post();
            $categoryModel = new CategoryModel();
            if($categoryModel->allowField(true)->validate(true)->update($data))
            {
                return alert('操作成功', cookie('__forward__'));
            }else{
                return alert($categoryModel->getError(), '', 0);
            }
        }
        $vo = CategoryModel::where(['id' => $id])->find();
        $category = CategoryModel::get_category_tree();
        $this->assign('categorylist', $category);
        return $this->fetch('edit', ['vo' => $vo]);
    }
}