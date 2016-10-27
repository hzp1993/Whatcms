<?php
namespace app\admin\model;

use think\Model;
use think\Validate;
use think\Db;
class Menus extends Model
{
    public static function getMenus()
    {
        //定义常量
        $module = get_module();
        $controller = get_controller();
        $action = get_action();
        $menus = session('ADMIN_MENU_LIST.'.$controller);
        if(empty($menus))
        {
            $where['pid'] = 0;
            $menus['main'] = Menus::where($where)->order('sort asc')->select();
            $menus['child'] = []; //子节点设置

            foreach($menus['main'] as $key => $item)
            {
                //auth检测主菜单权限
                if(!IS_ROOT && !self::checkRule(strtolower($module.'/'.$item['url']),2,null))
                {
                    unset($menus['main'][$key]);
                    continue;//继续循环
                }
                if(strtolower($controller.'/'.$action) == strtolower($item['url']))
                {
                    $menus['main'][$key]['class'] = 'am-active';
                }
            }

            //查询子栏目匹配
            $pid =  Menus::where([
                            'pid' => ['neq', 0],
                            'url' => ['like', '%'.$controller.'/'.$action.'%']
                            ])->value('pid');
            if($pid)
            {
                // 查找当前主菜单
                $nav = Menus::get(['pid' => $pid]);
                if($nav['pid'])
                {
                    $nav = Menus::get(['id' => $nav['pid']]);
                }
                foreach($menus['main'] as $key => $item) {
                    if ($item['id'] == $nav['id']) {
                        $menus['main'][$key]['class'] = 'am-active';
                        //生成child树
                        $groups = Menus::where([
                            'group_name' => ['neq', ''],
                            'pid' => $item['id']
                        ])->distinct(true)->column('group_name');

                        $where = [];
                        $where['pid'] = $item['id'];
                        $second_urls = Menus::where($where)->column('id,url');

                        //检测
                        if (!IS_ROOT) {
                            $to_check_url_arr = [];
                            foreach ($second_urls as $key => $to_check_url) {
                                if (stripos($to_check_url, $module)!==0) {
                                    $rule = $module . '/' . $to_check_url;
                                } else {
                                    $rule = $to_check_url;
                                }
                                if (self::checkRule($rule, 1, null))
                                    $to_check_url_arr[] = $to_check_url;
                            }
                        }
                        //按照分组生成子菜单树
                        foreach ($groups as $g) {
                            $map = ['group_name' => $g];
                            if (isset($to_check_url_arr)) {
                                if (empty($to_check_url_arr)) {
                                    // 没有任何权限
                                    continue;
                                } else {
                                    $map['url'] = ['in', $to_check_url_arr];
                                }
                            }

                            $map['pid'] = $item['id'];
                            $menuList = Db::name('menus')->where($map)->order('sort asc')->column('id,pid,name,url,icon');
                            $menuList = Db::name('menus')->where($map)->order('sort asc')->column('id,pid,name,url,icon');
                            $menus['child'][$g] = list_to_tree($menuList, 'id', 'pid', 'operater', $item['id']);
                        }
                    }
                }
            }

            session('ADMIN_MENU_LIST.'.$controller,$menus);
        }
        return $menus;
    }

    /**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     */
    protected static function checkRule($rule, $type=1, $mode='url'){
        static $auth = null;
        if (!$auth) {
            $auth = new \libs\Auth();
        }
        if(!$auth->check($rule,session('user_id'),$type,$mode)){
            return false;
        }
        return true;
    }

    public static function returnNodes($tree = true)
    {
        if($tree){
            $list = Db::name('menus')->field('id, name, url, pid')->order('sort asc')->select();
            foreach ($list as $key => $value)
            {
                 if( stripos($value['url'],get_module())!==0 )
                 {
                     $list[$key]['url'] = get_module().'/'.$value['url'];
                 }
            }
            $nodes = list_to_tree($list,$pk='id',$pid='pid',$child='operator',$root=0);
            foreach ($nodes as $key => $value)
            {
                if(!empty($value['operator']))
                {
                    $nodes[$key]['child'] = $value['operator'];
                    unset($nodes[$key]['operator']);
                    unset($nodes[$key][0]);
                }
            }
        }else{
            $nodes = Db::name('menus')->field('name,url,pid')->order('sort asc')->select();
            foreach ($nodes as $key => $value)
            {
                if( stripos($value['url'],get_module())!==0 ){
                    $nodes[$key]['url'] = get_module().'/'.$value['url'];
                }
            }
        }
        return $nodes;
    }
}
?>