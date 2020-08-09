<?php
/**
 * @name 评论表情图片
 * @description 评论允许使用 Emoji 表情图片
 */
?>
<?php
function get_qq_info(){
	header('Content-Type: text/html;charset=utf-8');
	$qqNum=$_GET["qq"];
	if($qqNum!=''){
		$urlPre  = 'http://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?g_tk=1518561325&uins=';  // 获取 QQ 信息API
		$data    = file_get_contents($urlPre.$qqNum);    // 获取 json 数据
		$data    = iconv("GB2312", "UTF-8", $data);      // 转换字符集 GB2312 转 UTF-8
		$pattern = '/portraitCallBack\((.*)\)/is';      // 正则匹配
		preg_match($pattern, $data, $result);
		$result = $result[1];
		echo $result;
	}else{
		echo "请输入qq号！";
	}
}
get_qq_info();	
?>