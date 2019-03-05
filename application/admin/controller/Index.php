<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/28
 * Time: 14:16
 */
namespace app\admin\controller;

use app\common\model\Menu;
class Index extends Base{

    public function index(){
        //获取左侧菜单
        $menuList=$this->ShowMenu($this->admin_info['role_id']);
        $this->assign('menuList',$menuList);
        return $this->fetch($this->controller_name.'/'.$this->action_name);
    }

    public function test(){
        return $this->fetch($this->controller_name.'/'.$this->action_name);
    }

    /**
     * 显示菜单
     * @param $role_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function ShowMenu($role_id){
        $menu=new Menu();
        $menu_list=$menu->getRoleMenuShow($role_id,['menu.parent_menu_id'=>0]);
        if(!empty($menu_list)){
            $data=[];
            foreach ($menu_list as $key=>$value){
                $data[$key]=$value;
                $child=$menu->getRoleMenuShow($role_id,['menu.parent_menu_id'=>$value['menu_id']]);
                $data[$key]['child']=$child;
            }
            return $data;
        }else{
            return $parent=[];
        }
    }


}