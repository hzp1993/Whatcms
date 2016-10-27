<?php
namespace app\admin\controller;

use think\Request;
use think\Db;
use libs\Tree;
use app\admin\model\Config;
use app\admin\controller\Common;
class Setting extends Common
{
    /**
     * [ index 配置列表]
     * @author ItWhat(964114968@qq.com)
     * @param  mixed
     */
    public function index()
    {
        $list = Config::order('sort desc')->select();
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign('list', $list);
        return $this->fetch ();
    }

    /**
     * [ add 添加配置信息]
     * @author ItWhat(964114968@qq.com)
     * @param  array|mixed|void
     */
    public function add()
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->except(['id'], 'post');
            $configModel = new Config();
            if($configModel->allowField(true)->validate(true)->save($data))
            {
                //缓存设置为null
                cache('DB_CONFIG_DATA', null);
                return alert('操作成功', cookie('__forward__'));
            }else{
                return alert($configModel->getError(), '', 0);
            }
        }
        return $this->fetch('edit');
    }

    /**
     * [ edit 编辑配置信息]
     * @author ItWhat(964114968@qq.com)
     * @param  array|mixed|void
     */
    public function edit($id)
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->post();
            $configModel = new Config();
            if($configModel->allowField(true)->validate(true)->update($data))
            {
                //缓存设置为null
                cache('DB_CONFIG_DATA', null);
                return alert('操作成功', cookie('__forward__'));
            }else{
                return alert($configModel->getError(), '', 0);
            }
        }
        $vo = Db::name('config')->where(['id' => $id])->find();
        return $this->fetch('edit', ['vo' => $vo]);
    }

    /**
     * [ del 删除配置信息]
     * @author ItWhat(964114968@qq.com)
     * @param  array|void
     */
    public function del()
    {
        $id = Request::instance()->get('id');
        $reslut = Config::destroy($id);
        if($reslut){
            return alert('操作成功', cookie('__forward__'));
        }else{
            return alert('操作失败', '', 0);
        }
    }

    /**
     * [ site ]
     * @author ItWhat(964114968@qq.com)
     * @param array|mixed|void
     */
    public function site()
    {
        if(Request::instance()->isPost())
        {
            $data = Request::instance()->post();
            $config = $data['config'];
            foreach($config as $k => $v)
            {
                $result = Config::where(['name' => $k])->update(['value' => $v]);
            }
            if($result !== false)
            {
                cache('DB_CONFIG_DATA', null);
                return alert('操作成功', cookie('__forward__'));
            }else{
                return alert('操作失败', '', 0);
            }
        }
        $group = input('groupId', 1);
        $list = Config::where(['group_id' => $group])->select();
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->assign('list', $list);
        return $this->fetch();
    }
}