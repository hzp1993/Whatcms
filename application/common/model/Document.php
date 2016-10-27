<?php
namespace app\common\model;

use think\Model;
use think\Db;
class Document extends Model
{
    protected $error;
    protected $rule = [
        ['title', 'require|max:100', '标题必填|标题不能超过100字符'],
        ['content', 'min:5', '内容不能小于5个字符']
    ];
    /**
     * [ get_documentList 获取文章列表]
     * @author ItWhat(964114968@qq.com)
     * @param  \think\paginator\Collection|\think\PaginatorCollection
     */
    public function get_DocumentList($cid, $limit = 10, $order = null)
    {
        $cid = (int)$cid; //强制转换int
        //获取分类信息
        $category_info = Db::name('category')->where('id', $cid)->find();
        //获取子分类信息
        $category_data = Db::name('category')->where(['moduleid' => $category_info['moduleid']])->select();
        $cid_list = get_category_childId($category_data, $cid);
        $cid_list[] = $cid;
        //视图查询数据
        $result = Db::view('document', 'id,cid,moduleid,title,flags,view,create_time,sort,status')
            ->view('category', 'name', 'category.id = document.cid')
            ->where(['moduleid' => $category_info['moduleid'], 'cid' => ['in', $cid_list]])
            ->paginate($limit,false,['query' => request()->param()]);
        return $result;
    }

    /**
     * [ get_InfoData 获取当前模型数据]
     * @author ItWhat(964114968@qq.com)
     * @param  void
     */
    public function get_InfoData($modelName = '', $id = '')
    {
        $result = Document::where('doc.id', $id)
            ->alias('doc')
            ->join('__'.strtoupper($modelName).'__ mod', 'doc.id = mod.id', 'RIGHT')
            ->find();
        return $result;
    }

    /**
     * 内容添加 通用
     * @param null $data
     * @return null|void
     */
    public static function addData($data = null)
    {
        if(empty($data)) return;

        $extend_data = $data['data']; //默认字段数据
        $extend_attribute = $data['attribute']; //模型字段数据
        $moduleid = Db::name('category')->where('id', $extend_data['type'])->value('moduleid');
        $models_name = Db::name('models')->where('id', $moduleid)->value('name');


        if(empty($extend_data['id']))
        {
            $datas = [
                'uid'         => session('user_id'),
                'moduleid'    => $moduleid,
                'create_time' => time(),
            ];
            $dataArr = array_merge($extend_data, $datas);
            //创建成功返回
            $data_id = Db::name('module_data')->insertGetId(except_key($dataArr, 'id,type'));
            if($data_id)
            {
                $datas = [];
                $datas = [
                    'id' => $data_id,
                ];
                $extend_attribute = format_data($extend_attribute);
                $attributeArr = array_merge($extend_attribute, $datas);
                $attribute_id = Db::name($models_name)->insert($attributeArr);
                if($attribute_id)
                {

                }
            }else{
                dump('-1');
            }
        }else{

        }

        return $data;
    }

    public function getError()
    {
        return $this->error = '111';
    }
}