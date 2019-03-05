<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/28
 * Time: 14:44
 */

namespace app\admin\controller;


use think\Controller;
use Think\Hook;
use think\Session;

class Base extends Controller
{
    protected $controller_name;//控制器
    protected $action_name;//操作
    public $admin_id;//登录者ID
    public $admin_info;//登陆者全部信息

    public function _initialize()
    {
        //判断用户登录态是否过期
        $this->checkLoginState();

        //获取类和方法
        $this->getControllerAndAction();

        //记录用户操作日志
        Hook::add('action_init','app\\admin\\behavior\\LogBehavior');//行为绑定
        Hook::listen('action_init',$params);//添加钩子并监听

    }

    /**
     * 判断用户登录态是否过期
     */
    public function checkLoginState(){
        if(Session::has('admin_info')){
            $this->admin_id = Session::get('admin_info')['admin_id'];
            $this->admin_info = Session::get('admin_info');
        }else{
            redirect('Login/login');
        }
    }


    /**
     * 获取类和方法
     */
    public function getControllerAndAction(){
        $this->controller_name = $this->request->controller();
        $this->action_name = $this->request->action();
        $this->assign('controller_name',$this->controller_name);
        $this->assign('action_name',$this->action_name);
    }


}