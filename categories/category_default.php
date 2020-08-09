<?php
/**
  * @name    默认分类页面模板
  * @description 默认的分类页面模板，用户未选择任何分类模板时默认使用此模板
  */
 $borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>

<section class="container">
	
	<div class="content">
		<div class="main">
			<?php 
			echo '<div class="cat-title" style="'.$borderRadius.'" ><h1>', single_cat_title(), $pagedtext.'</h1>'.( $description ? '<div class="cat-desc">' .$description. '</div>':'').'</div>';
			the_module_loader('module_new_posts_excerpt');
			wp_reset_query();
			?>
		</div>
	</div>
	
	<?php get_sidebar() ?>
	
</section>