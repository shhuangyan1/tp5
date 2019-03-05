<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/28
 * Time: 14:16
 */
namespace app\admin\controller;


class Index extends Base{

    public function index(){
        //获取左侧菜单
        $menuList = '';
        $this->assign('menuList',$menuList);
        return $this->fetch($this->controller_name.'/'.$this->action_name);
    }

    public function test(){
        return $this->fetch($this->controller_name.'/'.$this->action_name);
    }
}