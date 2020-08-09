<?php 
/** 
 * @name 双栏文章列表（类型文章）
 * @description 在引用位置处加载一个指定类型下的文章列表，可自主控制列表显示的文章数量。目前支持：热读文章、热评文章、随机文章、最赞文章
 */
$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>

<?php
function get_the_post_list_by_order($order){
	
	if ($order == 'rand'){
		$orderby = 'rand';
		$metakey = '';
	}elseif($order == 'comment'){
		$orderby = 'comment_count';
		$metakey = '';
	}elseif($order == 'view'){
		$orderby = 'meta_value_num';
		$metakey = 'views';
	}elseif($order == 'like'){
		$orderby = 'meta_value_num';
		$metakey = 'likes';
	}elseif($order == 'modified'){
		$orderby = 'modified';
		$metakey = '';
	}
	
	$query_post = array(
		'meta_key' => $metakey, 
		'posts_per_page'      => 5,
		'ignore_sticky_posts' => 1,
		'post_status' => 'publish',
		'post_type' => 'post',
		'orderby' => $orderby,
	);
	 query_posts($query_post);
	
	$i = 0;
	while( have_posts()):the_post();
		$i++;
		$meta = "";
		if( $orderby == 'comment_count' ){
			$meta = '评论('.get_comments_number('0', '1', '%').')';
		}elseif( $orderby =='meta_value_num' && $metakey == 'views'){
			$meta = '阅读 ('._get_post_views().')';
		}elseif( $orderby =='meta_value_num' && $metakey == 'likes'){
			$meta = '喜欢 ('._get_post_likes().')';
		}elseif( $orderby =='modified'){
			$meta = get_the_modified_time('Y-m-d');
		}else{
			$meta = get_the_time('m-d');
		}
		echo'<li>
			<span class="lable lable-'.$i.'">'.$i.'</span>
			<span class="meta">'.$meta.'</span>
			<a target="_blank" href="'.get_permalink().'" title="'.get_the_title().'-'.get_bloginfo('name').'">'.get_the_title().'</a>
		</li>';
	endwhile;
	wp_reset_query();
	
}
?>
<section class="posts-list-double-s1" style="<?php echo $borderRadius; ?>">
	<div class="posts-list-double-s1-box" style="<?php echo $borderRadius; ?>">
		<div class="title">
			<h3><?php echo  QGG_Options('feature_posts_list_left_name'); ?></h3>
		</div>
		<div class="content">
			<ul>
			<?php
			$order_left = QGG_Options('feature_posts_list_left_id');
			get_the_post_list_by_order($order_left);
			?>
			</ul>
		</div>
	</div>					 
	<div class="posts-list-double-s1-box" style="<?php echo $borderRadius; ?>">
		<div class="title">
			<h3><?php echo QGG_Options('feature_posts_list_right_name'); ?></h3>
		</div>
		<div class="content">
			<ul>
			<?php
			$order_right = QGG_Options('feature_posts_list_right_id');
			get_the_post_list_by_order($order_right);
			?>
			</ul>
		</div>
	</div>					
</section>