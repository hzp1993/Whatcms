<?php
namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
    //检验规则
    protected $rule = [
        ['moduleid', 'require', '内容必填'],
        ['name', 'require', '栏目名称必填'],
        ['template_index', 'require', '频道模版必填'],
        ['template_list', 'require', '列表模版必填'],
        ['template_show', 'require', '内容模版必填'],
    ];
}