<?php
// 微信公众号消息加解密
include(WECHAT_OFFICIAL_DIR.'/includes/class-wechat-msgcrypt.php');

/***  微信端开始 ***/
class Wechat_Official_Reply {
	
	public function __construct($wxCaptcha) {
		$this->captcha = $wxCaptcha;
	}
	public function responseMsg() {
		
		// 公众号验证信息
		$token          = WECHAT_OFFICIAL_TOKEN;
		$encodingAesKey = WECHAT_OFFICIAL_ENCODINGAESKEY;
		$appId          = WECHAT_OFFICIAL_APPID;
		// 获取消息内容
		$msgSignature   = $_GET['msg_signature'];
		$timeStamp      = $_GET['timestamp'];
		$nonce          = $_GET['nonce'];
		$from_xml       = file_get_contents('php://input');
		// 解密消息内容
		$msgCrypt = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
		
		// 第三方收到公众号平台发送的消息
		$decryptMsg = '';
		$errCode = $msgCrypt->decryptMsg($msgSignature, $timeStamp, $nonce, $from_xml, $decryptMsg);
		if ($errCode == 0) {
			$requestContent = $decryptMsg;	
		} else {
			// error_log("解密失败：".$errCode, 3, WECHAT_OFFICIAL_DIR."/wechat_log.txt");
		}
		
		// 如果没有POST数据，则返回
		if (empty($requestContent)) {
			echo '';
			return;
		}else{
			// 如果有POST数据，则解析POST数据(XML格式)
			$wechatObj  = simplexml_load_string($requestContent, 'SimpleXMLElement', LIBXML_NOCDATA);
			$msgType    = trim($wechatObj->MsgType);
			$fromUser   = "" . $wechatObj->FromUserName;
			$toUser     = "" . $wechatObj->ToUserName;
			$content    = trim($wechatObj->Content);
			
			// 内容为“验证码”时，输出验证码信息(XML格式)
			if( $msgType == 'text' ) {
				if( $content == '验证码' ){
					$reply = '您的验证码为：【'.$this->captcha.'】，验证码有效期为'.WECHAT_OFFICIAL_CAPTCHA_TIME.'分钟，请抓紧使用，过期需重新申请！';
					$xmlTextTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>%d</FuncFlag>
						</xml>";
					$replyContent = sprintf($xmlTextTpl, $fromUser, $toUser, time(), $reply, 0);
				} else {
					$reply = WECHAT_OFFICIAL_REPLY_DEFAULT;
					$xmlTextTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>%d</FuncFlag>
						</xml>";
					$replyContent = sprintf($xmlTextTpl, $fromUser, $toUser, time(), $reply, 0);
				}
				
			} else {
				$reply = WECHAT_OFFICIAL_REPLY_DEFAULT;
				$xmlTextTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>%d</FuncFlag>
					</xml>";
				$replyContent = sprintf($xmlTextTpl, $fromUser, $toUser, time(), $reply, 0);
			}
			
			// 发送消息加密
			$encryptMsg = '';
			$errCode = $msgCrypt->encryptMsg($replyContent, $timeStamp, $nonce, $encryptMsg);
			if ($errCode == 0) {
				// 输出消息内容
				echo $replyContent;
			} else {
				error_log("加密失败：".$errCode, 3, WECHAT_OFFICIAL_DIR."/wechat_log.txt");
			}
			
		}
	}
}