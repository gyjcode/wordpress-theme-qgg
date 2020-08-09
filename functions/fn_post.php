<?php
/**
 * POST 自定义相关函数
 */

// 文章页添加展开收缩效果
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
//添加展开/收缩快捷标签按钮
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

// 获取文章缩略图
function _get_post_thumbnail($size = 'thumbnail', $class = 'thumb') {
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
	}elseif( QGG_Options('thumb_postfirstimg_open') ){
		$content = $post->post_content;  
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);  
		$images = $strResult[1];
		foreach($images as $src){
			$r_src = $src;
			break;
		}
    } 

	if( $r_src ){
		if( QGG_Options('thumbnail_async_open') ){
    		return sprintf('<img data-src="%s" alt="%s" src="%s" class="thumb">', $r_src, $post->post_title .'-'. get_bloginfo('name'), get_template_directory_uri().'/img/thumbnail.png');
		}else{
    		return sprintf('<img src="%s" alt="%s" class="thumb">', $r_src, $post->post_title .'-'. get_bloginfo('name'));
		}
    }else{
    	return sprintf('<img data-thumb="default" src="%s" class="thumb">', get_template_directory_uri().'/img/thumbnail.png');
    }
}

// 获取文章阅读量
function _get_post_views($before = '', $after = '') {
	global $post;
	$post_ID = $post->ID;
	$views = (int) get_post_meta($post_ID, 'views', true);
	return $before . $views . $after;
}

// 获取文章点赞数
function _get_post_likes($before = '', $after = ''){
	global $post;
	$post_ID = $post->ID;
	$likes = (int)get_post_meta( $post_ID, 'likes', true );
	return $before . $likes . $after;
}

function _is_my_like($post_id=''){
	if( !is_user_logged_in() ) return false;
	$post_id = $post_id ? $post_id : get_the_ID();
	$likes = get_user_meta( get_current_user_id(), 'like-posts', true );
	$likes = $likes ? unserialize($likes) : array();
	return in_array($post_id, $likes) ? true : false;
}

// 获取文章副标题
function _get_the_subtitle($span=true){
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

// 获取产品价格前缀
function _get_price_pre(){
    return '&yen;';
}
// 获取文章产品信息
function _get_product_meta( $meta='', $before='', $after='' ){
	global $post;
	$post_ID = $post->ID;
	$product_meta = get_post_meta($post_ID, $meta, true);
	return $before.$product_meta.$after;
}



// 文章通用 Meta
$common_conf = array(
	'box_id'    => "common_meta_box",
	'box_title' => "通用设置",
	'ipt_id'    => "common_meta_ipt_id",
	'ipt_name'  => "common_meta_ipt_name",
	'div_class' => "post-meta-box-s1",
);
$common_meta = array(
	array(
		"name"  => "subtitle",
		"std"   => "",
		'title' => __('副标题', 'QGG')
	),
	array(
	    "name"  => "from_name",
	    "std"   => "",
	    "title" => __('来源名称', 'QGG')
	),
	array(
		"name"  => "from_url",
		"std"   => "",
		'title' => __('来源链接', 'QGG')
	)
);

$common_meta_box = new CreateMyMetaBox($common_conf,$common_meta);


// 文章新增产品相关 Meta
$product_conf = array(
	'box_id'    => "product_meta_box",
	'box_title' => "产品设置",
	'ipt_id'    => "product_meta_ipt_id",
	'ipt_name'  => "product_meta_ipt_name",
	'div_class' => "post-meta-box-s2",
);
$product_meta = array(
	array(
		"name"  => "original_price",
		"std"   => "", 
		"title" => __('产品原价', 'QGG')
	),
	array(
		"name"  => "bargain_price",
		"std"   => "",
		"title" => __('产品特价', 'QGG')
	),
	array(
		"name"  => "product_info",
		"std"   => "",
		"title" => __('产品信息', 'QGG')
	),
	array(
		"name"  => "product_link",
		"std"   => "",
		"title" => __('产品链接', 'QGG')
	)
);

$product_meta_box = new CreateMyMetaBox($product_conf,$product_meta);


// 文章新增视频相关 Meta
$video_conf = array(
		'box_id'    => "video_meta_box",
		'box_title' => "视频设置",
		'ipt_id'    => "video_meta_ipt_id",
		'ipt_name'  => "video_meta_ipt_name",
		'div_class' => "post-meta-box-s2",
	);
$video_meta = array(

	array(
		"name"   => "video_subname", 
		"std"    => "", 
		"title"  => '又名'
	),
	array(
		"name"   => "video_director", 
		"std"    => "", 
		"title"  => '导演'
	),
	array(
		"name"   => "video_screenwriter", 
		"std"    => "", 
		"title"  => '编剧'
	),
	array(
		"name"   => "video_author", 
		"std"    => "", 
		"title"  => '作者'
	),
	array(
		"name"   => "video_starring", 
		"std"    => "", 
		"title"  => '主演'
	),
	array(
		"name"   => "video_type", 
		"std"    => "", 
		"title"  => '类型'
	),
	array(
		"name"   => "video_publisher", 
		"std"    => "", 
		"title"  => '发行'
	),
	array(
		"name"   => "video_released", 
		"std"    => "", 
		"title"  => '上映'
	),
	array(
		"name"   => "video_language", 
		"std"    => "", 
		"title"  => '语言'
	),
	array(
		"name"   => "video_duration", 
		"std"    => "", 
		"title"  => '时长'
	),
	array(
		"name"   => "video_poster",
		"std"    => "",
		"title"  => '海报'
	),
	array(
		"name"   => "video_background", 
		"std"    => "",
		"title"  => '背景'
	),
);

$video_meta_box = new CreateMyMetaBox($video_conf,$video_meta);

// 文章新增视频列表 Meta
/**==================== 新增视频列表开始 ====================*/
function video_list_meta(){
	global $list_meta_box;
	$list_meta_box = array(
		'name' => 'video_list_info',
		"std"   => "",
		'title' => "",
		'type' => 'group',
		'submeta' => array(
			array(
				'title' => '序号',
				'name'   => 'sort',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'title' => '标题',
				'name'   => 'title',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'title' => '链接',
				'name'   => 'link',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'title' => '海报',
				'name'   => 'poster',
				'std'  => '',
				'type' => 'text'
			),
		),
	);
	
	add_action('admin_menu', 'my_list_meta_box_create');
	add_action('save_post', 'my_list_meta_box_save');
	
	function my_list_meta_box_create() {
		if ( function_exists('add_meta_box') ) {
			add_meta_box( 'video_lists_id', '视频列表', 'my_list_meta_box_init', 'post', 'normal', 'high' );
		}
	}
	// 初始化 Meta 信息
	function my_list_meta_box_init( $post_id ) {
		global $list_meta_box,$html_format;
		global $post;
		$post_id = $post -> ID;
		$total_num = get_post_meta($post_id, 'video_total_num', true) ? get_post_meta($post_id, 'video_total_num', true) : 1 ;
		$update_num = get_post_meta($post_id, 'video_update_num', true) ? get_post_meta($post_id, 'video_update_num', true) : 1;
		echo '
		<div class="meta-list-field">
			<div class="meta-list-sum">
				<label class="field-label" for="video-total-num-id" >总集数</label><input type="text" name="video_total_num" id="video-total-num-id" value="'.$total_num.'" class="field-input" />
				<label class="field-label" for="video-update-num-id" >更新至</label><input type="text" name="video_update_num" id="video-update-num-id" value="'.$update_num.'" class="field-input" />
			</div>
			<div class="meta-lists">';
				// 获取已有更新视频列表
				for ($i=1; $i<=$update_num; $i++){
					if ($i == 1){
						$html_format_old .= '
						<div class="meta-list-item first-item">
							<div class="meta-list-item-group">';
					}else{
						$html_format_old .= '
						<div class="meta-list-item">
							<div class="meta-list-item-group">';
					}
					if(is_array($list_meta_box['submeta'])){
						foreach ( $list_meta_box['submeta'] as $sub_meta ){
							$meta_box_key = $list_meta_box['name'].'_'.$i.'_'.$sub_meta['name'];
							$item_value = get_post_meta($post_id, $meta_box_key, true);
							
							$format = '<div class="meta-item header %s"><label for="%s">%s</label><input type="text" name="%s" id="%s" value="%s"/></div>';
							$mixed_arg0 = $sub_meta['name'];
							$mixed_arg1 = $meta_box_key;
							$mixed_arg2 = $sub_meta['title'];
							$mixed_arg3 = $item_value ? $item_value : $sub_meta['std'];
							$html_format_old .= sprintf( $format, $mixed_arg0, $mixed_arg1, $mixed_arg2, $mixed_arg1, $mixed_arg1, $mixed_arg3 );
						}
					}else{
						echo 'Error：submeta 不是一个数组集合！！！';
					}
					if ($i < $update_num){
						$html_format_old .= '
								<a href="#" style="visibility: hidden;" class="delete-item button-secondary '.$i.'" id = "'.$post_id.'";>删除</a>
							</div>
						</div>';
					}else{
						$html_format_old .= '
								<a href="#" class="delete-item button-secondary '.$i.'" id = "'.$post_id.'";>删除</a>
							</div>
						</div>';
					}
				} 
				// 生成新增视频列表组
				$html_format = '
				<div class="meta-list-item">
					<div class="meta-list-item-group">';
					if(is_array($list_meta_box['submeta'])){
						foreach ( $list_meta_box['submeta'] as $sub_meta ){
							$format = '<div class="meta-item %s"><label for="%s">%s</label><input type="text" name="%s" id="%s" value="%s"/></div>';
							$mixed_arg0 = $sub_meta['name'];
							$mixed_arg1 = $list_meta_box['name'].'_{{i}}_'.$sub_meta['name'];
							$mixed_arg2 = $sub_meta['title'];
							$mixed_arg3 = $sub_meta['std'];
							$html_format .= sprintf( $format, $mixed_arg0, $mixed_arg1, $mixed_arg2, $mixed_arg1, $mixed_arg1, $mixed_arg3 );
						}
					}else{
						echo 'Error：submeta 不是一个数组集合！！！';
					}
				$html_format .= '
						<a href="#" class="delete-item button-secondary '.$i.'" id = "'.$post_id.'";>删除</a>
					</div>
				</div>';
					
				echo '
				<script type="text/html" id="framework-html-'.$list_meta_box['name'].'">'.$html_format.'</script>
				'.$html_format_old.'
				<a href="#" class="add-item button-secondary" data-name="framework-html-'.$list_meta_box['name'].'">添加</a>
			</div>
		</div>';
		echo '<input type="hidden" name="list_meta_box_input" id="list-meta-box-input-id" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
	}
	// 遍历剧集信息生成 Meta
	function my_list_meta_box_save( $post_id ) {
		global $list_meta_box;
		global $post;
		$post_id = $post -> ID;
		
		if ( !wp_verify_nonce( isset($_POST['list_meta_box_input']) ? $_POST['list_meta_box_input'] : '', plugin_basename(__FILE__) ))
			return;
		if ( !current_user_can( 'edit_posts', $post_id ))
			return;	
		// 总集数
		creat_my_post_meta($post_id, 'video_total_num');
		// 更新至
		creat_my_post_meta($post_id, 'video_update_num');
		//分集
		$update_num_new = isset($_POST['video_update_num']) ? $_POST['video_update_num'] : '';
		for ($i=1; $i<=$update_num_new; $i++) {
			foreach( $list_meta_box['submeta'] as $sub_meta ) {
				$meta_box_key = $list_meta_box['name'].'_'.$i.'_'.$sub_meta['name'];
				creat_my_post_meta($post_id, $meta_box_key);
			}
		}
	}
	// 提交 Meta 到数据库
	function creat_my_post_meta($post_id, $meta_box_key){
		
		$new_data = isset($_POST[ $meta_box_key ]) ? $_POST[ $meta_box_key ] : '';
		
		if( get_post_meta( $post_id, $meta_box_key ) == "" ){
			add_post_meta( $post_id, $meta_box_key, $new_data, true );
		}elseif( $new_data != get_post_meta( $post_id, $meta_box_key, true ) ){
			update_post_meta( $post_id, $meta_box_key, $new_data );
		}elseif($new_data == ""){
			delete_post_meta( $post_id, $meta_box_key, get_post_meta( $post_id, $meta_box_key, true ) );
		}
	}

}
video_list_meta();
/**==================== 新增视频列表结束 ====================*/
