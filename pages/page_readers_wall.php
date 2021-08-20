<?php 
/**
 * Template name: 读者墙
 * Description:   展示网站读者，按评论排序
 */
get_header();
$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
?>

<div class="container">
	<!-- 页面菜单 -->
	<?php the_module_loader('module_page_menu', false) ?>
	<!-- 页面内容 -->
	<div class="content-wrap">
		<div class="content" style="<?php echo $borderRadius; ?>">
			<?php while (have_posts()) : the_post(); ?>
			<header class="page-header">
				<h1 class="page-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
			</header>
			<article class="page-content">
				<?php the_content(); ?>
			</article>
			<?php endwhile;  ?>

			<div class="readers-wall">
				<?php //readers_wall(1, 6, 100); ?>
				<?php readers_wall( 1, QGG_Options('readwall_limit_time'), QGG_Options('readwall_limit_number') ); ?>
			</div>

			<?php comments_template('', true); ?>
		</div>
	</div>
</div>

<?php
get_footer();
?>


<?php 
function readers_wall( $outer='1', $timer='3', $limit='100' ){
	global $wpdb;
	$sql = $wpdb->get_results("SELECT count(comment_author) AS cnt, user_id, comment_author, comment_author_url, comment_author_email FROM $wpdb->comments WHERE comment_date > date_sub( now(), interval $timer month ) AND user_id!='1' AND comment_author!=$outer AND comment_approved='1' AND comment_type='' GROUP BY comment_author ORDER BY cnt DESC LIMIT $limit");

	$i = 0;
	$html = '';
	foreach ($sql as $comment) {
		$i++;
		$c_url = $comment->comment_author_url;
		if (!$c_url) $c_url = 'javascript:;';
		
		$tt = $i;
		if( $i == 1 ){
			$tt = '金牌读者';
		}else if( $i == 2 ){
			$tt = '银牌读者';
		}else if( $i == 3 ){
			$tt = '铜牌读者';
		}else{
			$tt = '第'.$i.'名';
		}
		if( $i < 4 ){
			$html .= '<a class="item-top item-'.$i.'" target="_blank" href="'. $c_url . '"><h4>【'.$tt.'】<small><i>评论：</i>('. $comment->cnt . ')</small></h4>'.str_replace(' src=', ' data-src=', _get_the_avatar( $user_id=$comment->user_id, $user_email=$comment->comment_author_email) ).'<span><strong>'.$comment->comment_author.'</strong><b>'.$c_url.'</b></span></a>';
		}else{
			$html .= '<a target="_blank" href="'. $c_url . '" title="【'.$tt.'】评论：'. $comment->cnt . '">'.str_replace(' src=', ' data-src=', _get_the_avatar($user_id=$comment->user_id, $user_email=$comment->comment_author_email)).'<span><strong>'.$comment->comment_author.'</strong></span></a>';
		}
		
	}
	echo $html;
};
