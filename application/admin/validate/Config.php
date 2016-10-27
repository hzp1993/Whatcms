<?php
namespace app\admin\validate;

use think\Validate;

class Config extends Validate
{
    //检验规则
    protected $rule = [
        ['name', 'require|max:30', '名称必填|名称长度不能超过30字节'],
        ['title', 'require', '标题必填'],
        ['tvalue', 'max:150', '可选值长度不能超过150字节'],
    ];
}