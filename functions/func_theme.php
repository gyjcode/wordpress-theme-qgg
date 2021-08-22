<?php
/**
 * 主题通用函数
 */

/**==================== 文件加载器 ====================*/
// 模块加载器
function _module_loader($name = '', $apply = true) {
    if (!function_exists($name)) {
        include get_template_directory() . '/modules/' . $name . '.php';
    }

    if ($apply && function_exists($name)) {
        $name();
    }
}
// 广告加载器
function _ads_loader($name='', $class=''){
    if( !QGG_Options($name.'_on') ){
        return;
    }else{
        echo '<div class="adc module '.$class.'">'.QGG_Options($name).'</div>';
    }
}
// CSS 加载器
function _css_loader($arr) {
    foreach ($arr as $key => $item) {
        $href = $item;
        if (strstr($href, '//') === false) {
            $href = THEME_URI.'/assets/css/'.$item.'.css';
        }
        wp_enqueue_style('_'.$key, $href, array(), THEME_VER, 'all');
    }
}
// JS 加载器
function _js_loader($arr) {
    foreach ($arr as $key => $item) {
        $href = $item;
        if (strstr($href, '//') === false) {
            $href = THEME_URI.'/assets/js/'.$item.'.js';
        }
        wp_enqueue_script('_'.$item, $href, array(), THEME_VER, true);
    }
}

/**==================== 按需加载 JS 与 CSS 文件 ====================*/
add_action('wp_enqueue_scripts', '_enqueue_scripts_loader', '',THEME_VER);
function _enqueue_scripts_loader() {
    if (!is_admin()) {
        // delete jquery.js
        wp_deregister_script('jquery');
        // delete l10n.js
        wp_deregister_script('l10n');

        // 公共 CSS 文件
        _css_loader(array(
            'bootstrap' => 'libs/bootstrap.min', 
            'animate'   => 'libs/animate.min',
            'swiper'    => 'libs/swiper.min',
            'iconfont'  => 'libs/fontawsome.min',
            'main'      => 'main',
            'widget'    => 'widget',
            'comment'   => 'comment',
        ));
        // 公共 JS 文件
        _js_loader(array(
            'main'    => 'main',
        ));
        
        $hl_style = 'monokai-sublime';
        // 文章页面
        if (is_single()) {
            _css_loader(array(
                'single'    => 'single',
                'highlight' => 'highlight/'.$hl_style,
                'video-js'  => 'libs/video-js.min',
            ));
        }
        // 普通页面
        if (is_page()) {
            _css_loader(array(
                'page'     => 'page',
                'video-js' => 'libs/video-js.min',
            ));
        }
        // 用户中心
        if (is_page_template('pages/page_user_center.php')) {
            _css_loader(array('user-center' => 'user-center'));
        }
        // 分类页面
        if (is_category()) {
            _css_loader(array('category' => 'category'));
        }
    }
}

/**==================== 整站 Head 代码 ====================*/
add_action('wp_head', '_wp_head');
function _wp_head() {
    _site_keywords();
    _site_description();
    _post_views_record();
    _head_class();
    _head_code();
}
// SEO # 网站关键字
function _site_keywords() {
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

// SEO # 网站描述
function _site_description() {
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
// 整站皮肤样式
function _head_class() {
    
    $styles = '';

    // 整站变灰
    if (QGG_Options('site_style_gray')) {
        $styles .= "
        html{
            overflow-y: scroll;
            filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);
            -webkit-filter: grayscale(100%);
        }";
    }

    // 整站宽度
    $width = QGG_Options('site_style_width');
    if ( $width && $width !== '1200' ) {
        $styles .= '
        .container{
            max-width: '.$width.'px
        }';
    }

    // 整站皮肤
    $color = QGG_Options('site_style_skin');
    if ( $color && $color !== '24a0f0' ) {
        $styles .= '
        .site-style-color{
            color: #'.$color.' !important;
        }
        .site-style-background-color{
            background-color: #'.$color.' !important;
        }
        .site-style-border-color{
            border-color: #'.$color.' !important;
        }
        .site-style-focus-border-color:focus{
            border-color: #'.$color.' !important;
        }
        .site-style-hover-border-color:hover{
            border-color: #'.$color.' !important;
        }
        /* a标签单独设置 */
        .site-style-childA-color a{
            color: #'.$color.' !important;
        }
        .site-style-childA-hover-color a:hover{
            color: #'.$color.' !important;
        }';
    }

    // 整站圆角
    $borderRadius = QGG_Options('site_style_border-radius');
    if ( $borderRadius && $borderRadius !== 5 ) {
        $styles .= '
        .site-style-border-radius{
            border-radius: '.$borderRadius.'px;
        }';
    }

    // 输出样式
    echo $styles ? '<style>'. $styles .'</style>' : '';
}
// 自定义头部代码
function _head_code() {
    if (QGG_Options('site_head_code')) {
        echo "\n<!--HEADER_CODE_START-->\n" . QGG_Options('site_head_code') . "\n<!--HEADER_CODE_END-->\n";
    }
}

/**==================== 整站 Foot 代码 ====================*/
add_action('wp_footer', '_wp_footer');
function _wp_footer() {
    if (QGG_Options('site_foot_code')) {
        echo "<!--FOOTER_CODE_START-->\n" . QGG_Options('site_foot_code') . "\n<!--FOOTER_CODE_END-->\n";
    }
}

/**==================== 整站 Body 样式====================*/
function _body_class() {
    $class = '';
    
    if( QGG_Options('user_center_on')  && is_page_template('pages/page_user_center.php') ){
        $class .= ' user-center-on';
    }
    
    if( QGG_Options('nav_fixed') && !is_page_template('pages/page_user_reset_pwd.php') ){
        $class .= ' nav_fixed';
    }
    
    if( QGG_Options('topbar_off') ){
        $class .= ' topbar-off';
    }
    
    if ((is_single() || is_page()) && QGG_Options('post_indent_on')) {
        $class .= ' post-indent';
    }
    
    if ((is_single() || is_page()) && comments_open()) {
        $class .= ' comment-on';
    }
    if (is_super_admin()) {
        $class .= ' logged-admin';
    }
    
    return trim($class);
}

/**==================== 常用资源获取 ====================*/
// Logo
function _site_logo() {
    $tag = is_home() ? 'h1' : 'div';
    $title_default =  get_bloginfo('name').(get_bloginfo('description') ? '-'.get_bloginfo('description') : '');
    $title = QGG_Options('site_title') ?: $title_default;

    echo '
    <'.$tag.' class="logo">
        <a href=" '.get_bloginfo('url').' " title=" '.$title.' ">
            <img src=" '.QGG_Options('logo_colorful_src').' " alt=" '.$title.' ">
        </a>
    </'.$tag.'>';
}

/* 导航 */
function _site_menu($location = 'site_nav') {
    echo str_replace("</div>", "", preg_replace("/<div[^>]*>/", "", wp_nav_menu(array('theme_location' => $location, 'echo' => false))));
}

/* 用户头像*/
function _get_avatar($user_id = '', $user_email = '', $src = false, $size = 50) {
    $diy_avatar = _get_diy_avatar($user_id);
    if ($diy_avatar) {    // 有自定义头像则获取
        $attr = 'data-src';
        if ($src) {
            $attr = 'src';
        }

        return '<img class="avatar avatar-' . $size . ' photo" width="' . $size . '" height="' . $size . '" ' . $attr . '="' . $diy_avatar . '">';
    } else {    // 否则获取 Gravatar 头像
        $gravatar = get_avatar($user_email, $size, get_option('avatar_default'));    // 否则获取主题默认头像
        if ($src) {
            return $gravatar;
        } else {
            return str_replace(' src=', ' data-src=', $gravatar);
        }
    }
}
// 后台设置 # 默认头像修改
add_filter( 'avatar_defaults', '_avatar_defaults' );  
function _avatar_defaults ($avatar_defaults) {
    $new_avatar_url = _get_default_avatar();
    $avatar_defaults[$new_avatar_url] = "主题默认头像";  
    return $avatar_defaults;
}
// 默认头像获取
function _get_default_avatar(){
    return get_template_directory_uri() . '/assets/img/avatar-default.png';
}
//  Gravatar 头像获取
add_filter('get_avatar_url', '_get_avatar_url');
function _get_avatar_url($url) {
    $from = QGG_Options('gravatar_from_custom');
    if( !$from ){
        $from = QGG_Options('gravatar_from', 'https://gravatar.wp-china-yes.net/avatar/');
    }
    $url = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&d=(.*).*/', $from.'$1?s=$2&d='.get_option('avatar_default'), $url);
    return $url;
}

// 自定义头像获取 # 数据库
function _get_diy_avatar($user_id = '') {
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

/**==================== 更多功能函数 ====================*/
// 屏蔽默认表情
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
// 自定义 Emoji 表情
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
    return get_template_directory_uri().'/assets/img/emojis/'.$img;
}
add_filter('smilies_src', 'custom_emojis_src', 10, 2);


/**==================== 分类目录添加更多功能 ====================*/

// 文章链接打开方式
function _post_target_blank(){
    return QGG_Options('target_blank') ? ' target="_blank"' : '';
}

// 日期显示方式
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

// 获取用户中心页面
function _get_page_user_center_link(){
    
    $page_id = QGG_Options('user_center_page');

    if( !$page_id ){
        return false;
    }

    if( get_permalink($page_id) ){
        return get_permalink($page_id);
    }

    return false;
}
// 获取用户密码重置页面
function _get_page_user_reset_pwd_link(){
    
    $page_id = QGG_Options('user_reset_passward');
    
    if( !$page_id ){ 
        return false; 
    }elseif( get_permalink($page_id) ){ 
        return get_permalink($page_id); 
    }
    
    return false;
}
