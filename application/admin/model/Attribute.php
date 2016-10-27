<?php
namespace app\admin\Model;

use think\Validate;
use think\Db;
use think\Model;
class Attribute extends Model
{
    private static $default_field = ['id', 'cid', 'moduleid', 'title', 'flags', 'thumb', 'uid', 'view', 'comment', 'good', 'bad', 'mark', 'content', 'seotitle', 'keywords', 'description', 'create_time', 'update_time'];

    /**
     *  操作的表名
     * @author 渣渣IT
     * @DateTime 2016-08-31
     * @var null
     */
    protected  static $table_name = null;

    /**
     * [validate_check 验证信息]
     * @author 渣渣IT
     * @DateTime 2016-08-31
     * @param string $data
     * @return mixed
     */
    protected static function validate_check($data='')
    {
        $rule = [
            'name' => 'require|checkName:',
            'title' => 'require',
            'field' => 'require',
            'doc_type' => 'require',
        ];

        $msg = [
            'name.require' => lang('Please input Field name'),
            'name.checkName' => lang('Field already exists'),
            'title.require' => lang('Please input Field title'),
            'field.require' => lang('Please input Field define'),
            'doc_type.require' => lang('Parameter error'),
        ];
        $validate = new Validate($rule,$msg);
        $validate->extend([
            'checkName' => function($value){
                $map = [
                    'name' => ['eq',input('name', 'post')],
                    'doc_type' => ['eq',input('doc_type', 'post')],
                ];
                $id = input('id', 'post');
                if(!empty($id))
                {
                    $map['id'] = ['neq',$id];
                }
                $result = Attribute::where($map)->value('name');
                return $value == $result ? false : true;
            }
        ]);
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
     * @DateTime 2016-08-31
     * @param $data
     * @return array
     */
    public static function add($data)
    {
        //进行验证信息
        $check = self::validate_check($data);
        if($check['status'] == 1) {
            //检测是否存在默认字段
            if(in_array($data['name'], self::$default_field)) return alert(lang('Default field error'), '', 0);
            //创建字段
            $table_field = self::addField($data);
            if($table_field != true) $message = alert(lang('operation error'), 0);
            $result = Attribute::create($data);
            if ($result) {
                //查询models字段然后进行更新
                cache('attribute_'.$data['doc_type'], null);
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
     * @DateTime 2016-08-31
     * @param $data
     * @return array
     */
    public static function edit($data)
    {
        //进行验证信息
        $check = self::validate_check($data);
        if($check['status'] == 1) {
            //检测是否存在默认字段
            if(in_array($data['name'], self::$default_field)) return alert(lang('Default field error'), '', 0);
            //更新字段
            $table_field = self::updateField($data);
            if(!$table_field) $message = alert(lang('operation error'), 0);
            $result = Attribute::update($data);
            if ($result) {
                cache('attribute_'.$data['doc_type'], null);
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
     * [checkTableExist 检查当前表]
     * @author 渣渣IT
     * @DateTime 2016-08-31
     * @param $data
     * @return array
     */
    protected static function checkTableExist($doc_type)
    {
        $table_name = Db::name('models')->where('id', $doc_type)->value('name');
        self::$table_name = strtolower(config('database.prefix').$table_name);
        $result = DB::connect()->query("SHOW TABLES LIKE '".self::$table_name."'");
        return count($result);
    }

    /**
     * [addField 创建表添加字段]
     * @author 渣渣IT
     * @DateTime 2016-09-01
     * @param $data
     * @return array
     */
    protected static function addField($field)
    {
        //检测表是否存在
        $table_exist = self::checkTableExist($field['doc_type']);
        $table_name = self::$table_name;
        if($field['value'] === '')
        {
            $default = '';
        }elseif(is_numeric($field['value'])) {
            $default = ' DEFAULT '.$field['value'];
        }elseif(is_string($field['value'])){
            $default = ' DEFAULT \''.$field['value'].'\'';
        }else{
            $default = '';
        }

        if ($table_exist) {
            $sql = <<<sql
                ALTER TABLE `{$table_name}` ADD COLUMN `{$field['name']}` {$field['field']} {$default} COMMENT '{$field['title']}';
sql;
        } else {
            //新建表
            $sql = <<<sql
            CREATE TABLE IF NOT EXISTS `{$table_name}` 
            (`id`  int(10) UNSIGNED NOT NULL COMMENT 'ID' ,
            `{$field['name']}` {$field['field']} {$default} COMMENT '{$field['title']}' ,
            PRIMARY KEY (`id`)
            )
            ENGINE=MyISAM
            DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
            CHECKSUM=0
            ROW_FORMAT=DYNAMIC
            DELAY_KEY_WRITE=0
            ;
sql;
        }
        $result = DB::connect()->execute($sql);
        return $result !== false;
    }

    /**
     * [updateField 更新表字段]
     * @author 渣渣IT
     * @DateTime 2016-09-01
     * @param $data
     * @return array
     */
    protected static function updateField($field)
    {
        //检测表是否存在
        self::checkTableExist($field['doc_type']);
        $table_name = self::$table_name;

        //获取原字段名
        $last_field = Attribute::where('id', $field['id'])->value('name');

        //获取默认值
        $default = $field['value'] !='' ? ' DEFAULT \''.$field['value'].'\'' : '';

        $sql = <<<sql
            ALTER TABLE `{$table_name}` CHANGE COLUMN `{$last_field}` `{$field['name']}` {$field['field']} {$default} COMMENT '{$field['title']}' ;
sql;
        $result = DB::connect()->execute($sql);
        return $result !== false;
    }

    /**
     * [deleteFiled 删除表字段]
     * @author 渣渣IT
     * @DateTime 2016-09-01
     * @param $data
     * @return array
     */
    public static function deleteFiled($field)
    {
        $table_exist = self::checkTableExist($field['doc_type']);
        $table_name = self::$table_name;
        if($table_exist)
        {
            $sql = <<<sql
                ALTER TABLE `{$table_name}` DROP COLUMN `{$field['name']}`;
sql;
        }
        $result = DB::connect()->execute($sql);
        return $result !== false;
    }

    /**
     * [deleteTable 删除]
     * @author 渣渣IT
     * @DateTime 2016-09-02
     * @param $data
     * @return array
     */
    public static function deleteTable($table)
    {
        $table_name = config('database.prefix').$table;
        $sql = <<<sql
                drop table `{$table_name}`;
sql;
        $result = DB::connect()->execute($sql);
        return $result !== false;
    }


}