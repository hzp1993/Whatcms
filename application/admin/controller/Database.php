<?php
namespace app\admin\controller;

use think\Request;
use think\Db;
use libs\Data;
use app\admin\controller\Common;
class Database extends Common
{
    public function backups($tables = null, $id = null, $start = null)
    {
        if(Request::instance()->isPost() && !empty($tables) && is_array($tables))
        {
            $path = cache_config('DATA_BACKUP_PATH');
            if(!is_dir($path))
            {
                mkdir($path, 0755, true);
            }
            $config = [
                'path' => realpath($path).DIRECTORY_SEPARATOR,
                'part' => cache_config('DATA_BACKUP_PART_SIZE'),
                'compress' => cache_config('DATA_BACKUP_COMPRESS'),
                'level' => cache_config('DATA_BACKUP_COMPRESS_LEVEL')
            ];

            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock))
            {
                return alert(lang('Detect a backup task being performed, please try again later'), '', 0);
            }else{
                //创建锁文件
                file_put_contents($lock, time());
            }

            //检查备份目录是否可写
            if(!is_writeable($config['path']))
                return alert(lang('Backup directory does not exist or not, please try again after checking'), '', 0);

            //生成备份文件信息
            $file = [
                'name' => date('Ymd-His', time()),
                'part' => 1,
            ];
            session('backup_file', $file);

            //缓存要备份的表
            $database = new Data($file, $config);
            if(false !== $database::create())
            {
                foreach($tables as $k => $table)
                {
                    $start = $database::backup($table, 0);
                    if($start === false){
                        return alert(lang('Backup error'), '', 0);
                        continue;
                    }
                }
                unlink($config['path'] . 'backup.lock');
                session('backup_file', null);
                return alert(lang('The backup to complete'), url('reduction'));
            }else{
                return alert(lang('Backup error'), '', 0);
            }
        }
        $list = Db::connect()->query('SHOW TABLE STATUS');
        $list  = array_map('array_change_key_case', $list);
        $this->assign('list', $list);
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        return $this->fetch();
    }

    public function reduction()
    {
        $path = cache_config('DATA_BACKUP_PATH');
        if(!is_dir($path))
        {
            mkdir($path, 0755, true);
        }
        $path = realpath($path);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path,  $flag);

        $list = [];
        foreach ($glob as $name => $file) {
            if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];

                if(isset($list["{$date} {$time}"])){
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time']     = strtotime("{$date} {$time}");

                $list["{$date} {$time}"] = $info;
            }
        }
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function optimize($tables = null)
    {
        if($tables)
        {
            if(is_array($tables))
            {
                $tables = implode('`,`', $tables);
                $list = Db::connect()->query('OPTIMIZE TABLE `{$tables}`');
            }else{
                $list = Db::connect()->query('OPTIMIZE TABLE `{$tables}`');
            }
            if($list)
            {
                return alert(lang('Data table optimization'), cookie('__forward__'));
            }else{
                return alert(lang('Data table optimization error please try again'), '', 0);
            }
        }else{
            return alert(lang('Please specify a table to be optimized'), '', 0);
        }
    }

    public function repair($tables = null)
    {
        if($tables)
        {
            if(is_array($tables))
            {
                $tables = implode('`,`', $tables);
                $list = Db::connect()->query('REPAIR TABLE `{$tables}`');
            }else{
                $list = Db::connect()->query('REPAIR TABLE `{$tables}`');
            }
            if($list)
            {
                return alert(lang('Data table repair completed'), cookie('__forward__'));
            }else{
                return alert(lang('Data table optimization error please try again'), '', 0);
            }
        }else{
            return alert(lang('Please specify a table to be optimized'), '', 0);
        }
    }
}
?>