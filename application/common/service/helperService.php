<?php
/**
 * Created by PhpStorm.
 * User: vowkin
 * Date: 2017/5/20
 * Time: 11:27
 */
namespace app\common\service;

use app\common\model\UserModel;

class helperService
{
    public static $errorInfo = null;
    public static $private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDkMateIwsasBD9NT2n5iUc2RM5jERDyComwLyOCn0XIBNW246j
2yd/yhGXFFKLJ2R2wqGn7RON+tE7901eDkqsqAX9vFZ3zWBHUczWzwSS4htSS047
XJOWk+cKuRwPHxX0hZwyUjBMLW7Ig2jDNGqI7MO2KgUvqZxoMrB4PQ7OfQIDAQAB
AoGADxumv+XMD2lGxqzmtx4KqP1KQ44g2uf+unAaS8EwBP7vqNmCeSDCXbiQL5N9
346tHRvHvil5GPZZMgIukiKaGPJJL0lG/+sq0ZawsApO/ApCt2MLwqCS7qu7H4R6
6nf6D1caAvt+AZu+6YPMlghRceDpV2cxDgas1Bt9zgZJLrUCQQD0jx88b+pBs0YX
XK+BV85wTlbaByDMsgbJ6sMjtamkzHGyJ25PZmY/MAF98+IDcci02jbXOeRm/KnG
3ImibkujAkEA7t6Oli+GKSlkBdped+rQhmfvty8NFyJybU8Zw6FXFovd+Jru/fOL
yyssk7L1FHJWeESTDpA47WC+soPHqKgfXwJBAJzqH/6lSEczgeuHesygzEJe4Xcv
T6pHJ/fye5az/s9QpjrK9gpYB47PfIWWMBRJs5/my305FgXGZCDGbEEeR8UCQEtA
JZ8+nX8+INqPLo+Mk+Cjwart0avmGDJDZxRwMWVS7ryw4nVyUinREhv9lqO4WXFN
+R3vZV+yyKCoTy/ctvUCQH4XkYv9mfPJfaYDL9E0vCsWSEBWiaCTM+Oji6vCuOJO
yz+kJ+SlcLhSOAc28FlIKA5FTC/H3xYOQW0BYpJGV4I=
-----END RSA PRIVATE KEY-----';

    /**
     * @param $url
     * @param string $param
     * @param bool $is_ssl 是否启用ssl请求
     * @return mixed
     */
    public static function http_post($url, $param='',$is_ssl=false) {
        $url = str_replace(' ', '', $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if($is_ssl){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER,['Content-Type: application/json; charset=utf-8','Content-Length:' . strlen($param)]);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
             self::$errorInfo = [
                 'msg' =>'Curl Errno : ' . curl_error($ch),
                 'line'=>__LINE__,
                 'file'=>__FILE__
             ];
         }
        curl_close($ch);
        return $tmpInfo;
    }

    public static function voice_download($mediaId){
        $serviceWechat = new serviceWechat();
        $res = $serviceWechat->getToken();
        $res = json_decode($res,true);
        if(!isset($res['code']) || $res['code'] != 200){
            die('获取token数据失败，请重新尝试！');
        }

        $token = $res['data'];

        $path = './uploads/wxVoice';
        $filename = time().rand(1111,9999).".amr";

        $filePath =  "$path/$filename";

        self::downAndSaveFile("https://qyapi.weixin.qq.com/cgi-bin/media/get?access_token=$token&media_id=$mediaId","$filePath");

        return substr($filePath,1);
    }

    //根据URL地址，下载文件
    private static function downAndSaveFile($url,$savePath){
        ob_start();
        readfile($url);
        $img  = ob_get_contents();
        ob_end_clean();
        $fp = fopen("$savePath", 'a');
        fwrite($fp, $img);
        fclose($fp);
    }
    public static function downloadFile($file){
    $file_name = $file;
    $mime = 'application/force-download';
    header('Pragma: public'); // required
    header('Expires: 0'); // no cache
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private',false);
    header('Content-Type: '.$mime);
    header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
    header('Content-Transfer-Encoding: binary');
    header('Connection: close');
    readfile($file_name); // push it out
    exit();
}


    //组件分页样式
    public static function createPage($count,$page,$url,$params,$pageSize=10,$offset=5){
        $countPage = ceil($count/$pageSize);
        $pageString = "";
        //固化边界
        if($page<1){
            $page = 1;
        }
        if($offset < 1){
            $offset = 1;
        }
        if($page> $countPage){
            $page = $countPage;
        }
        //左右偏移页面
        $startPage = $page-$offset;
        $endPage   = $page+$offset;
        //判断最小值(因为$page和$offset都不小于1所以最大值不会小于1不做判断，仅判断最小值即可)
        if($startPage < 1){
            $startPage = 1;
        }
        //判断最大值(同理最小值没有超过$countPage的可能)
        if($endPage > $countPage){
            $endPage = $countPage;
        }

        for($i=$startPage;$i<=$endPage;$i++){
            if(intval($page) == $i){
                $pageString .= "<a class='active' href='".url($url,array_merge($params,['page'=>$i]))."'>{$i}</a>";
                continue;
            }
            $pageString .= "<a href='".url($url,array_merge($params,['page'=>$i]))."'>{$i}</a>";
        }

        $pageString .= "  共有{$count}条数据，共{$countPage}页";

        return $pageString;
    }


    /**
     * 统一输出源
     * @param $data
     * @param $param
     */
    public static  function returnJson($data,$param=[]){

        die(json_encode($data,JSON_UNESCAPED_UNICODE));
    }

    /**
     * 生成唯一编号
     * @return string
     */
    public static function createOrderNum(){
        $key = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 4, 5);
        $key.=rand(100,999);
        usleep(1000);
        return $key;
    }
    /**
     * 生成会员编号
     * @return string
     */
    public static function createUserNum($i=0){

        $userModel=new UserModel();
        $key= helperService::getRandomString(6,'12356789');
        $userInfo=$userModel->getOne(['user_no'=>$key]);
        if($userInfo){
            $i++;
            if($i<=10){
                createUserNum($i);
            }
        }
        return $key;
    }

    /**
     * 二维数组排序
     * @param array $list
     * @param $sort
     * @return array
     */
    public static function arraySort($list=[],$value,$sort){
        foreach ($list as $item) {
            $k[]=$item[$value];
        }
        if($sort=='asc'){
            array_multisort($k,SORT_ASC,$list);
        }elseif($sort=='desc'){
            array_multisort($k,SORT_DESC,$list);
        }
        return $list;
    }


    /**
     * 创建文件目录
     * @param $path
     * @return bool
     */
    protected function checkPath($path)
    {
        if (is_dir($path)) {
            return true;
        }
        if (mkdir($path, 0755, true)) {
            return true;
        } else {
            $this->error = "目录 {$path} 创建失败！";
            return false;
        }
    }

    /**
     * 文件上传
     * @param $name
     * @param string $path_name
     * @param string $param_img
     * @return string
     */
    public static function uploadImage($name,$path_name='',$param_img=''){
        if(request()->isHead()){
            return '';
        }
        $file = request()->file($name);
        if($file) {
            if(empty($param_img)){
                $info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .$path_name);
                $save_name = DS."uploads".DS.$path_name.DS.$info->getSaveName();
                return $save_name;
            }else{
                return $param_img;
            }
        }else{
            return '上传文件出错';
        }
    }

    /**
     * 产生随机的字符串
     * @param $len
     * @param null $chars
     * @return string
     */
    public static function getRandomString($len, $chars=null)
    {
        if (is_null($chars)) {
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        }
        mt_srand(10000000*(double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }


    /**
     * 替换周
     * @param $week
     * @param $prefix
     * @return string
     */
    public static function replaceWeek($week,$prefix="周"){
        switch($week){
            case 1:
                $item = $prefix.'一';
                break;
            case 2:
                $item = $prefix.'二';
                break;
            case 3:
                $item = $prefix.'三';
                break;
            case 4:
                $item = $prefix.'四';
                break;
            case 5:
                $item = $prefix.'五';
                break;
            case 6:
                $item = $prefix.'六';
                break;
            default:
                $item = $prefix.'日';
                break;
        }

        return $item;
    }


    /**
     * 取出list中的，两个字段组成text,value的json输出给选择控件
     * @param $list
     * @param $text_key
     * @param $value_key
     * @return string
     */
    public static function transListToJson($list,$text_key,$value_key){
        if(empty($list)){
            return json_encode([]);
        }
        $JsonArr = [];
        foreach($list as $item){
            $arr = [];
            $arr['text'] = $item[$text_key];
            $arr['value'] = $item[$value_key];
            $JsonArr[] = $arr;
        }
        return json_encode($JsonArr);
    }

    /**
     * 创建连续的时间数组
     * @param $startDayTime
     * @param $len
     * @return array
     */
    public static function createWeekDay($startDayTime,$len){
        $arrWeekDay = [];
        for($i=0;$i<$len;$i++){
            $arr = [];
            $arr['format_time'] = $startDayTime+$i*86400;
            $arr['week'] = date('w',$arr['format_time']);
            $arr['znWeek'] = helperService::replaceWeek($arr['week']);
            $arr['day']  = date('d',$arr['format_time']);
            $arrWeekDay[] = $arr;
        }
        return $arrWeekDay;
    }

    /**
     * 模型数据转数组
     * @param $data
     * @return mixed
     */
    public static function modelDataToArr($data){
        return json_decode(json_encode($data),true);
    }

    /**
     * 参数过滤
     * @param array $params
     * @param $arr
     * @return array
     */
    public static function array_filter($params=[],$arr){
        $data=[];
        foreach ($arr as $k=>$v){
            if(isset($params[$v])){
                $data[$v]=$params[$v];
            }
        }
        return $data;
    }

}