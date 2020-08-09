<?php 
/**
 * @name 首页滚动公告 
 * @description 在引用位置处加载一个滚动广告，后台主题设置可输入需要滚动的公告内容，每行一个，支持 HTML 代码
 */
?>

<section class="scroll-announcement container">
	<div id="scroll-announcement-list">
		<div style="list-style: none;">
			<?php $sitemsg = explode(PHP_EOL,QGG_options('scroll_announcement'));
				foreach ($sitemsg as $value) {
					echo '<li><i class="iconfont qgg-horn_filled"></i>'.stripslashes($value).'</li>';
				} 
			?>			
		</div>
	</div>
</section>