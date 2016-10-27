<?php
namespace app\admin\model;

use think\Model;
use think\Validate;
class Models extends Model
{
    /**
     * [validate_check 验证信息]
     * @author 渣渣IT
     * @DateTime 2016-08-15
     * @param string $data
     * @return mixed
     */
    protected static function validate_check($data='')
    {
        $rule = [
            'name' => 'require',
            'title' => 'require',
        ];

        $msg = [
            'name.require' => lang('Please input Model name'),
            'title.require' => lang('Please input Model title')
        ];
        $validate = new Validate($rule,$msg);
        $result = $validate->check($data);
        if(!$result){
            $message['msg'] = $validate->getError();
            $message['status'] = 0;
            return $message;
        }else{
            $message['status'] = 1;
            return $message;
        }
    }

    /**
     * [add 增加数据]
     * @author 渣渣IT
     * @DateTime 2016-08-15
     * @param $data
     * @return array
     */
    public static function add($data)
    {
        //进行验证信息
        $check = self::validate_check($data);
        if($check['status'] == 1) {
            $data['create_time'] = time();
            $data['status'] = 1;
            $result = Models::create($data);
            if ($result) {
                cache('models', null);
                $message = alert(lang('operation success'), cookie('__forward__'));
            } else {
                $message = alert(lang('operation error'), '', 0);
            }
            return $message;
        }else{
            return $check;
        }
    }

    /**
     * [edit 编辑数据]
     * @author 渣渣IT
     * @DateTime 2016-08-15
     * @param $data
     * @return array
     */
    public static function edit($data)
    {
        //进行验证信息
        $check = self::validate_check($data);
        if($check['status'] == 1) {
            $data['update_time'] = time();
            $result = Models::update($data);
            if ($result) {
                cache('models', null);
                $message = alert(lang('operation success'), cookie('__forward__'));
            } else {
                $message = alert(lang('operation error'), '', 0);
            }
            return $message;
        }else{
            return $check;
        }
    }
}
?>