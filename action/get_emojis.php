<?php
/**
 * @name 评论表情图片
 * @description 评论允许使用 Emoji 表情图片
 */
?>

<script type="text/javascript" language="javascript"> 
/* <![CDATA[ */ 
	function get_the_emoji(tag) { 
		var myField; 
		tag = ' ' + tag + ' '; 
		if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') { 
			myField = document.getElementById('comment'); 
		} else { 
			return false; 
		} 
		if (document.selection) { 
			myField.focus(); 
			sel = document.selection.createRange(); 
			sel.text = tag; 
			myField.focus(); 
		} else if (myField.selectionStart || myField.selectionStart == '0') { 
			var startPos = myField.selectionStart; 
			var endPos = myField.selectionEnd; 
			var cursorPos = endPos; 
			myField.value = myField.value.substring(0, startPos) 
			+ tag 
			+ myField.value.substring(endPos, myField.value.length); 
			cursorPos += tag.length; 
			myField.focus(); 
			myField.selectionStart = cursorPos; 
			myField.selectionEnd = cursorPos; 
		} else { 
			myField.value += tag; 
			myField.focus(); 
		} 
	}
/* ]]> */ 
</script>

<div class="emojis-box" style="display: none;"> 
	
	<a onclick="javascript:get_the_emoji('[滑稽]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_funny.gif" title="滑稽" alt="滑稽" /></a>
	<a onclick="javascript:get_the_emoji('[帅气]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_cool.gif" title="帅气" alt="帅气" /></a>
	<a onclick="javascript:get_the_emoji('[愤怒]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_anger.gif" title="愤怒" alt="愤怒" /></a>
	<a onclick="javascript:get_the_emoji('[大哭]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_cry.gif" title="大哭" alt="大哭" /></a>
	<a onclick="javascript:get_the_emoji('[疑问]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_doubt.gif" title="疑问" alt="疑问" /></a>
	<a onclick="javascript:get_the_emoji('[亲亲]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_kiss.gif" title="亲亲" alt="亲亲" /></a>
	<a onclick="javascript:get_the_emoji('[可怜]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_pitiful.gif" title="可怜" alt="可怜" /></a>
	<a onclick="javascript:get_the_emoji('[点赞]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_praise.gif" title="点赞" alt="点赞" /></a> 
	<a onclick="javascript:get_the_emoji('[大汗]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_sweat.gif" title="大汗" alt="大汗" /></a>
	<a onclick="javascript:get_the_emoji('[流汗]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_perspire.gif" title="流汗" alt="流汗" /></a>
	<a onclick="javascript:get_the_emoji('[开心]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_happy.gif" title="开心" alt="开心" /></a>
	<a onclick="javascript:get_the_emoji('[大笑]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_laughing.gif" title="大笑" alt="大笑" /></a> 
	<a onclick="javascript:get_the_emoji('[偷笑]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_snicker.gif" title="偷笑" alt="偷笑" /></a>
	<a onclick="javascript:get_the_emoji('[苦笑]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_wrysmile.gif" title="苦笑" alt="苦笑" /></a>
	<a onclick="javascript:get_the_emoji('[邪笑]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_evilsmile.gif" title="邪笑" alt="邪笑" /></a>
	<a onclick="javascript:get_the_emoji('[邪恶]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_evil.gif" title="邪恶" alt="邪恶" /></a> 
	<a onclick="javascript:get_the_emoji('[纠结]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_kink.gif" title="纠结" alt="纠结" /></a>
	<a onclick="javascript:get_the_emoji('[无语]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_speechless.gif" title="无语" alt="无语" /></a>
	<a onclick="javascript:get_the_emoji('[鄙视]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_despise.gif" title="鄙视" alt="鄙视" /></a>
	<a onclick="javascript:get_the_emoji('[我喷]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_spray.gif" title="我喷" alt="我喷" /></a> 
	<a onclick="javascript:get_the_emoji('[委屈]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_grievance.gif" title="委屈" alt="委屈" /></a>
	<a onclick="javascript:get_the_emoji('[挖鼻]')"><img src="<?php echo site_url(); ?>/wp-content/themes/qgg/img/emojis/icon_nose.gif" title="挖鼻" alt="挖鼻" /></a>
	
</div>