<?php
namespace app\admin\model;

use Think\Auth;
use think\Model;
use think\Validate;
class AuthGroup extends Model
{
    /**
     * [validate_check 验证信息]
     * @author 渣渣IT
     * @DateTime 2016-08-22
     * @param string $data
     * @return mixed
     */
    protected static function validate_check($data='')
    {
        $rule = [
            'title' => 'require',
        ];

        $msg = [
            'title.require' => lang('Please input User groups')
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
     * @DateTime 2016-08-22
     * @param $data
     * @return array
     */
    public static function add($data)
    {
        //进行验证信息
        $check = self::validate_check($data);
        if($check['status'] == 1) {
            $data['module'] = get_module();
            $data['status'] = 1;
            $data['type'] = 1;
            $result = AuthGroup::create($data);
            if ($result) {
                cache('auth_group', null);
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
     * @DateTime 2016-08-22
     * @param $data
     * @return array
     */
    public static function edit($data)
    {
        //进行验证信息
        $check = self::validate_check($data);
        if($check['status'] == 1) {
            $data['module'] = get_module();
            $data['status'] = 1;
            $data['type'] = 1;
            $result = AuthGroup::update($data);
            if ($result) {
                cache('auth_group', null);
                $message = alert(lang('operation success'), cookie('__forward__'));
            } else {
                $message = alert(lang('operation error'), '', 0);
            }
            return $message;
        }else{
            return $check;
        }
    }

    public static function writeGroup($data)
    {
        $rules = implode(',', array_unique($data['rules']));
        $result = AuthGroup::where('id', $data['group_id'])->update(['rules' => $rules]);
        if($result)
        {
            $message = alert(lang('operation success'), cookie('__forward__'));
        }else{
            $message = alert(lang('operation error'), '', 0);
        }
        return $message;
    }

    public static function addGroup($data)
    {
        if(is_numeric($data['uid']))
        {
            if(is_administrator($data['uid']))
            {
                return alert(lang('This user is the super administrator'), '', 0);
            }
            $user = db('user')->where('id', $data['uid'])->find();
            if(!$user)
            {
                return alert(lang('Data error! The user does not exist'), '', 0);
            }
        }
        $auth_access = db('auth_group_access')->where('uid', $data['uid'])->find();
        //查询是否存在 不存在新增 存在修改
        if(!$auth_access)
        {
            $result = db('auth_group_access')->insert($data);
            if($result)
            {
                $message = alert(lang('operation success'), cookie('__forward__'));
            }else{
                $message = alert(lang('operation error'), '', 0);
            }
        }else{
            $result = db('auth_group_access')->where('uid', $data['uid'])->update($data);
            if($result)
            {
                $message = alert(lang('operation success'), cookie('__forward__'));
            }else{
                $message = alert(lang('operation error'), '', 0);
            }
        }
        return $message;

    }
}
?>