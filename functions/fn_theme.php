<?php
/**
 * 主题通用函数
 */

// 隐藏顶部工具条
add_filter('show_admin_bar', '__return_false');
 
// 屏蔽默认表情
// WordPress Emoji Delete
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'disable_emojis' );
 // 自定义 Emoji
function custom_init_smilies(){
	global $wpsmiliestrans;
	$wpsmiliestrans = array(
		'[滑稽]' => 'icon_funny.gif',
		'[帅气]' => 'icon_cool.gif',
		'[愤怒]' => 'icon_anger.gif',
		'[大哭]' => 'icon_cry.gif',
		'[疑问]' => 'icon_doubt.gif',
		'[亲亲]' => 'icon_kiss.gif',
		'[可怜]' => 'icon_pitiful.gif',
		'[点赞]' => 'icon_praise.gif',
		'[大汗]' => 'icon_sweat.gif',
		'[流汗]' => 'icon_perspire.gif',
		'[开心]' => 'icon_happy.gif',
		'[大笑]' => 'icon_laughing.gif',
		'[偷笑]' => 'icon_snicker.gif',
		'[苦笑]' => 'icon_wrysmile.gif',
		'[邪笑]' => 'icon_evilsmile.gif',
		'[邪恶]' => 'icon_evil.gif',
		'[纠结]' => 'icon_kink.gif',
		'[无语]' => 'icon_speechless.gif',
		'[鄙视]' => 'icon_despise.gif',
		'[我喷]' => 'icon_spray.gif',
		'[委屈]' => 'icon_grievance.gif',
		'[挖鼻]' => 'icon_nose.gif'
	);
}
add_action( 'all', 'custom_init_smilies' );
// 添加自定义表情路径
function custom_emojis_src ($img_src, $img){
	return get_template_directory_uri().'/img/emojis/'.$img;
}
add_filter('smilies_src', 'custom_emojis_src', 10, 2);

/**==================== 禁用谷歌字体 ====================*/
// Remove Open Sans that WP adds from frontend
if (!function_exists('remove_wp_open_sans')){
	function remove_wp_open_sans() {
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', false );
		wp_enqueue_style('open-sans','');
	}
	add_action('wp_enqueue_scripts', 'remove_wp_open_sans');
	// Uncomment below to remove from admin
	add_action('admin_enqueue_scripts', 'remove_wp_open_sans');
}

// 整站头部代码加载
add_action('wp_head', '_the_head');
function _the_head() {
	_the_keywords();
	_the_description();
	_the_head_code();
	_the_head_css();
	_post_views_record();
}
// 获取网站页面关键字
function _the_keywords() {
	global $new_keywords;
	if( $new_keywords ) {
		echo "<meta name=\"keywords\" content=\"{$new_keywords}\">\n";
		return;
	}

	global $s, $post;
	$keywords = '';
	if (is_singular()) {
		if (get_the_tags($post->ID)) {
			foreach (get_the_tags($post->ID) as $tag) {
				$keywords .= $tag->name . ', ';
			}
		}
		foreach (get_the_category($post->ID) as $category) {
			$keywords .= $category->cat_name . ', ';
		}
		$keywords = substr_replace($keywords, '', -2);
		$the = trim(get_post_meta($post->ID, 'keywords', true));
		if ($the) {
			$keywords = $the;
		}
	} elseif (is_home()) {
		$keywords = QGG_Options('home_keywords');
	} elseif (is_tag()) {
		$keywords = single_tag_title('', false);
	} elseif (is_category()) {

		global $wp_query; 
		$cat_ID = get_query_var('cat');
		$keywords = _get_tax_meta($cat_ID, 'keywords');
		if( !$keywords ){
			$keywords = single_cat_title('', false);
		}
	
	} elseif (is_search()) {
		$keywords = esc_html($s, 1);
	} else {
		$keywords = trim(wp_title('', false));
	}
	if ($keywords) {
		echo "<meta name=\"keywords\" content=\"{$keywords}\">\n";
	}
}

// 获取网站页面描述
function _the_description() {
	global $new_description;
	if( $new_description ){
		echo "<meta name=\"description\" content=\"$new_description\">\n";
		return;
	}

	global $s, $post;
	$description = '';
	$blog_name = get_bloginfo('name');
	if (is_singular()) {
		if (!empty($post->post_excerpt)) {
			$text = $post->post_excerpt;
		} else {
			$text = $post->post_content;
		}
		$description = trim(str_replace(array("\r\n", "\r", "\n", "　", " "), " ", str_replace("\"", "'", strip_tags($text))));
		$description = mb_substr($description, 0, 200, 'utf-8');

		if (!$description) {
			$description = $blog_name . "-" . trim(wp_title('', false));
		}

		$the = trim(get_post_meta($post->ID, 'description', true));
		if ($the) {
			$description = $the;
		}
		
	} elseif (is_home()) {
		$description = QGG_Options('home_description');
	} elseif (is_tag()) {
		$description = trim(strip_tags(tag_description()));
	} elseif (is_category()) {

		global $wp_query; 
		$cat_ID = get_query_var('cat');
		$description = _get_tax_meta($cat_ID, 'description');
		if( !$description ){
			$description = trim(strip_tags(category_description()));
		}

	} elseif (is_archive()) {
		$description = $blog_name . "'" . trim(wp_title('', false)) . "'";
	} elseif (is_search()) {
		$description = $blog_name . ": '" . esc_html($s, 1) . "' 的搜索結果";
	} else {
		$description = $blog_name . "'" . trim(wp_title('', false)) . "'";
	}
	
	echo "<meta name=\"description\" content=\"$description\">\n";
}
// 获取后台设置头部代码
function _the_head_code() {
	if (QGG_Options('site_head_code')) {
		echo "\n<!--HEADER_CODE_START-->\n" . QGG_Options('site_head_code') . "\n<!--HEADER_CODE_END-->\n";
	}
}
function _the_head_css() {
	$styles = '';
	// 整站变灰
	if (QGG_Options('site_style_gray')) {
		$styles .= "html{overflow-y:scroll;filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);-webkit-filter: grayscale(100%);}";
	}
	// 整站宽度
	if (QGG_Options('site_style_width') && QGG_Options('site_style_width')!=='1200') {
		$styles .= ".container{max-width:".QGG_Options('site_style_width')."px}";
	}
	// 整站风格
	$color = '';
	if (QGG_Options('site_style_skin') && QGG_Options('site_style_skin') !== '45B6F7') {
		$color = QGG_Options('site_style_skin');
		if ($color) {
			$styles .= '.site-skin-color{
							color: #'.$color.'!important;
						}
						.site-skin-bgc{
							background-color: #'.$color.'!important;
						}
						.site-skin-border{
							border-color: #'.$color.'!important;
						}';
		}
	}
	if ($styles) {
		echo '<style>' . $styles . '</style>';
	}
}
// 记录文章阅读量
function _post_views_record() {
	if (is_singular()) {
		global $post;
		$post_ID = $post->ID;
		if ($post_ID) {
			$post_views = (int) get_post_meta($post_ID, 'views', true);
			if (!update_post_meta($post_ID, 'views', ($post_views + 1))) {
				add_post_meta($post_ID, 'views', 1, true);
			}
		}
	}
}

// 整站底部代码加载
add_action('wp_footer', '_the_footer');
function _the_footer() {
	if (QGG_Options('site_foot_code')) {
		echo "<!--FOOTER_CODE_START-->\n" . QGG_Options('site_foot_code') . "\n<!--FOOTER_CODE_END-->\n";
	}
}

/**==================== 主题 Body 样式 ====================*/
function _bodyclass() {
	$class = '';
	
	if( QGG_Options('user_center_open')  && is_page_template('pages/page_user_center.php') ){
		$class .= ' user-center-open';
	}
	
	if( QGG_Options('nav_fixed') && !is_page_template('pages/page_user_reset_pwd.php') ){
		$class .= ' nav_fixed';
	}
	
	if( QGG_Options('topbar_off') ){
		$class .= ' topbar-off';
	}
	
	if ((is_single() || is_page()) && QGG_Options('post_indent_open')) {
		$class .= ' post-indent';
	}
	
	if ((is_single() || is_page()) && comments_open()) {
		$class .= ' comment-open';
	}
	if (is_super_admin()) {
		$class .= ' logged-admin';
	}
	
	return trim($class);
}

/**==================== 加载主题 JS 与 CSS 文件 ====================*/
add_action('wp_enqueue_scripts', 'the_scripts_loader');
function the_scripts_loader() {
	if (!is_admin()) {
		// delete jquery.js
		wp_deregister_script('jquery');
		// delete l10n.js
		wp_deregister_script('l10n');

		$purl = get_template_directory_uri();
		
		// 用户中心加载 user-center.css
		if (is_page_template('pages/page_user_center.php')) {
			the_css_loader(array('user-center' => 'user-center'));
		}
		// 分类页面加载 category.css
		if (is_category()) {
			the_css_loader(array('category' => 'category'));
		}
		// 普通页面加载 page.css
		if (is_page()) {
			the_css_loader(array(
				'page' => 'page',
				'video-js'   => $purl.'/css/libs/video-js.min.css',
			));
		}
		// 文章页面加载 single.css
		if (is_single()) {
			the_css_loader(array(
				'single' => 'single',
				'video-js'   => $purl.'/css/libs/video-js.min.css',
			));
		}
		// 公共 CSS 文件
		the_css_loader(array(
			'bootstrap' => $purl.'/css/libs/bootstrap.min.css', 
			'animate'   => $purl.'/css/libs/animate.min.css',
			'swiper'    => $purl.'/css/libs/swiper.min.css',
			'iconfont'  => $purl.'/css/libs/iconfont.css',
			'main'      => 'main',
			'widget'    => 'widget',
			'comment'   => 'comment',
		));
		// 公共 JS 文件,其他 JS 文件通过 require.js 按需加载
		the_js_loader(array(
			//'jquery'  => $purl.'/js/libs/jquery.min.js',
			'require' => $purl.'/js/libs/require.min.js',
			'main'    => $purl.'/js/main.js',
		));
	}
}
// 加载 CSS 文件函数
function the_css_loader($arr) {
	foreach ($arr as $key => $item) {
		$href = $item;
		if (strstr($href, '//') === false) {
			$href = get_template_directory_uri() . '/css/' . $item . '.css';
		}
		wp_enqueue_style('_' . $key, $href, array(), THEME_VERSION, 'all');
	}
}
// 加载 JS 文件函数
function the_js_loader($arr) {
	foreach ($arr as $key => $item) {
		$href = $item;
		if (strstr($href, '//') === false) {
			$href = get_template_directory_uri() . '/js/' . $item . '.js';
		}
		wp_enqueue_script('_' . $item, $href, array(), THEME_VERSION, true);
	}
}

/**==================== 整站评论 AJAX ====================*/
if(!function_exists('get_ajax_comment_callback') || !function_exists('get_ajax_comment_err')) {

	if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
		wp_die('请升级到4.4以上版本');
	}
	
	function get_ajax_comment_err($a) {
		header('HTTP/1.0 500 Internal Server Error');
		header('Content-Type: text/plain;charset=UTF-8');
		echo $a;
		exit;
	}

	function get_ajax_comment_callback(){
		$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
		if ( is_wp_error( $comment ) ) {
			$data = $comment->get_error_data();
			if ( ! empty( $data ) ) {
				get_ajax_comment_err($comment->get_error_message());
			} else {
				exit;
			}
		}
		$user = wp_get_current_user();
		do_action('set_comment_cookies', $comment, $user);
		$GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
		?>
		<li <?php comment_class(); ?>  id="comment-<?php echo get_comment_ID(); ?>">
		
			<div class="comt-box">
				<div class="comt-avatar">
					<?php echo _get_the_avatar($user_id=$comment->user_id, $user_email=$comment->comment_author_email, $src=true);?>
				</div>
				<div class="comt-main" id="div-comment-'<?php echo get_comment_ID(); ?>">
						<?php comment_text(); ?>
					<div class="comt-meta"><span class="comt-author"><?php echo get_comment_author_link();?></span>
						<time><?php echo _get_time_ago($comment->comment_date); ?></time>
					</div>
					<?php
					if ($comment->comment_approved == '0'){
					  echo '<span class="comt-approved">待审核</span>';
					}
					?>
				</div>
			</div>
		</li>
		<?php die();
	}
	add_action('wp_ajax_nopriv_comment', 'get_ajax_comment_callback');
	add_action('wp_ajax_comment', 'get_ajax_comment_callback');	
}

/**==================== 加载主题网站广告代码 ====================*/
function _the_ads($name='', $class=''){
	if( !QGG_Options($name.'_open') ){
		return;
	}else{
		$borderRadius = 'border-radius: '.QGG_options('site_style_border-radius').'px;';
		echo '<div class="ad-show '.$class.'" style="'.$borderRadius.'">'.QGG_Options($name).'</div>';
	}
}

/**==================== 加载主题模板文件 ====================*/
function the_module_loader($name = '', $apply = true) {
	if (!function_exists($name)) {
		include get_template_directory() . '/modules/' . $name . '.php';
	}

	if ($apply && function_exists($name)) {
		$name();
	}
}

/**==================== 加载主题 LOGO 文件 ====================*/
function the_site_logo() {
	$tag = is_home() ? 'h1' : 'div';
	$title = QGG_Options('home_title')?QGG_Options('home_title') : get_bloginfo('name') .(get_bloginfo('description')? '-'.get_bloginfo('description') : '');
	echo '<' . $tag . ' class="logo">
			<a href="' . get_bloginfo('url') . '" title="' . $title . '">
			<img src="'.QGG_Options('logo_colorful_src').'" alt="'.$title.'">
			</a>
		</' . $tag . '>';
}

/**==================== 主题导航已出 DIV 与 UL 标签 ====================*/
function the_site_menu($location = 'nav') {
	echo str_replace("</ul></div>", "", preg_replace("/<div[^>]*><ul[^>]*>/", "", wp_nav_menu(array('theme_location' => $location, 'echo' => false))));
}

/**==================== 文章链接打开方式 ====================*/
function the_post_target_blank(){
    return QGG_Options('target_blank') ? ' target="_blank"' : '';
}

/**==================== 主题日期显示方式 ====================*/
function _get_time_ago($post_time) {
	$post_time = strtotime($post_time);
	$interval  = time() - $post_time;
	
	if ( $interval < 1 ) { return '刚刚'; }
	$array = array(
		12 * 30 * 24 * 60 * 60 * 1 => '年前 (' . date('Y-m-d', $post_time) . ')',
		30 * 24 * 60 * 60 * 1      => '个月前 (' . date('m-d', $post_time) . ')',
		7  * 24 * 60 * 60 * 1      => '周前 (' . date('m-d', $post_time) . ')',
		24 * 60 * 60 * 1           => '天前',
		60 * 60 * 1                => '小时前',
		60 * 1                     => '分钟前',
		1                          => '秒前',
	);
	foreach ($array as $secs => $str) {
		$d = $interval / $secs;
		if ($d >= 1) {
			$r = round($d);
			return $r . $str;
		}
	};
}

/**==================== 主题获取用户头像 ====================*/
// 获取默认头像
function _get_default_avatar(){
	return get_template_directory_uri() . '/img/avatar-default.png';
}
// 过滤后台默认 Avatar 头像并添加主题默认头像
add_filter( 'avatar_defaults', '_the_new_avatar' );  
function _the_new_avatar ($avatar_defaults) {
	$new_avatar_url = _get_default_avatar();
	$avatar_defaults[$new_avatar_url] = "主题默认头像";  
	return $avatar_defaults;
}
// 判断头像加载方式获取 Avatar 头像
if( QGG_Options('gravatar_url') ){
	if( QGG_Options('gravatar_url') == 'ssl' ){
		add_filter('get_avatar', '_get_ssl_avatar');
	}elseif( QGG_Options('gravatar_url') == 'v2ex' ){
		add_filter('get_avatar', '_get_v2ex_avatar');
	}
}
function _get_ssl_avatar($avatar) {
	$avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&d=(.*).*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2&d='.get_option('avatar_default').'" class="avatar avatar-$2">',$avatar);
	return $avatar;
}
function _get_v2ex_avatar($avatar) {
	$avatar = str_replace(array("www.gravatar.com/avatar", "0.gravatar.com/avatar", "1.gravatar.com/avatar", "2.gravatar.com/avatar"), "cdn.v2ex.com/gravatar", $avatar);
	return $avatar;
}
// 获取用户自定义头像
function _get_user_avatar($user_id = '') {
	if (!$user_id) {
		return false;
	}
	$avatar = get_user_meta($user_id, 'avatar',1);
	if ($avatar) {
		return $avatar;
	} else {
		return false;
	}
}
// 获取用户头像(有自定义则显示自定义，无自定义则显示 Avatar)
function _get_the_avatar($user_id = '', $user_email = '', $src = false, $size = 50) {
	$user_avtar = _get_user_avatar($user_id);
	if ($user_avtar) {
		$attr = 'data-src';
		if ($src) {
			$attr = 'src';
		}

		return '<img class="avatar avatar-' . $size . ' photo" width="' . $size . '" height="' . $size . '" ' . $attr . '="' . $user_avtar . '">';
	} else {
		$avatar = get_avatar($user_email, $size, get_option('avatar_default'));
		if ($src) {
			return $avatar;
		} else {
			return str_replace(' src=', ' data-src=', $avatar);
		}
	}
}

/**==================== 分类目录添加更多功能 ====================*/
// 获取根分类目录 ID
function _get_cat_root_id($cat){
	$this_category = get_category($cat); 
	while($this_category->category_parent){
		$this_category = get_category($this_category->category_parent);
	}
	return $this_category->term_id;     // 返回跟分类的 ID
}
// 获取指定分类目录下的某属性
function _get_tax_meta($id=0, $field=''){
	$ops = get_option( "_taxonomy_meta_$id" );

	if( empty($ops) ){
		return '';
	}

	if( empty($field) ){
		return $ops;
	}

	return isset($ops[$field]) ? $ops[$field] : '';
}


class __Tax_Cat{

	function __construct(){
		// 新建分类页面添加自定义字段输入框 
		add_action( 'category_add_form_fields', array( $this, 'add_tax_field' ) );
		// 编辑分类页面添加自定义字段输入框 
		add_action( 'category_edit_form_fields', array( $this, 'edit_tax_field' ) );
		// 保存自定义字段数据
		add_action( 'edited_category', array( $this, 'save_tax_meta' ), 10, 2 );
		add_action( 'create_category', array( $this, 'save_tax_meta' ), 10, 2 );
	}
	
	//新建分类页面添加自定义字段输入框
	public function add_tax_field(){    
		echo '
			<div class="form-field">
				<label for="term_meta[style]">展示样式</label>
				<select name="term_meta[style]" id="term_meta[style]" class="postform">
					<option value="default">默认样式</option>
					<option value="video">视频展示</option>
					<option value="product">产品展示</option>
				</select>
				<p class="description">选择后前台展示样式将有所不同</p>
			</div>
			
			<div class="form-field">
				<label for="term_meta[title]">SEO 标题</label>
				<input type="text" name="term_meta[title]" id="term_meta[title]" />
			</div>
			
			<div class="form-field">
				<label for="term_meta[keywords]">SEO 关键字（keywords）</label>
				<input type="text" name="term_meta[keywords]" id="term_meta[keywords]" />
			</div>
			
			<div class="form-field">
				<label for="term_meta[keywords]">SEO 描述（description）</label>
				<textarea name="term_meta[description]" id="term_meta[description]" rows="4" cols="40"></textarea>
			</div>
		';
	}
	
	// 编辑分类页面添加自定义字段输入框
	public function edit_tax_field( $term ){    

		$term_id = $term->term_id;    // 获取当前分类 ID
		$term_meta = get_option( "_taxonomy_meta_$term_id" );    // 获取已保存的 Option
		
		$meta_style = isset($term_meta['style']) ? $term_meta['style'] : '';  // 自定义添加分类样式

		$meta_title = isset($term_meta['title']) ? $term_meta['title'] : '';    // 自定义添加分类标题
		$meta_keywords = isset($term_meta['keywords']) ? $term_meta['keywords'] : '';    // 自定义添加分类关键字
		$meta_description = isset($term_meta['description']) ? $term_meta['description'] : '';    // 自定义添加分类描述
		
		echo '
			<tr class="form-field">
				<th scope="row">
					<label for="term_meta[style]">展示样式</label>
					<td>
						<select name="term_meta[style]" id="term_meta[style]" class="postform">
							<option value="default" '. ('default'==$meta_style?'selected="selected"':'') .'>默认样式</option>
							<option value="video" '. ('video'==$meta_style?'selected="selected"':'') .'>视频展示</option>
							<option value="product" '. ('product'==$meta_style?'selected="selected"':'') .'>产品展示</option>
						</select>
						<p class="description">选择后前台展示样式将有所不同</p>
					</td>
				</th>
			</tr>
			
			<tr class="form-field">
				<th scope="row">
					<label for="term_meta[title]">SEO 标题</label>
					<td>
						<input type="text" name="term_meta[title]" id="term_meta[title]" value="'. $meta_title .'" />
					</td>
				</th>
			</tr>
			
			<tr class="form-field">
				<th scope="row">
					<label for="term_meta[keywords]">SEO 关键字（keywords）</label>
					<td>
						<input type="text" name="term_meta[keywords]" id="term_meta[keywords]" value="'. $meta_keywords .'" />
					</td>
				</th>
			</tr>
			
			<tr class="form-field">
				<th scope="row">
					<label for="term_meta[description]">SEO 描述（description）</label>
					<td>
						<textarea name="term_meta[description]" id="term_meta[description]" rows="4">'. $meta_description .'</textarea>
					</td>
				</th>
			</tr>
			
		';
	}
	
	public function save_tax_meta( $term_id ){    // 保存自定义字段的数据
 
		if ( isset( $_POST['term_meta'] ) ) {
			
			$term_meta = array();
			
			// 获取表单传过来的POST数据，POST数组一定要做过滤
			$term_meta['style'] = isset ( $_POST['term_meta']['style'] ) ? esc_sql( $_POST['term_meta']['style'] ) : '';
			$term_meta['title'] = isset ( $_POST['term_meta']['title'] ) ? esc_sql( $_POST['term_meta']['title'] ) : '';
			$term_meta['keywords'] = isset ( $_POST['term_meta']['keywords'] ) ? esc_sql( $_POST['term_meta']['keywords'] ) : '';
			$term_meta['description'] = isset ( $_POST['term_meta']['description'] ) ? esc_sql( $_POST['term_meta']['description'] ) : '';
			
			// 保存 Option 数组
			update_option( "_taxonomy_meta_$term_id", $term_meta );
 
		}
	}
 
}
 
$tax_cat = new __Tax_Cat();