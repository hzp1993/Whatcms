<?php
// 应用公共文件

/**
 * 检测用户是否登陆
 * @return bool|mix|mixed|null|string|void
 */
function is_login()
{
    $uid = session('user_id');
    if(!$uid)
    {
        return false;
    }
    return $uid;
}

/**
 * 设置随机函数
 * @param int $num
 * @return string
 */
function set_rand($num = 32)
{
    $str = '';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz-';
    $charlen = strlen($chars) -1;
    for($i=0; $i<$num; $i++)
    {
        $str .= $chars[mt_rand(0, $charlen)];
    }
    return $str;
}

/**
 * 获取客户端IP地址
 * @param int $type
 * @param bool $adv
 * @return mixed
 */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 获取模块名称
 * @return $this|string
 */
function get_module()
{
    $request = request();
    return $request->module();
}

/**
 * 获取控制名称
 * @return $this|string
 */
function get_controller()
{
    $request = request();
    return $request->controller();
}

/**
 * 获取操作名称
 * @return string
 */
function get_action()
{
    $request = request();
    return $request->action();
}

/**
 * 返回结果
 * @param string $msg
 * @param string $url
 * @param int $status
 * @param string $data
 * @return array
 */
function alert($msg='', $url='', $status=1, $data='')
{
    $message = [
        'msg' => $msg,
        'url' => $url,
        'status' => $status,
        'data' => $data
    ];
    return $message;
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list,$field, $sortby='asc') {
    if(is_array($list)){
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc':// 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ( $refer as $key=> $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0, $level = 0) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                    $parent[$level][] = $level+1;
                }
            }
        }
    }
    return $tree;
}

/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 * @author yangweijie <yangweijiester@gmail.com>
 */
function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
    if(is_array($tree)) {
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, $sortby='asc');
    }
    return $list;
}

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 */
function time_format($time = NULL,$format='Y-m-d H:i'){
    $time = $time === NULL ? NOW_TIME : intval($time);
    return date($format, $time);
}


/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 获取缓存中过的配置
 * @param null $name
 * @return bool|mixed
 */
function cache_config($name = null)
{
    $config = cache('DB_CONFIG_DATA');
    if(empty($name)) return $config;
    return $config[$name];
}

/**
 * 对象或是数组中查询条件返回多维数组
 * @param $array
 * @param $key
 * @param $val
 * @return array
 */
function search_array_key_value($array, $key, $val, $is_object = false)
{
    $data = [];
    if ($is_object) {
        foreach ($array as $item => $value) {
            if ($value->$key == $val) {
                $data[] = $value->toArray();
            }
        }
    } else {

        foreach ($array as $item => $value) {
            dump($value);die;
        }
    }
    return $data;
}

/**
 * 获取所有模型
 * @return bool|false|mixed|PDOStatement|string|\think\Collection
 */
function get_Allmodule()
{
    $models = cache('models');
    if(!$models){
        $models = db('models')->where('status', 1)->select();
    }
    return $models;
}

/**
 * 截取指定长度的字符串
 * @param string $str 字符串
 * @param integer $num 截取长度
 * @param boolean $flag 是否显示省略符
 * @param string $sp 省略符
 * @return string
 */
function str2sub($str, $num, $flag = 0, $sp = '...')
{
    if ($str == '' || $num <= 0) {
        return $str;
    }
    $strlen = mb_strlen($str, 'utf-8');
    $newstr = '';
    $newstr .= mb_substr($str, 0, $num, 'utf-8'); //substr中国会乱码
    if ($num < $strlen && $flag) {
        $newstr .= $sp;
    }

    return $newstr;
}

/**
 * post 处理
 * @param $data
 * @return mixed
 */
function format_data($data)
{
    foreach($data as $key => $val)
    {
        if(is_array($val))
        {
            $data[$key] = implode(',', $val);
        }
    }
    return $data;
}

/**
 * 过滤掉指定的数据
 * @param $data
 * @param $name
 * @return mixed
 */
function except_key($data, $name)
{
    if (is_string($name)) {
        $name = explode(',', $name);
    }
    foreach($name as $key)
    {
        if (isset($data[$key])) {
            unset($data[$key]);
        }
    }
    return $data;
}

/**
 * 获取栏目父级找子级
 * @param $array
 * @param int $pid
 * @return array
 */
function get_category_childId($array, $pid = 0)
{
    $data = [];
    foreach($array as $key => $val)
    {
        if($val['pid'] == $pid)
        {
            $data[] = $val['id'];
            $data = array_merge($data, get_category_childId($array, $val['id']));
        }
    }
    return $data;
}