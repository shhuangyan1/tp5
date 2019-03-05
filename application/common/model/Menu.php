<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/5
 * Time: 10:05
 * work: 菜单管理控制器
 */

namespace app\common\model;


class Menu extends Base
{
    /**
     * 获取角色菜单的展现形式
     * @param $role_id 角色id
     * @param array $where 条件
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRoleMenuShow($role_id,$where=[]){
        $where['menu.is_show']=1;//菜单显示
        $where['menu.status']=1;//菜单状态正常
        if($where){
            $this->where($where);
        }
        $join=[
            ['role_menu rm','rm.menu_id = menu.menu_id and rm.role_id ='.$role_id],
        ];
        $field="menu.*,rm.role_id rm_role,rm.menu_id rm_menu";
        $this->alias('menu');
        $this->field('menu.*,rm.role_id');
        $this->join($join);
        $this->order('parent_menu_id asc,list_order desc');
        $res= $this->field($field)->select();
        return $this->BaseModel($res);

    }
}