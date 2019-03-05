<?php
/**
 * Created by PhpStorm.
 * User: vowkin
 * Date: 2017/5/22
 * Time: 13:15
 */
namespace app\common\service;

class BaseService
{
    public  $mall_host = '';
    private $_token = null;
    private $_account=null;
    public function __construct()
    {
        $this->_account=config('account');
        $this->mall_host = config('api_host');
        $this->_token = \cookie('vpi_token');
        if(!$this->_token){
            $this->getToken($this->_account);
        }
    }

    protected function Request_call($url, $param=[]) {
        if(!is_array($param)){
            throw new \RuntimeException('HTTP POST 参数必须是数组');
        }
        $param['token'] = $this->_token;
        $param['companyCode']=$this->_account['companyCode'];
        $param = json_encode($param);
        $url = $this->mall_host.$url;
        $tmpInfo = helperService::http_post($url,$param);
        $tmpInfo=json_decode($tmpInfo,true);
        if( 40044 == $tmpInfo['code']){
            $this->getToken($this->_account);
            $param = json_decode($param,true);
            $param['token'] = $this->_token;
            $param = json_encode($param);
            $tmpInfo = helperService::http_post($url,$param);
        }
        return $tmpInfo;
    }

    //获取token
    public function getToken($param){
        $res = $this->Request_call('index/auth/authorization',$param);
        $token = $res['data']['access_token'];
        setcookie('vpi_token',$token,time()+7200);
        $this->_token = $token;
    }
}