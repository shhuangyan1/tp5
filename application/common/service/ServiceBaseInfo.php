<?php

namespace app\common\service;

class ServiceBaseInfo
{

    public static  function returnEmail($studentName,$data)
    {

        $public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDkMateIwsasBD9NT2n5iUc2RM5
jERDyComwLyOCn0XIBNW246j2yd/yhGXFFKLJ2R2wqGn7RON+tE7901eDkqsqAX9
vFZ3zWBHUczWzwSS4htSS047XJOWk+cKuRwPHxX0hZwyUjBMLW7Ig2jDNGqI7MO2
KgUvqZxoMrB4PQ7OfQIDAQAB
-----END PUBLIC KEY-----';

        $data = json_encode([
            'send_name' => '上海傲盈网络科技有限公司',
            'theme_name' => '信息反馈',
            "content" => '您好，'.$studentName.'在'.$data.'进行了非法操作',
            "reply_mail" => 'leo.xie@52zhainan.com',
            'to_mail' => [
//                ['mail' => 'leo.xie@Kuyunnet.com', 'name' => 'leo'],
                ['mail' => 'nan.zhang@Kuyunnet.com', 'name' => 'leo'],
                ['mail' => 'rong.wang@Kuyunnet.com', 'name' => 'leo'],
            ]
        ]);

        $moniOpenssl = helperService::http_post('http://wechat2.vowkin.com/base/moniOpenssl',$data,true);
        $data = $moniOpenssl;

        helperService::http_post('http://wechat2.vowkin.com/mail/sendMail',$data);
        return true;
    }

    /**
     * data['level'],data['code']"省传两位，市传4位，区传6位"
     * @param $data
     * @return bool
     */
    public static function getAddress($data){
        $data['ssl_ts'] =time();
        $data=json_encode($data);
        $moniOpenssl = helperService::http_post('http://wechat2.vowkin.com/base/moniOpenssl',$data,true);
        $data = $moniOpenssl;
        $res = helperService::http_post('http://wechat2.vowkin.com/Cityarea/getAreaList',$data,true);
        $res = json_decode($res,true);
        return $res['data'];
    }


}
