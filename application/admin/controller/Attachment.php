<?php
namespace app\admin\controller;

use libs\Qiniu;
use think\Request;
use think\Db;
use app\common\model\Images;
use app\admin\controller\Common;
class Attachment extends Common
{
    public function browseFile()
    {
        $data = Request::instance()->get();
        if(isset($data['y']) && !isset($data['m']))
        {
            $result = Images::getYMD('m', $data['y']);
        }elseif(isset($data['m']) && isset($data['y'])){
            $result = Images::getYMD('d', $data['y'], $data['m']);
        }else{
            $result = Images::getYMD();
        }
        return $this->fetch('', ['ymdlist' => $result]);
    }

    public function upload($filename='mypic')
    {
        if(Request::instance()->isPost())
        {
            $file = Request::instance()->file($filename);

            //UPLOAD_TYPE=1七牛 否则本地 未完成：回调需要完善 2016.09.13
            if(cache_config('UPLOAD_TYPE') == 1 && $file != null)
            {
                $config = [
                    'accesskey' => cache_config('UPLOAD_QINIU_ACCESS_KEY'),
                    'secretkey' => cache_config('UPLOAD_QINIU_SECRET_KEY'),
                    'bucket'    => cache_config('UPLOAD_QINIU_BUCKET'),
                    'domain'    => cache_config('UPLOAD_QINIU_DOMAIN'),
                    'exts'      => ['jpg', 'png', 'gif'],
                    'maxsize'   => 2097152,
                    'callback'  => cache_config('WEB_DOMAIN').url('callback'),//回调url
                ];
                $qiniu = new Qiniu($config);
                $result = $qiniu->upload($file->getInfo());
                if($result['key'])
                {
                    $data['key'] = $result['hash'];
                    $data['name'] = $result['key'];
                    if(!$this->add_upload_date($data)) return alert(lang('Upload error'), '', 0);
                    return json(['error' => 0, 'url' => $result['key'], 'message' => lang('Upload success')]);
                }else{
                    return json(['error' => 1, 'message' => $qiniu->getError()]);
                }
            }elseif(cache_config('UPLOAD_TYPE') == 0 && $file != null){

            }
        }
    }

    /**
     * 添加上传的数据存入数据库中
     * @param string $data
     */
    private function add_upload_date($data = '')
    {
        $data['y'] = date('Y', time());
        $data['m'] = date('m', time());
        $data['d'] = date('d', time());
        $data['create_time'] = time();
        $result = Db::name('images')->insert($data);
        if($result) return true;
    }

    
}