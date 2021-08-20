<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=11,IE=10,IE=9,IE=8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<link rel="shortcut icon" href="<?php echo home_url() . '/favicon.ico' ?>">
	<!-- 主题多处使用 jquery ，很多插件功能也依赖 jquery ，还是统一放在头部加载吧-->
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/libs/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/libs/require.min.js"></script>
	<?php wp_head();?>
</head>
<body <?php body_class(_bodyclass()); ?>>
	<header id="header">
		<!-- 顶部工具条 -->
		<?php if( !QGG_Options('topbar_off') ){ ?>

			<div class="top-bar">
				<div class="container">
					<!-- 网站公告 -->
					<?php if( QGG_Options('announcement_on') ){ ?>
						<div class="announcement-wrap container">
							<ul id="announcement-list" class="announcement-list">
								<?php $list = explode(PHP_EOL, QGG_Options('announcement_list'));
									foreach ($list as $item) {
										echo '<li><i class="fa fa-volume-up"></i>'.stripslashes( $item ).'</li>';
									} 
								?>	
							</ul>
						</div>
					<?php } ?>

					<!-- 用户登录 -->
					<?php if( QGG_Options('user_center_open') ){ ?>
						<div class="user-wrap">
							<?php if ( !is_user_logged_in() ) {?>
								<?php the_module_loader('module_get_page_user_reset_pwd'); ?>
								<a rel="nofollow" href="javascript:;" class="signin-loader">Hi, 请登录</a>
								<a rel="nofollow" href="javascript:;" class="signup-loader">我要注册</a>
								<a rel="nofollow" rel="nofollow" href="<?php echo module_get_page_user_reset_pwd() ?>">找回密码</a>
							<?php } elseif( is_user_logged_in() ) { ?>
								<?php the_module_loader('module_get_page_user_center'); ?>
								嗨，<?php global $current_user; echo $current_user->display_name; ?>
								<a href="<?php echo module_get_page_user_center(); ?>" class="register">进入会员中心</a>
								<?php if ( is_super_admin() ) { ?>
									<a rel="nofollow" target="_blank" href="<?php echo site_url('/wp-admin/') ?>">后台管理</a>
								<?php } ?>
							<?php } ?>
						</div>
					<?php } ?> 
				</div>
			</div>

		<?php } ?>
		<!-- 公共导航 -->
		<div class="site-nav">
			<div class="container">
				<!-- 手机导航按钮 -->
				<div class="mobile-nav-btn">
					<a class="mobile-nav-ico" href="javascript:;"><i class="fa fa-bars"></i></a>
				</div>
				<!-- 站点 Logo -->
				<?php _site_logo() ?>
				<!-- 品牌文字 -->
				<?php  
					$brand_text = QGG_Options('brand_text');
					if( $brand_text ){
						$brand_text = explode("\n", $brand_text);
						echo '<div class="brand">'.$brand_text[0]. '<br>'.$brand_text[1].'</div>';
					}
				?>
				<!-- 导航内容 -->
				<ul class="nav-list">
					<?php
						!wp_is_mobile() ? the_site_menu('site_nav') : the_site_menu('site_nav_m'); 
					?>
				</ul>
				<!-- 搜索按钮 -->
				<div class="search">
					<a class="search-ico" href="javascript:;"><i class="fa fa-search"></i></a>
				</div>
			</div>
		</div>
		<!-- 彩色条带 -->
		<?php
			$color_bar = QGG_Options("color_bar");
			if( $color_bar ){
				echo '<div class="color-bar" style="background: url(' . $color_bar . ');"></div>';
			}
		?>
		<!-- 搜索框 -->
		<div id="site-search-box" class="site-search">
			<div class="container">
			<form method="get" class="site-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
				<input class="search-input" name="s" type="text" placeholder="输入关键字" value="<?php echo htmlspecialchars($s); ?>">
				<button class="search-btn" type="submit"><i class="iconfont qgg-search"></i></button>
			</form>
			<?php
			if( QGG_Options('search_baidu_open') && QGG_Options('search_baidu_code') ){
				echo '
				<form class="site-search-form">
					<input id="bdcsMain" class="search-input" type="text" placeholder="输入关键字">
					<button class="search-btn" type="submit"><i class="iconfont qgg-search"></i></button>
				</form>';
				echo QGG_Options('search_baidu_code');
			}
			?>
			</div>
		</div>
		
	</header>
	