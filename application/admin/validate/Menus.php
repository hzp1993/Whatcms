<?php
namespace app\admin\validate;

use think\Validate;

class Menus extends Validate
{
    //检验规则
    protected $rule = [
        ['url', 'require|max:255', 'URL必填|URL长度不能超过255字节'],
        ['name', 'require', '别名必填']
    ];
}