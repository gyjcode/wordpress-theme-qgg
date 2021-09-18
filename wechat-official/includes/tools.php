<?php 
/**
 * 工具函数
 */

// 日志函数
if ( !function_exists('_error_log') ) {
    function _error_log($conent) {
        // 微信公众号默认错误
        switch ($conent) {
            case -40001:
                $conent = "-40001    ValidateSignatureError(签名验证错误)";
                break; 
            case -40002:
                $conent = "-40002    ParseXmlError(xml解析失败)";
                break;
            case -40003:
                $conent = "-40003    ComputeSignatureError(sha加密生成签名失败)";
                break;
            case -40004:
                $conent = "-40004    IllegalAesKey(encodingAesKey 非法)";
                break;
            case -40005:
                $conent = "-40005    ValidateAppidError(appid 校验错误)";
                break;
            case -40006:
                $conent = "-40006    EncryptAESError(aes 加密失败)";
                break; 
            case -40007:
                $conent = "-40007    DecryptAESError(aes 解密失败)";
                break;
            case -40008:
                $conent = "-40008    IllegalBuffer(解密后得到的buffer非法)";
                break;
            case -40009:
                $conent = "-40009    EncodeBase64Error(base64加密失败)";
                break;
            case -40010:
                $conent = "-40010    DecodeBase64Error(base64解密失败)";
                break;
            case -40011:
                $conent = "-40011    GenReturnXmlError(生成xml失败)";
                break;
            default:
                $conent = $conent;
        }
        
        $log = WX_ROOT_DIR.'/log_error.txt';
        $logSize = 10240;
        if( file_exists($log) && filesize($log) > $logSize ) {
            unlink($log);
        }
        error_log(date("Y-m-d H:i:s").'  '.$conent.PHP_EOL.PHP_EOL, 3, $log);
    }
}
