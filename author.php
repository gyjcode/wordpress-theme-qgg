<?php

get_header();

global $wp_query;
$curauth = $wp_query->get_queried_object();
?>

<section class="container">
	<div class="content-wrap">
		<div class="content">
			<?php 
			$pagedtext = '';
			if( $paged && $paged > 1 ){
				$pagedtext = ' <small>第'.$paged.'页</small>';
			}
			
			echo '<div class="author-title">';
				echo '<div class="avatar">'._get_the_avatar($curauth->ID, $curauth->user_email).'</div>';
				echo '<h1  class="name">'.$curauth->display_name.'的文章</h1>';
				echo '<div class="desc">'.get_the_author_meta('description', $curauth->ID).'</div>';
			echo '</div>';
			
			the_module_loader('module_new_posts_excerpt');
			
			wp_reset_query();
			?>
		</div>
	</div>
	<?php get_sidebar() ?>
</section>

<?php get_footer(); ?>