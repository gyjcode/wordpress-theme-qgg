<?php 
/**
 * @ Token 验证文件
 */

class wechatTokenVerify{
    
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];
        
        $token = WECHAT_OFFICIAL_TOKEN;
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
