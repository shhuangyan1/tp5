<?php
/**
 * Created by PhpStorm.
 * User: vowkin
 * Date: 2017/5/22
 * Time: 21:49
 */

namespace app\common\service;

class ServiceSmsApi extends BaseService
{

//'mobile'=>'mobile',
//'temp_id'=>'number'
    public function sendCode($param=[]){
        $res = $this->Request_call('index/Sms/sendCode',$param);
        return $res;
    }

//'code'=>'number',
//'msg_id'=>'string'
    public function checkCode($param=[]){
        $res = $this->Request_call('index/Sms/checkCode',$param);
        return $res;
    }

//'mobile'=>'mobile',
//'temp_id'=>'number',
//'temp_params'=>'array'
    public function sendMessage($param=[]){
        $res = $this->Request_call('index/Sms/sendMessage',$param);
        return $res;
    }

    /**
     * 订单预支付推送
     * @param $server_name
     * @param $mobile
     * @param $price
     * @return mixed
     */
    public function sendYPayOrder($server_name,$mobile,$price)
    {
        $str='尊敬的王牌车务会员，{{server_name}}的订单已支付{{price}}元，详细信息请在微信支付中查看。';
        $data=[
           'mobile'=>$mobile,
            'temp_id'=>149390,
            'temp_params'=>['server_name'=>$server_name,'price'=>$price],
        ];
        return $this->sendMessage($data);
    }

    /**
     * 订单付尾款支付
     * @param $server_name
     * @param $mobile
     * @param $price
     * @return mixed
     */
    public function sendWPayOrder($server_name,$mobile,$price)
    {
        $str='尊敬的王牌车务会员，{{server_name}}的订单已支付{{price}}元，详细信息请在微信支付中查看。';
        $data=[
            'mobile'=>$mobile,
            'temp_id'=>149390,
            'temp_params'=>['server_name'=>$server_name,'price'=>$price],
        ];
        return $this->sendMessage($data);
    }

    /**
     * 订单预退款推送
     * @param $mobile
     * @param $server_name
     * @param $price
     * @return mixed
     */
    public function sendYRefundOrder($mobile,$server_name,$price)
    {
        $str='尊敬的王牌车务会员，{{server_name}}的订单平台已成功退款{{price}}元，请注意查收。详细信息请在微信支付中查看。';
        $data=[
            'mobile'=>$mobile,
            'temp_id'=>149391,
            'temp_params'=>['server_name'=>$server_name,'price'=>$price],
        ];
        return $this->sendMessage($data);
    }

    /**
     * 订单尾款退款推送
     * @param $mobile
     * @param $server_name
     * @param $price
     * @return mixed
     */
    public function sendWRefundOrder($mobile,$server_name,$price)
    {
        $str='尊敬的王牌车务会员，{{server_name}}的订单平台已成功退款{{price}}元，请注意查收。详细信息请在微信支付中查看。';
        $data=[
            'mobile'=>$mobile,
            'temp_id'=>149391,
            'temp_params'=>['server_name'=>$server_name,'price'=>$price],
        ];
        return $this->sendMessage($data);
    }

    /**
     * 订单已受理，请支付尾款
     * @param $mobile
     * @param $server_name
     * @param $price
     * @return mixed
     */
    public function sendOrderOffer($mobile,$server_name,$price)
    {
        $str='尊敬的王牌车务会员，您预约的{{server_name}}订单平台已受理，需要支付尾款金额{{price}}元。详细信息请在公众号中查看。';
        $data=[
            'mobile'=>$mobile,
            'temp_id'=>149392,
            'temp_params'=>['server_name'=>$server_name,'price'=>$price],
        ];
        return $this->sendMessage($data);
    }

    /**
     * 订单资料未通过审核
     * @param $mobile
     * @param $file_name
     * @return mixed
     */
    public function sendFileNo($mobile,$file_name)
    {
        $str='尊敬的王牌车务会员，很抱歉，您提交的{{file_name}}没有通过审核，请到公众号中重新上传资料信息，如有问题请及时联系客服解决。谢谢！';
        $data=[
            'mobile'=>$mobile,
            'temp_id'=>149393,
            'temp_params'=>['title'=>$file_name],
        ];
        return $this->sendMessage($data);
    }

    /**
     * 订单资料审核全部通过
     * @param $mobile
     * @return mixed
     */
    public function sendFileOk($mobile)
    {
        $str='尊敬的会员，您的资料已全部审核成功，详细信息请在公众号中查看。';
        $data=[
            'mobile'=>$mobile,
            'temp_id'=>149394,
            'temp_params'=>['1'=>1],
        ];
        return $this->sendMessage($data);
    }



}