<?php get_header(); ?>

<section class="container">
	<div class="content">
		<div class="main">
			<?php 
			$pagedtext = '';
			if( $paged && $paged > 1 ){
				$pagedtext = ' <small>第'.$paged.'页</small>';
			}
			
			echo '<div class="tag-title"><h1>标签：<span>', single_tag_title(), '</span></h1>'.$pagedtext.'</div>';
			
			the_module_loader('module_new_posts_excerpt');
			
			wp_reset_query();
			?>
		</div>
	</div>
	<?php get_sidebar(); ?>
</section>

<?php get_footer(); ?>