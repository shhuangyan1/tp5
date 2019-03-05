<?php
/**
 * Created by PhpStorm.
 * User: vowkin
 * Date: 2017/5/20
 * Time: 11:27
 */
namespace app\common\service;

class interfaceService
{
    public static $errorInfo = null;

    /**
     * 微信二维码接口
     * @param $content
     * @param $note
     * @return string
     */
    public static  function returnErWeiCode($content,$note="张三,000000001"){
        $data = json_encode(['ssl_ts'=>time(),'content'=>$content,'level'=>'L',"size"=>'10',"is_down"=>1,"note"=>$note]);
        $moniOpenssl = helperService::http_post('http://wechat2.vowkin.com/base/moniOpenssl',$data,true);
        $data = urlencode($moniOpenssl);
        return "http://wechat2.vowkin.com/Qrcode/QR?encrypted={$data}";
    }

    /**
     * 微信一维码接口
     * @param $content
     * @param $note
     * @return string
     */
    public static  function returnYiWeiCode($content,$note="张三,000000001"){
        $data = json_encode(['ssl_ts'=>time(),'content'=>$content,'level'=>'M',"size"=>'3',"is_down"=>1,"note"=>$note]);
        $moniOpenssl = helperService::http_post('http://wechat2.vowkin.com/base/moniOpenssl',$data,true);
        $data = urlencode($moniOpenssl);
        return "http://wechat2.vowkin.com/Barcode/Bar?encrypted={$data}";
    }

}