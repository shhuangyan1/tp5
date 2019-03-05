<?php
/**
 * Created by PhpStorm.
 * User: vowkin
 * Date: 2017/5/22
 * Time: 21:49
 */

namespace app\common\service;
use think\Config;

class serviceWechat
{
    public function getConfigJs($param=[]){
        $param['companyCode'] = Config::get('account')['companyCode'];
        dump(json_encode($param));
        halt('http://vapi.ahamu.cn/index/WechatJs/getConfigJs?leo_debug=leo',json_encode($param));
        $res = helperService::http_post('http://vapi.ahamu.cn/index/WechatJs/getConfigJs?leo_debug=leo',json_encode($param,true));
        return $res;
    }

    public function getToken($param=[]){
        $param['companyCode'] = Config::get('account')['companyCode'];
        $res = helperService::http_post('http://vapi.ahamu.cn/index/WechatJs/getToken?leo_debug=leo',json_encode($param));
        return $res;
    }

    //微信退款
    public function weChatRefund($param=[]){
        $data=[
            'out_trade_no'=>$param['out_trade_no'],//支付商户编号
            'total_fee'=>$param['total_fee'],//总的付款金额 单位是分
            'refund_fee'=>$param['refund_fee'],//我要退款的金额
            'op_user_id'=>$param['op_user_id'],//操作人id
            'companyCode'=>Config::get('account')['companyCode'],
        ];
        $res = helperService::http_post('http://vapi.ahamu.cn/index/wechat/PlatformRefund.html',json_encode($data,true));
        $res=json_decode($res,true);
        return $res;
    }

    //微信扫码付款
    public function weChatSweepPay($param=[]){
        $data=[
            "order_no"=>$param['order_no'],
            "total_fee"=>$param['total_fee'],
            "body"=>$param['body'],
//            "attach"=>$param['attach'],
//            "goods_tag"=>$param['goods_tag'],
            "limit_pay"=>$param['limit_pay'],
            "spbill_create_ip"=>$param['spbill_create_ip'],
            "companyCode"=>Config::get('account')['companyCode'],
            "auth_code"=> $param['auth_code'],
            "notify_url"=>Config::get('host')."/controller/WechatPay/weChatSweepPay_callback",//异步通知的地址
        ];
        $res = helperService::http_post('http://vapi.ahamu.cn/index/wechat/microPay.html',json_encode($data,true));
        $res=json_decode($res,true);
        return $res;
    }



}