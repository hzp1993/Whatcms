<?php
namespace app\common\model;

use think\Model;
class Images extends Model
{
    public static function getImagesList()
    {

    }

    public static function getYMD($data = '', $y = '', $m = '')
    {
        if($y && $m == null)
        {
            $list = Images::where('y', $y)->field('m')->group('m')->select();
        }elseif($m && $y !== null){
            $list = Images::where(['y' => $y, 'm' => $m])->paginate(5,false,['query' => request()->param()]);
        }else{
            $list = Images::group('y')->field('y')->select();
        }
        return $list;
    }
}
?>