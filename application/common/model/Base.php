<?php
namespace app\common\model;
use app\common\service\helperService;
use think\Model;
use think\Session;

class Base extends Model
{

    public function __construct($data=""){
        parent::__construct($data);
    }

    protected function BaseModel($res)
    {
        return helperService::modelDataToArr($res);
    }


    public function getPage($page,$pageSize,$condition=[],$is_count = false,$order=''){
        $this->alias('a');
        if(!empty($condition)){
            $this->where($condition);
        }
        if(!empty($order)){
            $this->order($order);
        }
        if($is_count){
            return $this->count();
        }
        return $this->page($page,$pageSize)->select();
    }
    /**
     * 保存数据
     * @param $data
     * @param array $condition
     * @return $this
     */
    public function saveData($data,$condition=[]){
        if($condition){
            return $this->where($condition)->update($data);
        }
        return $this->insertGetId($data);
    }

    /**
     * 保存多条数据
     * @param $data
     * @return bool|int|string
     */
    public function insertAllDate($data){
        if(empty($data) && !is_array($data)){
            return false;
        }
        return $this->insertAll($data);
    }

    /**
     * 获取一条数据信息
     * @param array $condition
     * @param bool $is_cache
     * @param string $field
     * @return mixed
     */
    public function getOne($condition=[],$is_cache=false,$field=''){
        $this->alias('a');
        if($field){
            $this->field($field);
        }
        if($condition){
            $this->where($condition);
        }
        if($is_cache){
            $this->cache($is_cache,60);
        }
        return $this->BaseModel($this->find());
    }
    /**
     * 获取总条数据信息
     * @param array $condition
     * @return array|false|\PDOStatement|string|Model
     */
    public function getCount($condition=[]){
        if($condition){
            return $this->where($condition)->count();
        }
        return $this->count();
    }
    /**
     * 获取多条数据信息
     * @param array $condition 条件
     * @param string $order 排序
     * @param int $limit 限制数
     * @return array
     */
    public function getMulti($condition=[],$order='',$limit=0){
        if($order){
            $this->order($order);
        }
        if($limit){
            $this->limit($limit);
        }
        if($condition){
            return $this->BaseModel($this->where($condition)->select());
        }
        return $this->BaseModel($this->select());
    }
    /**
     * 根据条件删除数据
     * @param array $condition
     * @return bool|int
     */
    public function removeData($condition=[]){
        //删除功能要严格控制
        if(empty($condition)){
            return false;
        }
        return $this->where($condition)->delete();
    }


}
