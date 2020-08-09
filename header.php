
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=11,IE=10,IE=9,IE=8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<link rel="shortcut icon" href="<?php echo home_url() . '/favicon.ico' ?>">
	<!-- 主题多处使用 jquery ，很多插件功能也依赖 jquery ，还是统一放在头部加载吧-->
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/libs/jquery.min.js"></script>
	<?php wp_head();?>
</head>
<body <?php body_class(_bodyclass()); ?>>
	<header id="header">
		<?php if( !QGG_Options('topbar_off') ){ ?>
		<div class="top-bar">
			<div class="container">
				<div class="content">
					<!-- 首页滚动公告 -->
					<?php  
					if( QGG_options('scroll_announcement_open') ){
						the_module_loader('module_scroll_announcement');
					}
					?>
					
					<div class="user-wrap">
					<?php if( QGG_Options('user_center_open') ){ ?>
						<?php if( !is_user_logged_in() ) {?>
							<?php the_module_loader('module_get_page_user_reset_pwd'); ?>
							<a rel="nofollow" href="javascript:;" class="signin-loader">Hi, 请登录</a>
							<a rel="nofollow" href="javascript:;" class="signup-loader">我要注册</a>
							<a rel="nofollow" rel="nofollow" href="<?php echo module_get_page_user_reset_pwd() ?>">找回密码</a>
						<?php }elseif( is_user_logged_in() ){ ?>
							<?php the_module_loader('module_get_page_user_center'); ?>
							嗨，<?php global $current_user; echo $current_user->display_name; ?>
							<a href="<?php echo module_get_page_user_center(); ?>" class="register">进入会员中心</a>
							<?php if( is_super_admin() ){ ?>
								<a rel="nofollow" target="_blank" href="<?php echo site_url('/wp-admin/') ?>">后台管理</a>
							<?php } ?>
						<?php } ?>
					<?php } ?> 
					</div>
				</div>	
			</div>
		</div>
		<?php } ?>
		
		<div class="site-nav clearfix">
			<div class="container">
				
				<div class="mobile-nav-btn">
					<a class="mobile-nav-ico" href="javascript:;"><i class="iconfont qgg-list"></i></a>
				</div>
				
				<?php the_site_logo() ?>
				<?php  
					$brand_text = QGG_Options('brand_text');
					if( $brand_text ){
						$brand_text = explode("\n", $brand_text);
						echo '<div class="brand">' . $brand_text[0] . '<br>' . $brand_text[1] . '</div>';
					}
				?>
				<div class="nav-cover">
					<ul>
					<?php
					if ( !wp_is_mobile() ) {
						the_site_menu('site_nav'); 
					}else{
						the_site_menu('site_nav_m');
					}
					?>
					</ul>
				</div>
				
				<div class="site-search">
					<a class="site-search-ico" href="javascript:;"><i class="iconfont qgg-search"></i></a>
				</div>
			</div>
		</div>
		<?php
			$color_bar = QGG_Options("color_bar");
			if( $color_bar ){
				echo '<div class="color-bar" style="background: url(' . $color_bar . ');"></div>';
			}
		?>
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
	