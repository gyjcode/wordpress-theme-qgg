<?php 
/** 
 * @name 双栏文章列表（类型文章）
 * @description 在引用位置处加载一个指定分类下的文章列表
 */
$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>

<?php
function get_the_post_list_by_cat($cat){
	
	$query_post = array(
		'cat' => $cat,
		'posts_per_page'      => 5,
		'ignore_sticky_posts' => 1,
		'post_status' => 'publish',
		'post_type' => 'post',
		'orderby' => 'date',
	);
	 query_posts($query_post);
	
	$i = 0;
	$_thumb = _get_post_thumbnail();
	while(have_posts()):the_post();
		$i++;
		echo'<li>
			'._get_post_thumbnail().'
			<span class="meta">'.get_the_time('m-d').'</span>
			<a target="_blank" href="'.get_permalink().'" title="'.get_the_title().'-'.get_bloginfo('name').'">'.get_the_title().'</a>
		</li>';
	endwhile;
	wp_reset_query();
	
}
?>

<section class="posts-list-double-s2">
	<div class="posts-list-double-s2-box" style="<?php echo $borderRadius; ?>">
		<div class="title">
			<h3><?php echo QGG_options('cat_posts_list_left_name')?QGG_options('cat_posts_list_left_name'):'请设置一个分类名称'; ?></h3>
		</div>
		<div class="content">
			<ul>
			<?php
				$id_left = QGG_options('cat_posts_list_left_id');
				get_the_post_list_by_cat($id_left);
			?>
			</ul>
		</div>
	</div>
	<div class="posts-list-double-s2-box" style="<?php echo $borderRadius; ?>">
		<div class="title">
			<h3><?php echo QGG_options('cat_posts_list_right_name')?QGG_options('cat_posts_list_right_name'):'请设置一个分类名称'; ?></h3>
		</div>
		<div class="content">
			<ul>
			<?php
				$id_right = QGG_options('cat_posts_list_right_id');
				get_the_post_list_by_cat($id_right);
			?>
			</ul>
		</div>
	</div>
</section>