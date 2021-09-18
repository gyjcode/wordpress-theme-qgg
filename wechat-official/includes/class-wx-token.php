<?php 
/**
 * @ Token 验证文件
 */

class wechatTokenVerify{
    // 验证并返回结果
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];
        
        $token = WX_TOKEN;
        $tmpArr = array ($token, $timestamp, $nonce);
        sort ($tmpArr, SORT_STRING);
        $tmpStr = implode ( $tmpArr );
        $tmpStr = sha1 ( $tmpStr );
        
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
    // 判断结果输出消息
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        } else {
            echo 'Error Signature!';
        }
    }
}
