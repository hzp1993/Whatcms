<?php
namespace app\common\model;

use think\Validate;
use think\Model;
class User extends Model
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
            'username' => 'require',
            'email' => 'require',
        ]; 

        $msg = [
            'username.require' => lang('Please input User name'),
            'email.require' => lang('Please input Email'),
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
     * [admin_login 管理员登陆]
     * @author 渣渣IT
     * @DateTime 2016-08-04
     * @param $data
     * @return array
     */
    public static function admin_login($data)
    {
        $rule = [
            'username' => 'require',
            'password' => 'require',
            'captcha|验证码'=>'require|captcha'
        ];

        $msg = [
            'username.require' => lang('User name cannot be empty'),
            'password.require' => lang('Password can not be empty'),
         ];

        $validate = new Validate($rule,$msg);
        $result = $validate->check($data);
        if(!$result){
            $message['msg'] = $validate->getError();
            $message['status'] = 0;
            return $message;
        }else{
            //进行数据验证
            $user_info = User::where('username', $data['username'])->whereOr('email', $data['username'])->find();
            if(!$user_info) return ['msg' => lang('The user does not exist, please check and try again'), 'status' => 0];
            $password = md5(md5($data['password']).$user_info['auth_key']);
            if($password != $user_info['password']) return ['msg' => lang('Incorrect password'), 'status' => 0];
            session('user_id', $user_info['uid']);
            session('username', $user_info['username']);
            User::where('uid', $user_info['uid'])->update([
                'login_ip' => get_client_ip(),
                'login_time' => time()
            ]);
            return ['url' => url('index/index'), 'msg' => lang('Landing success'), 'status' => 1];
        }
    }

    public static function add($data)
    {
        //进行验证信息
        $check = self::validate_check($data);

        if($check['status'] == 1) {
            //检测密码是否为空
            if(empty($data['password'])) return alert(lang('Password can not be empty'), '', 0);
            if($data['password'] != input('repassword')) {
                return alert(lang('Confirm password is not consistent'), '', 0);
            }
            if(self::is_checkUser($data))
                return alert(lang('User name or mailbox already exists, please replace'), '', 0);
            $data['auth_key'] = set_rand();
            $data['password'] = md5(md5($data['password']).$data['auth_key']);
            $data['reg_ip'] = get_client_ip();
            $data['reg_time'] = time();
            $result = User::create($data);
            if ($result) {
                cache('user', null);
                $message = alert(lang('operation success'), cookie('__forward__'));
            } else {
                $message = alert(lang('operation error'), '', 0);
            }
            return $message;
        }else{
            return $check;
        }
    }

    public static function edit($data)
    {
        //进行验证信息
        $check = self::validate_check($data);

        if($check['status'] == 1) {
            //检测密码是否为空
            if($data['password'] != input('repassword'))
                return alert(lang('Confirm password is not consistent'), '', 0);
            if(self::is_checkUser($data))
                return alert(lang('User name or mailbox already exists, please replace'), '', 0);
            $data['auth_key'] = set_rand();
            $data['password'] = md5(md5($data['password']).$data['auth_key']);
            $result = User::update($data);
            if ($result) {
                cache('user', null);
                $message = alert(lang('operation success'), cookie('__forward__'));
            } else {
                $message = alert(lang('operation error'), '', 0);
            }
            return $message;
        }else{
            return $check;
        }
    }

    protected static function is_checkUser($data)
    {
        $uid = isset($data['uid']) ? $data['uid'] : '';
        if($uid)
        {
            $reslut = User::where(['uid' => ['neq', $uid], 'username' => $data['username']])->find();
        }else{
            $reslut = User::where('username', $data['username'])->whereOr('email', $data['email'])->find();
        }
        if($reslut) return true;
    }

    //退出登录
    public static function logout()
    {
        session(null);
        $message = alert(lang('operation success'), url('helper/login'));
        return $message;
    }
}