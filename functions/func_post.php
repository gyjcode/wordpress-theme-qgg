<?php
/**
 * POST 自定义相关函数
 */

/**==================== 文章基本信息 ====================*/
// 文章副标题
function _get_the_post_subtitle($span=true){
    global $post;
    $post_ID = $post->ID;
    $subtitle = get_post_meta($post_ID, 'subtitle', true);

    if( !empty($subtitle) ){
        if( $span ){
            return ' <span>'.$subtitle.'</span>';
        }else{
            return ' '.$subtitle;
        }
    }else{
        return false;
    }
}
// 文章摘要 # 两个工具函数：tool_get_strlen 与 tool_str_cut
function _get_the_post_excerpt($limit = 120, $after = '...') {
    $excerpt = strip_tags( get_the_excerpt() );
    if (tool_get_strlen($excerpt ) > $limit) {
        return tool_str_cut($excerpt , 0, $limit, $after);
    } else {
        return $excerpt;
    }
}
// 文章缩略图
function _get_the_post_thumbnail($size = 'thumbnail', $class = 'thumb') {
    global $post;
    $r_src = '';
    
    if (has_post_thumbnail()) {
        $domsxe = get_the_post_thumbnail();
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $domsxe, $strResult, PREG_PATTERN_ORDER);  
        $images = $strResult[1];
        foreach($images as $src){
            $r_src = $src;
            break;
        }
    }elseif( QGG_Options('thumb_postfirstimg_on') ){
        $content = $post->post_content;  
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);  
        $images = $strResult[1];
        foreach($images as $src){
            $r_src = $src;
            break;
        }
    } 

    if( $r_src ){
        if( QGG_Options('thumbnail_async_on') ){
            return sprintf('<img data-src="%s" alt="%s" src="%s" class="thumbnail">', $r_src, $post->post_title .'-'. get_bloginfo('name'), get_template_directory_uri().'/assets/img/thumbnail.png');
        }else{
            return sprintf('<img src="%s" alt="%s" class="thumbnail">', $r_src, $post->post_title .'-'. get_bloginfo('name'));
        }
    }else{
        return sprintf('<img data-thumb="default" src="%s" class="thumbnail">', get_template_directory_uri().'/assets/img/thumbnail.png');
    }
}

// 文章阅读量
function _get_the_post_views($before = '', $after = '') {
    global $post;
    $post_ID = $post->ID;
    $views = (int) get_post_meta($post_ID, 'views', true);
    return $before . $views . $after;
}

// 文章喜欢数
function _get_the_post_likes($before = '', $after = ''){
    global $post;
    $post_ID = $post->ID;
    $likes = (int)get_post_meta( $post_ID, 'likes', true );
    return $before . $likes . $after;
}
// 我喜欢的文章
function _is_my_like($post_id=''){
    if( !is_user_logged_in() ) return false;
    $post_id = $post_id ? $post_id : get_the_ID();
    $likes = get_user_meta( get_current_user_id(), 'like-posts', true );
    $likes = $likes ? unserialize($likes) : array();
    return in_array($post_id, $likes) ? true : false;
}

// 获取文章产品信息
function _get_the_product_meta( $meta='', $before='', $after='' ){
    global $post;
    $post_ID = $post->ID;
    $product_meta = get_post_meta($post_ID, $meta, true);
    return $before.$product_meta.$after;
}

// 文章页添加展开收缩效果
if (!function_exists('custom_collapse')){
function custom_collapse($atts, $content = null){
    extract(shortcode_atts(array("title"=>""), $atts));
    return '
    <div class="collapse-box">
        <div class="collapse-title">
            <span>'.$title.'</span><a href="javascript:;" class="collapse-btn">展开/收缩</a>
            <div style="clear: both;"></div>
        </div>
        <div class="collapse-content" style="display: none;">'.$content.'</div>
    </div>';
}
add_shortcode('collapse', 'custom_collapse');
}
//添加展开/收缩快捷标签按钮
if (!function_exists('appthemes_add_collapse')){
function appthemes_add_collapse() {
    ?>
    <script type="text/javascript">
        if ( typeof QTags != 'undefined' ) {
            QTags.addButton( 'collapse', '展开/收缩按钮', '[collapse title="说明文字"]','[/collapse]\n' );
        }
    </script>
    <?php 
}
add_action('admin_print_footer_scripts', 'appthemes_add_collapse' );
}


// 文章通用 Meta
$common_conf = array(
    'box_id'    => "common_meta_box",
    'box_title' => __('通用设置', 'QGG'),
    'ipt_id'    => "common_meta_ipt_id",
    'ipt_name'  => "common_meta_ipt_name",
    'div_class' => "post-meta-box-s1",
);
$common_meta = array(
    array(
        'title' => __('副标题', 'QGG'),
        "name"  => "subtitle",
        "value" => "",
        "placeholder" => "副标题"
    ),
    array(
        "title" => __('来源名称', 'QGG'),
        "name"  => "from_name",
        "value" => "",
        "placeholder" => "来源名称"
    ),
    array(
        'title' => __('来源链接', 'QGG'),
        "type"  => "url",
        "name"  => "from_url",
        "value" => "",
        "placeholder" => "http(s)://"
    )
);

$common_meta_box = new _CreatePostMeta($common_conf, $common_meta);


// 文章新增产品相关 Meta
$product_conf = array(
    'box_id'    => "product_meta_box",
    'box_title' => __('产品设置', 'QGG'),
    'ipt_id'    => "product_meta_ipt_id",
    'ipt_name'  => "product_meta_ipt_name",
    'div_class' => "post-meta-box-s3",
);
$product_meta = array(
    array(
        "title" => __('原价', 'QGG'),
        "type"  => "number",
        "name"  => "product_price",
        "value" => "",
        "placeholder" => "0.00",
        "step"  => "0.01"
    ),
    array(
        "title" => __('库存(SKU)', 'QGG'),
        "type"  => "number",
        "name"  => "product_sku",
        "value" => "",
        "placeholder" => "0.00",
        "step"  => "0.01"
    ),
    array(
        "title" => __('跳转链接', 'QGG'),
        "type"  => "url",
        "name"  => "product_link",
        "value" => "",
        "placeholder" => "http(s)://"
    ),
    array(
        "title" => __('特价', 'QGG'),
        "type"  => "number",
        "name"  => "product_sale_price",
        "value" => "",
        "placeholder" => "0.00",
        "step"  => "0.01"
    ),
    array(
        "title" => __('起日期', 'QGG'),
        "type"  => "date",
        "name"  => "product_sale_price_date_from",
        "value" => "",
        "placeholder" => "YYYY-MM-DD",
        "pattern" => "[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"
    ),
    array(
        "title" => __('止日期', 'QGG'),
        "type"  => "date",
        "name"  => "product_sale_price_date_to",
        "value" => "",
        "placeholder" => "YYYY-MM-DD",
        "pattern" => "[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"
    )
);

$product_meta_box = new _CreatePostMeta($product_conf, $product_meta);

// 产品相册注册
include_once  THEME_DIR.'/functions/utils/func_product_gallery.php';

// 文章新增视频相关 Meta
$video_conf = array(
    'box_id'    => "video_meta_box",
    'box_title' => __('视频设置', 'QGG'),
    'ipt_id'    => "video_meta_ipt_id",
    'ipt_name'  => "video_meta_ipt_name",
    'div_class' => "post-meta-box-s2",
);
$video_meta = array(
    array(
        "title"  => __('导演', 'QGG'),
        "name"   => "video_director", 
        "value"  => "",
        "placeholder" => "导演"
    ),
    array(
        "title"  => __('编剧', 'QGG'),
        "name"   => "video_scriptwriter", 
        "value"  => "",
        "placeholder" => "编剧"
    ),
    array(
        "title"  => __('主演', 'QGG'),
        "name"   => "video_actor", 
        "value"  => "",
        "placeholder" => "主演"
    ),
    array(
        "title"  => __('语言', 'QGG'),
        "name"   => "video_language", 
        "value"  => "",
        "placeholder" => "语言"
    ),
    array(
        "title"  => __('发行公司', 'QGG'),
        "name"   => "video_publisher", 
        "value"  => "",
        "placeholder" => "发行公司"
    ),
    array(
        "title"  => __('上映时间', 'QGG'),
        "type"   => 'date',
        "name"   => "video_releasetime", 
        "value"  => "",
        "placeholder" => "YYYY-MM-DD"
    ),
);

$video_meta_box = new _CreatePostMeta($video_conf, $video_meta);
