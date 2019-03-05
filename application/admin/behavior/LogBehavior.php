<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/1
 * Time: 10:00
 */
namespace app\admin\behavior;

use app\common\model\SysLog;
class LogBehavior {
    public function run(&$params)
    {

            $data = array(
                'IP'=>'localhost',
                'UserName'=>'测试',
                'IPCity'=>'南京',
                'TrueName'=>'李四',
                'DateTime'=>date('Y-m-d H:i:s',time()),
            );
            $SysLogModel=new SysLog();
            $SysLogModel->saveData($data);

    }




}
