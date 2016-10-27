<?php
namespace app\admin\model;

use think\Model;
use think\Validate;
class Config extends Model
{
    public static function site($data)
    {
        $config = $data['config'];
        foreach($config as $k => $v)
        {
            $result = Config::where(['name' => $k])->update(['value' => $v]);
        }
        if($result !== false)
        {
            cache('DB_CONFIG_DATA', null);
            $message = alert(lang('operation success'), cookie('__forward__'));
        }else{
            $message = alert(lang('operation error'), '', 0);
        }
        return $message;
    }
}