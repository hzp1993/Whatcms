<?php
namespace app\admin\controller;

use think\Request;
use app\admin\model\Models;
use app\admin\model\Attribute;
use app\admin\controller\Common;
class Model extends Common
{
    public function index()
    {
        $list = cache('models');
        if(!$list)
        {
            $list = Models::order('sort asc')->paginate(10);
            cache('models', $list);
        }
        $this->assign('list', $list);
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        return $this->fetch();
    }

    public function add()
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->except(['id'], 'post');
            $msg = Models::add($data);
            return $msg;
        }
        return $this->fetch('edit');
    }

    public function edit($id)
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->post();
            $msg = Models::edit($data);
            return $msg;
        }
        $vo = Models::where(['id' => $id])->find();
        $attributelist = Attribute::where('doc_type', $id)->field('id,title')->select();

        return $this->fetch('edit', ['vo' => $vo, 'attributelist' => $attributelist]);
    }

    public function del($id)
    {
        $field = Attribute::where('doc_type', $id)->find();
        if($field) return alert(lang('Please delete the field in the list'), '', 0);

        //删除表
        $table = Models::where('id', $id)->value('name');
        $result = Attribute::deleteTable($table);
        if($result){
            $id = Models::destroy($id);
            if($id){
                cache('models', null);
                return alert(lang('operation success'), cookie('__forward__'));
            }else{
                return alert(lang('operation error'), '', 0);
            }
        }else{
            return alert(lang('operation error'), '', 0);
        }
    }

    public function attribute($mod = null, $name = '')
    {
        $mod || $this->error(lang('Parameter error'));//不存在 报错
        $list = cache('attribute_'.$mod);
        if(!$list)
        {
            $list = Attribute::where('doc_type',$mod)->order('id asc')->select();
            cache('attribute_'.$mod, $list);
        }
        $this->assign('list', $list);
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        return $this->fetch();
    }

    public function attribute_add($mod = null)
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->except(['id'], 'post');
            $msg = Attribute::add($data);
            return $msg;
        }
        return $this->fetch('attribute_edit');
    }

    public function attribute_edit($mod = null, $id)
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->post();
            $msg = Attribute::edit($data);
            return $msg;
        }
        $vo = Attribute::where(['id' => $id, 'doc_type' => $mod])->find();
        return $this->fetch('attribute_edit', ['vo' => $vo]);
    }

    public function attribute_del($id)
    {
        //指定表字段
        $field = Attribute::where('id', $id)->find();
        $table_field = Attribute::deleteFiled($field);
        if($table_field != true) return alert(lang('operation error'), 0);

        //删除数据
        $reslut = Attribute::destroy($id);
        if($reslut){
            cache('attribute_'.$field['doc_type'], null);
            return alert(lang('operation success'), cookie('__forward__'));
        }else{
            return alert(lang('operation error'), '', 0);
        }
    }
}