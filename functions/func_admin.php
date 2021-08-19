<?php
/**
  * WordPress 源代码修改
  */
 
// 隐藏顶部工具条
add_filter('show_admin_bar', '__return_false');

// 后台加载 CSS 文件
function my_backstage_css() {
	wp_enqueue_style( "backstage_css", THEME_URI.'/assets/css/backstage.css' );
}
add_action('admin_init', 'my_backstage_css');

// 后台编辑器 CSS 文件
add_editor_style( THEME_URI.'/assets/css/editor-style.css' );

// 后台加载 JS 文件
function my_backstage_js() {
	wp_register_script( 'backstage_js', THEME_URI.'/assets/js/backstage.js' );  
	wp_enqueue_script( 'backstage_js' ); 
}
add_action( 'admin_enqueue_scripts', 'my_backstage_js' );

// 添加链接管理
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// 移除头部无用代码
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'wp_generator');

// 移除自带表情
remove_action( 'admin_print_scripts' ,	'print_emoji_detection_script');
remove_action( 'admin_print_styles'  ,	'print_emoji_styles');
remove_action( 'wp_head'             ,	'print_emoji_detection_script',	7);
remove_action( 'wp_print_styles'     ,	'print_emoji_styles');
remove_filter( 'the_content_feed'    ,	'wp_staticize_emoji');
remove_filter( 'comment_text_rss'    ,	'wp_staticize_emoji');
remove_filter( 'wp_mail'             ,	'wp_staticize_emoji_for_email');

// 禁用块编辑器
add_filter('use_block_editor_for_post', '__return_false');
remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );

//拓展 WordPress 功能
add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'status', 'video'));
add_theme_support('post-thumbnails');
add_theme_support('title-tag');

//取消自己 PING 自己
add_action( 'pre_ping', 'no_self_ping' );
function no_self_ping(&$links) {
	$home = get_option('home');
	foreach ($links as $l => $link )
		if (0 === strpos($link, $home))
	unset($links[$l]);
}

// 添加菜单,注册导航
if ( !function_exists( '_register_the_nav_menu' ) ) {
 
	function _register_the_nav_menu(){
		register_nav_menus( array(
			'top_nav'    => __('顶部菜单', 'qgg'),
			'site_nav'   => __('导航菜单', 'qgg'),
			'site_nav_m' => __('手机导航', 'qgg'),
			'bottom_nav' => __('底部菜单', 'qgg'),
			'page_nav'   => __('侧栏导航', 'qgg')
		) );
	}
	add_action( 'after_setup_theme', '_register_the_nav_menu', 0 );
}

// 页面菜单生成
function the_nav_menu($location = 'nav') {
	echo str_replace("</ul></div>", "", preg_replace("/<div[^>]*><ul[^>]*>/", "", wp_nav_menu(array('theme_location' => $location, 'echo' => false))));
}

// 注册侧栏小工具
if ( !function_exists('_register_the_nav_sidebar')) {
	function _register_the_nav_sidebar(){
		$sidebars = array(
			'header'         => '公共头部',
			'footer'         => '公共底部',
			'home'           => '首页侧栏',
			'single'         => '文章侧栏',
			'category'       => '分类侧栏',
			'tag'            => '标签侧栏',
			'search'         => '搜索侧栏',
			'single_video'   => '视频侧栏',
			'single_product' => '产品侧栏',
		);
		$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
		
		foreach ($sidebars as $key => $value) {
			register_sidebar(array(
				'name'          => $value,
				'id'            => $key,
				'before_widget' => '<div id="%1$s" class="widget %2$s" style="'.$borderRadius.'">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>'
			));
		};
	}
	add_action( 'widgets_init', '_register_the_nav_sidebar' );
}

// 扩展文章编辑器
function _add_editor_buttons($buttons) {
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	$buttons[] = 'del';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'copy';
	$buttons[] = 'paste';
	$buttons[] = 'cut';
	$buttons[] = 'image';
	$buttons[] = 'anchor';
	$buttons[] = 'backcolor';
	$buttons[] = 'wp_page';
	$buttons[] = 'charmap';
	return $buttons;
}
add_filter("mce_buttons_2", "_add_editor_buttons");

// 网站用户仅查看自己上传的文件
add_action('pre_get_posts','_restrict_media_library');
function _restrict_media_library( $wp_query_obj ) {
	 global $current_user, $pagenow;
	 if( !is_a( $current_user, 'WP_User') )
		 return;
	 if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
		 return;
	 if( !current_user_can('manage_media_library') )
		 $wp_query_obj->set('author', $current_user->ID );
	 return;
}
