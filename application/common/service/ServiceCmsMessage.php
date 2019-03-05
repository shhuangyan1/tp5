<?php
/**
 * Created by PhpStorm.
 * User: vowkin
 * Date: 2017/5/22
 * Time: 21:49
 */

namespace app\common\service;

use app\common\model\MessageModel;
use think\Config;

class ServiceCmsMessage extends BaseService
{

//`id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增',
//`title` varchar(20) DEFAULT NULL COMMENT '消息标题',
//`content` text COMMENT '内容',
//`user_no` varchar(20) DEFAULT NULL COMMENT '接收会员编号',
//`add_ts` int(11) DEFAULT NULL COMMENT '添加时间',
//`admin_id` varchar(20) DEFAULT NULL COMMENT '发送消息管理员编号',
//`type` tinyint(4) DEFAULT NULL COMMENT '1系统信息，2个人信息',

//    站内信发送
    public $message;

    //后台发送物流信息
    public static function sendLogistics($param=[]){
        $message=new MessageModel();
        $data=[
            'title'=>'退款订单',
            'content'=>'您有一个订单已经退款成功了，微信将在24小时内将金额原路返回到您的账户！',
            'user_no'=>$param['user_no'],
            'add_ts'=>time(),
            'admin_id'=>0,
            'type'=>2,
        ];
        return $message->saveData($data);
    }


}