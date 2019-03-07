<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/1
 * Time: 15:24
 */

namespace app\admin\controller;

use app\common\service\helperService;
use app\common\model\Admin;
use think\Validate;
use think\Session;

class Login extends Base
{
    public function _initialize()
    {
        //获取类和方法
        $this->getControllerAndAction();
    }


    /**
     * 登录页面
     */
    public function login(){
        return $this->fetch($this->controller_name.'/'.$this->action_name);
    }

    /**
     * 验证登录
     */
    public function checkLogin(){
        $params = $this->request->param();
        $rule = [
            ['login_number|用户名','require|max:50','用户名必须输入|名称不可超过100位'],
            ['password|密码','require|max:32','密码必须输入|密码不可超过32位'],
        ];

        $validate = new Validate($rule);

        if(!$validate->check($params)){
            helperService::returnJson(['code'=>400,'msg'=>$validate->getError()]);
        }

        $admin = new Admin();
        $password = strlen($params['password'])>18?trim($params['password']):md5($params['password']);
        $info = $admin->getOne(['login_number'=>$params['login_number'],'password'=>$password,'status'=>1]);
        if(empty($info)){
            helperService::returnJson(['code'=>400,'msg'=>'账号或密码错误']);
        }
        Session::set('admin_info',$info);
        helperService::returnJson(['code'=>200,'msg'=>'验证成功','data'=>['url'=>url('admin/Index/index')]]);
    }

    /**
     * 退出登录
     */
    public function loginOut(){
        Session::delete('admin_info');
        $this->redirect('login');
    }
}