<?php
define('IS_ROOT', is_administrator());
/**
 * 获取配置类型
 */
function set_config_type($id='')
{
    $value = [
        '1' => '文本框',
        '2' => '下拉列表',
        '3' => '单选框',
        '4' => '多行文本',
        '5' => '上传图片',
    ];
    if($id){
        return $value[$id];
    }
    return $value;
}

/**
 * 获取配置分组
 */
function set_config_group($id=null)
{
    $value = [
        '0' => '不分组',
        '1' => '基本信息',
        '2' => '核心配置',
        '3' => '邮箱配置',
        '4' => '数据配置',
        '5' => '会员配置',
        '6' => '上传设置'
    ];
    if(isset($id)){
        return $value[$id];
    }
    return $value;
}

/**
 * 获取状态
 */
function set_status($id=null)
{
    $value = [
        '0' => '否',
        '1' => '是',
    ];
    if(isset($id)){
        return $value[$id];
    }else{

        return $value;
    }
}

function set_form_config($type, $name, $value, $tvalue)
{
    $str = '';
    $status_arr = set_status();
    switch ($type)
    {
        case 1:
            $str = '<input class="am-form-field" name="config['.$name.']" value="'.$value.'" />';
        break;
        case 2:
            $str = '<select name="config['.$name.']">';
            if(!empty($tvalue)){
                $arr = explode(PHP_EOL, trim($tvalue));
                foreach($arr as $k => $v)
                {
                    $array = explode('|', $v);
                    if($array[0] == $value) $selected = 'selected'; else $selected = '';
                    $str .= '<option value="'.$array[0].'" '.$selected.'>'.$array[1].'</option>';
                }
            }else{
                foreach($status_arr as $k => $v){
                    if($k == $value) $selected = 'selected'; else $selected = '';
                    $str .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                }
            }
            $str .= '</select>';
        break;
        case 3:
            if(!empty($tvalue)){
                $arr = explode(PHP_EOL, trim($tvalue));
                foreach($arr as $k => $v)
                {
                    $array = explode('|', $v);
                    if($array[0] == $value) $checked = 'checked'; else $checked = '';
                    $str .= '<label class="am-radio-inline"><input type="radio" value="'.$array[0].'" name="config['.$name.']" '.$checked.' >'.$array[1].'</label>';
                }
            }else{
                foreach($status_arr as $k => $v){
                    if($k == $value) $checked = 'checked'; else $checked = '';
                    $str .= '<label class="am-radio-inline"><input type="radio" value="'.$k.'" name="config['.$name.']" '.$checked.' >'.$v.'</label>';
                }
            }
        break;
        case 4:
            $str = '<textarea rows="5" name="config['.$name.']">'.$value.'</textarea>';
        break;
        default:
            $str = '<div class="am-form-inline"><input class="am-form-field" id="uploadOne" name="config['.$name.']" value="'.$value.'" style="width:75%;"/> <div class="am-btn am-btn-primary am-radius am-btn-sm up-picture-btn"> <span>添加图片</span><input id="fileupload" type="file" name="mypic" data-url="'.url("attachment/upload").'"></div> <a href="javascript:;" onclick="openWindow(this);" data-url="'.url("attachment/browseFile").'" title="站内图片" class="am-btn am-btn-success am-radius am-btn-sm ">站内图片</a></div>';
    }
    return $str;
}

/**
 * 无限极分类
 * @param $list
 * @param int $pid
 * @param int $level
 * @return array
 */
function unlimitedForLevel($list, $pid = 0, $level = 0)
{
    $arr = [];
    foreach ($list as $k => $v) {
        if ($v['pid'] == $pid) {
            $v['level'] = $level + 1;
            $arr[] = $v;
            $arr = array_merge($arr, unlimitedForLevel($list, $v['id'], $level + 1));
        }
    }
    return $arr;
}

/**
 * 检测是否是超级管理
 */
function is_administrator($uid = null){
    $uid = is_null($uid) ? session('user_id') : $uid;
    return $uid && (intval($uid) === config('USER_ADMINISTRATOR'));
}

/**
 * 获取字段表单类型
 */
function get_attribute_type($type = '')
{
    // TODO 可以加入系统配置
    static $_type = [
        'hidden'    =>  ['隐藏', 'varchar(32) NOT NULL'],
        'text'      =>  ['单行文本','varchar(128) NOT NULL'],
        'textarea'  =>  ['多行文本','varchar(256) NOT NULL'],
        'array'     =>  ['数组', 'varchar(32) NOT NULL'],
        'radio'     =>  ['单选','varchar(32) NOT NULL'],
        'checkbox'  =>  ['复选框','varchar(32) NOT NULL'],
        'select'    =>  ['下拉框','varchar(32) NOT NULL'],
        'date'      =>  ['时间','int(10) NOT NULL'],
        'picture'   =>  ['上传图片','int(10) UNSIGNED NOT NULL'],
        'pictures'  =>  ['多张图片','varchar(32) NOT NULL'],
        'file'      =>  ['单个附件','int(10) UNSIGNED NOT NULL'],
        'files'     =>  ['多个附件','varchar(32) NOT NULL'],
        'media'     =>  ['单个媒体','int(10) UNSIGNED NOT NULL'],
        'medias'    =>  ['多个媒体','varchar(32) NOT NULL'],
        'editor'    =>  ['编辑器','text NOT NULL'],
    ];
    return $type?$_type[$type][0]:$_type;
}

function get_attribute_validate($id = null)
{
    $value = [
        'regex' => '正则验证',
        'function' => '函数验证',
        'unique' => '唯一验证',
        'length' => '长度验证',
        'in' => '验证在范围内',
        'notIn' => '验证不在范围内',
        'between' => '验证不在范围内',
        'notbetween' => '不在区间验证',
    ];
    if(isset($id)){
        return $value[$id];
    }
    return $value;
}

function get_cohesion(){
    
}