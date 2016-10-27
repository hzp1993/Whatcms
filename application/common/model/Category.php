<?php
namespace app\common\model;

use think\Db;
use think\Validate;
use think\Model;
use libs\Pinyin;
use libs\Tree;
class Category extends Model
{
    /**
     * [get_category_tree 获取分类生成树形结构]
     * @author 渣渣IT
     * @DateTime 2016-09-29
     * @param bool $issend 前台用户生成使用 开发投稿模式
     * @return array|false|mixed|\PDOStatement|string|\think\Collection
     */
    public static function get_category_tree($issend = false, $moduleid = '')
    {
        $tree = new Tree();
        if($issend)
        {
            $category = Db::name('category')->where(['staus' => 1, 'issend' => 1])->order('sort desc')->select();
        }else{
            if($moduleid){
                $category = Db::name('category')->where('moduleid', $moduleid)->order('sort desc')->select();
            }else{
                $category = Db::name('category')->order('sort desc')->select();
            }

        }
        $category = $tree->toFormatTree($category);
        $category = array_merge([0 => ['id'=>0,'name'=>'顶级栏目']], $category);
        return $category;
    }

}
?>