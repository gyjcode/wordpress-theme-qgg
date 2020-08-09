<?php
/**
 * @ name 指定分类友情链接模块
 * @ description 在引用位置处加载一个指定分类的友情链接
 */
?>

<section class="friendly-links container">
	<div class="friendly-links-box">
		<ul>
			<label>友情链接：</label>
			<?php 
			$links_cat = QGG_Options('home_friendly_links');
			wp_list_bookmarks(array(
				'categorize'       => false,
				'title_li'         => '',
				'category'         => $links_cat,
				'category_orderby' => 'SLUG',
				'category_order'   => 'ASC',
				'orderby'          => 'RATING',
				'order'            => 'DESC',
				'show_name'        => false,
				'show_description' => false,
				'show_images'      => false,
			)); 
			?>
		</ul>
	</div>
</section>