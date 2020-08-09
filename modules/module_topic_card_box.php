<?php
/**
 * @name 首页推荐盒子模块
 * @description 在引用位置处加载一个推荐模块，可设置连接、背景图片、宣传标题、描述信息等
 */
$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>

<section class="topic-card-box">
	<ul>
		<?php
		for ($i=1; $i <= 4; $i++) {
			echo
			'<li class="topic-card-item" style="'.$borderRadius.'">
				<a class="topic-card-link" target= "_blank" href="'.QGG_options('topic_card_box_link-'.$i).'">
					<div class="focus">
						<div class="mask"></div>
						<img src="'.QGG_options('topic_card_box_img-'.$i).'" alt="">
						<h4>'.QGG_options('topic_card_box_title-'.$i).'</h4>
					</div>
					<p>
						<b>'.QGG_options('topic_card_box_desc01-'.$i).'</b>
						<i>'.QGG_options('topic_card_box_desc02-'.$i).'</i>
					</p>
				</a>
			</li>';
		}
		?>
	</ul>
</section>