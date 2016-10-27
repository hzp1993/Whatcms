<?php
namespace app\admin\controller;

use think\Db;
use think\Request;
use app\common\model\Category;
use app\common\model\Document;
use app\admin\model\Models;
use app\admin\model\Attribute;
use app\admin\controller\Common;
class Content extends Common
{
    public function index()
    {
        $documentModel = new Document();
        $cid = input('get.type');
        if(!$cid) $this->error(lang('Parameter error'));

        //获取文章列表
        $article_list = $documentModel->get_DocumentList($cid, cache_config('ADMIN_PAGE_ROWS'), null);
        dump($article_list);
        $this->assign('contentlist', $article_list);
        return $this->fetch();
    }

    public function edit($id = '')
    {
        $documentModel = new Document();
        $type = input('get.type');

        if(Request::instance()->isPost())
        {
            $data = Request::instance()->post();

        }
        //获取该分类
        $category_info = Category::where('id', $type)->find();
        //获取该分类模型
        $model_type = Models::where('id', $category_info['moduleid'])->find();
        //组合条件
        $map = [
            'is_show'    => ['eq', 1],
            'doc_type'   => ['in', '0,'.$category_info['moduleid']],
        ];
        $attribute_list = Attribute::where($map)->select();
        //获取当前文章数据
        $item = $documentModel->get_InfoData($model_type['name'], $id);
        $this->assign('item', $item);
        $this->assign('attributelist', $attribute_list);
        $this->assign('categorylist', Category::get_category_tree(false, $category_info['moduleid']));
        return $this->fetch();

    }


//    public function add()
//    {
//        $cid = input('get.type');
////        if(!$cid) $this->error(lang('Parameter error'));
//        if(Request::instance()->isPost())
//        {
//            $data = Request::instance()->except(['id'], 'post');
//            Hzp::addData($data);
//
//        }
//        //获取该分类
//        $category_info = Category::where('id', $cid)->find();
//        //获取该分类模型
//        $model_type = Models::where('id', $category_info['moduleid'])->find();
//
//        //组合条件
//        $map = [
//            'is_show'    => ['eq', 1],
//            'doc_type'   => ['in', '0,'.$category_info['moduleid']],
//        ];
//
//        $attribute_list = Attribute::where($map)->select();
//        $this->assign('categorylist', Category::get_category_tree(false, $category_info['moduleid']));
//        $this->assign('attributelist', $attribute_list);
//        return $this->fetch('edit');
//    }

}

