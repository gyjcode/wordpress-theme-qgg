<?php
/**
 * Template Name: 站点地图
 * Description:   站点地图页面，HTML格式
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
		<title>SiteMap - <?php bloginfo('name'); ?></title>
		<meta name="keywords" content="SiteMap,<?php bloginfo('name'); ?>" />
		<meta name="copyright" content="<?php bloginfo('name'); ?>" />
		<link rel="canonical" href="<?php echo get_permalink(); ?>" />
		<style type="text/css">
			body {
				margin: 0;
				font-family: Verdana;
				font-size: 12px;
				color: #000;
				background: #fff;
			}
			img {
				border:0;
			}
			li {
				margin-top: 8px;
			}
			.page {
				padding: 4px; 
				border-top: 1px #eee solid
			}
			.author {
				border-top: 1px #ddddee solid;
				padding: 5px; 
				background-:#eef; 
			}
			#nav, #content, #footer {
				clear: both;
				width: 95%; 
				margin: auto;
				margin-top: 10px;
				padding: 8px;
				border: 1px solid #eee; 
			}
		</style>
	</head>
	<body vlink="#333333" link="#333333">
		<h2 style="text-align: center; margin-top: 20px"><?php bloginfo('name'); ?>'s SiteMap </h2>
		<center></center>
		<div id="nav">
			<a href="<?php bloginfo('url'); ?>/"><strong><?php bloginfo('name'); ?></strong></a> &raquo; <a href="<?php echo get_permalink(); ?>">站点地图</a>
		</div>
		
		<div id="content">
			<h3>最新文章</h3>
			<ul>
				<?php
				$previous_year = $year = 0;
				$previous_month = $month = 0;
				$ul_open = false;
				$myposts = get_posts('numberposts=-1&orderby=post_date&order=DESC');
				foreach($myposts as $post) :
				?>
					<li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank"><?php the_title(); ?></a></li>
				<?php
				endforeach;
				?>
			</ul>
		</div>
		<div id="content">
			<li class="categories">分类目录<ul><?php wp_list_categories('title_li='); ?></ul></li>
		</div>
		<div id="content">
			<li class="categories">单页面</li>
			<?php wp_page_menu( $args ); ?>
		</div>
		<div id="footer">
			查看博客首页: <strong><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></strong></div><br />
			<center>
				<div style="text-algin: center; font-size: 11px">
					最新更新: <?php $last = $wpdb->get_results("SELECT MAX(post_modified) AS MAX_m FROM $wpdb->posts WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')");$last = date('Y-m-d G:i:s', strtotime($last[0]->MAX_m));echo $last; ?><br /><br />
				</div>
			</center>
		<center>
		<div style="text-algin: center; font-size: 11px">
			Powered by <strong><a href="http://blog.quietguoguo.com/" target="_blank">蝈蝈要安静</a></strong>&nbsp;
			&copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url');?>/" style="cursor:help"><?php bloginfo('name');?></a> 版权所有.<br /><br />
		</div>
		</center>
	</body>
</html>