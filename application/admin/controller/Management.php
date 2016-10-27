<?php
namespace app\admin\controller;

use think\Request;
use app\admin\model\AuthGroup;
use app\admin\model\Menus;
use app\common\model\User;
use app\admin\controller\Common;
class Management extends Common
{
    public function index()
    {
        $list = cache('auth_group');
        if(!$list)
        {
            $list = AuthGroup::order('id asc')->select();
        }
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function add()
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->except(['id'], 'post');
            $msg = AuthGroup::add($data);
            return $msg;
        }
        return $this->fetch('edit');
    }

    public function edit($id)
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->post();
            $msg = AuthGroup::edit($data);
            return $msg;
        }
        $vo = AuthGroup::where(['id' => $id])->find();
        return $this->fetch('edit', ['vo' => $vo]);
    }

    public function del()
    {
        $id = Request::instance()->get('id');
        $reslut = AuthGroup::destroy($id);
        if($reslut){
            cache('auth_group', null);
            return alert(lang('operation success'), cookie('__forward__'));
        }else{
            return alert(lang('operation error'), '', 0);
        }
    }

    public function access($group_id)
    {
        self::updateRules();//初始化把menus的数据存入auth_rules
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->post();
            $msg = AuthGroup::writeGroup($data);
            return $msg;
        }
        $auth_group = AuthGroup::where(['status' => 1, 'id' => $group_id])->find();
        $main_rules = db('auth_rule')->where(['status' => 1, 'type' => 2])->column('name,id');
        $child_rules = db('auth_rule')->where(['status' => 1, 'type' => 1])->column('name,id');
        $nodes_list = Menus::returnNodes();
        $this->assign('main_rules', $main_rules);
        $this->assign('child_rules', $child_rules);
        $this->assign('node_list', $nodes_list);
        $this->assign('this_group', $auth_group);
        return $this->fetch();
    }

    public function seek()
    {
        return $this->fetch();
    }

    public function group()
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->post();
            $msg = AuthGroup::addGroup($data);
            return $msg;

        }
        $group_list = AuthGroup::where('status', 1)->column('id,title');
        $this_access = db('auth_group_access')->where('uid', input('uid', 'get'))->value('group_id');
        $this->assign('this_access', $this_access);
        $this->assign('group_list', $group_list);
        return $this->fetch();
    }

    public function updateRules()
    {
        $nodes = Menus::returnNodes(false);
        $rules = db('auth_rule')->where(['type' => ['in', '1,2']])->select();

        //构建insert数据
        $data = [];
        foreach($nodes as $value)
        {
            $temp['name']   = $value['url'];
            $temp['title']  = $value['title'];
            $temp['module'] = 'admin';
            if($value['pid'] > 0)
            {
                $temp['type'] = 1;
            }else{
                $temp['type'] = 2;
            }
            $temp['status'] = 1;
            $data[strtolower($temp['name'].$temp['module'].$temp['type'])] = $temp;
        }

        $update = [];//保存需要更新的节点
        $ids = [];//保存需要删除的节点的id
        foreach($rules as $key => $value)
        {
            $index = strtolower($value['name'].$value['module'].$value['type']);
            if(isset($data[$index]))
            {
                $data[$index]['id'] = $value['id'];
                $update[] = $data[$index];
                unset($data[$index]);
                unset($rules[$key]);
                unset($value['condition']);
                $diff[$value['id']]=$value;
            }elseif($value['status']==1){
                $ids[] = $value['id'];
            }
        }

        if(count($update))
        {
            foreach($update as $k => $row)
            {
                if($row != $diff[$row['id']])
                {
                    db('auth_rule')->where(['id' => $row['id']])->update($row);
                }
            }
        }

        if(count($ids))
        {
            db('auth_rule')->where(['id' => ['in', implode(',', $ids)]])->update(['status' => -1]);
        }

        if(count($data))
        {
            db('auth_rule')->insertAll(array_values($data));
        }
    }
}
?>