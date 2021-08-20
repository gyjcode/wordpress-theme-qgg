<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function optionsframework_option_name() {
	// Change this to use your theme slug
	return 'QGG';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'theme-textdomain'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {

	// Test data
	$test_array = array(
		'one' => __( 'One', 'theme-textdomain' ),
		'two' => __( 'Two', 'theme-textdomain' ),
		'three' => __( 'Three', 'theme-textdomain' ),
		'four' => __( 'Four', 'theme-textdomain' ),
		'five' => __( 'Five', 'theme-textdomain' )
	);

	// Multicheck Array
	$multicheck_array = array(
		'one' => __( 'French Toast', 'theme-textdomain' ),
		'two' => __( 'Pancake', 'theme-textdomain' ),
		'three' => __( 'Omelette', 'theme-textdomain' ),
		'four' => __( 'Crepe', 'theme-textdomain' ),
		'five' => __( 'Waffle', 'theme-textdomain' )
	);

	// Multicheck Defaults
	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);

	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );

	// Typography Defaults
	$typography_defaults = array(
		'size' => '15px',
		'face' => 'georgia',
		'style' => 'bold',
		'color' => '#bada55' );

	// Typography Options
	$typography_options = array(
		'sizes' => array( '6','12','14','16','20' ),
		'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
		'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
		'color' => false
	);

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}

	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
	// $options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}
	
	// Pull all the linkcats into an array
	$options_linkcats = array();
	$options_linkcats_obj = get_terms('link_category');
	foreach ( $options_linkcats_obj as $tag ) {
		$options_linkcats[$tag->term_id] = $tag->name;
	}
	
	// Feature Article Lists
	$feature_post = array(
		'rand'     => __( '随机文章', 'QGG' ),
		'view'     => __( '最多阅读', 'QGG' ),
		'like'     => __( '最多喜欢', 'QGG' ),
		'comment'  => __( '最多评论', 'QGG' ),
		'modified' => __( '最新更新', 'QGG' )
	);
	
	$wechat_encoding_mode = array(
		'plaintext'  => __( '明文模式', 'QGG' ),
		'compatible' => __( '兼容模式', 'QGG' ),
		'encryption' => __( '安全模式', 'QGG' )
	);
	
	// If using image radio buttons, define a directory path
	$img_uri =  get_template_directory_uri() . '/assets/img/';
	$ads_desc   =  __('可添加任意广告联盟代码或自定义代码', 'QGG');
	$ads_01     =  __('<a href="https://zibuyu.life/" target="_blank">
						<img src="'.get_template_directory_uri().'/assets/img/ads-reset-01.png">
					</a>', 'QGG');
	$ads_02     =  __('<a href="https://zibuyu.life/" target="_blank">
						<img src="'.get_template_directory_uri().'/assets/img/ads-reset-02.png">
					</a>', 'QGG');

	/**==================== 正式配置代码开始 ====================*/

	$options = array();

	$options[] = array(
		'name'    => __( '基本配置', 'QGG' ),
		'type'    => 'heading'
	);
	
	$options[] = array(
		'name'     => __('整站样式调整', 'QGG'),
		'desc'     => __('开启，变灰——在特殊的日子里我们可能需要灰色表达情感', 'QGG'),
		'id'       => 'site_style_gray',
		'type'     => "checkbox",
		'std'      => false
	);
	
	$options[] = array(
		'desc'     => __('开启，导航固定——使网站导航始终固定悬浮在页面顶部', 'QGG'),
		'id'       => 'nav_fixed',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('宽度——设置一个值作为整站内容区宽度值', 'QGG'),
		'id'       => 'site_style_width',
		'type'     => 'text',
		'std'      => 1200
	);
	
	$options[] = array(
		'desc'     => __('圆角——设置一个值作为整站模块中圆角半径', 'QGG'),
		'id'       => 'site_style_border-radius',
		'type'     => 'text',
		'std'      => 5
	);
	
	$options[] = array(
		'desc'     => __("风格——14种颜色供选择，点击选择你喜欢的颜色，保存后前端展示会有所改变。", 'QGG'),
		'id'       => "site_style_skin",
		'std'      => "45B6F7",
		'type'     => "colorradio",
		'options'  => array(
			'45B6F7' => 1,
			'FF5E52' => 2,
			'2CDB87' => 3,
			'00D6AC' => 4,
			'16C0F8' => 5,
			'EA84FF' => 6,
			'FDAC5F' => 7,
			'FD77B2' => 8,
			'76BDFF' => 9,
			'C38CFF' => 10,
			'FF926F' => 11,
			'8AC78F' => 12,
			'C7C183' => 13,
			'555555' => 14
		)
	);
	
	$options[] = array(
		'name'     => __('头像获取方式选择', 'QGG'),
		'desc'     => __('Gravatar 头像服务在国外，国内加载可能会比较慢', 'QGG'),
		'id'       => 'gravatar_url',
		'std'      => "ssl",
		'type'     => "radio",
		'options'  => array(
			'no'   => __('原有方式', 'QGG'),
			'ssl'  => __('从Gravatar官方ssl获取', 'QGG'),
			'v2ex' => __('从v2ex官网获取', 'QGG'),
		)
	);
	
	$options[] = array(
		'name'     => __('超链接新窗口打开', 'QGG'),
		'desc'     => __('开启，网站中超链接点击后将在新窗口中打开', 'QGG'),
		'id'       => 'target_blank',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'name'     => __('文章内容首行缩进', 'QGG'),
		'desc'     => __('开启，段落缩进——前台文章段落首行缩进，后台编辑时无效', 'QGG'),
		'id'       => 'post_indent_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'name'     => __('最新文章列表', 'QGG'),
		'desc'     => __('模块标题——显示在模块上方的文字性标题内容', 'QGG'),
		'id'       => 'new_posts_excerpt_title',
		'std'      => __('最新发布', 'QGG'),
		'type'     => 'text'
	);
	
	$options[] = array(
		'desc'     => __('右侧链接-显示在模块上方右侧的超链接，可设置多个', 'QGG'),
		'id'       => 'new_posts_excerpt_title_more',
		'std'      => '<a href="https://blog.quietguoguo.com/">蝈蝈要安静 | 一个不学无术的伪程序员</a>',
		'type'     => 'textarea'
	);
	
	$options[] = array(
		'desc'     => __('显示方式——模块文章在前端的显示方式', 'QGG'),
		'id'       => 'list_type',
		'type'     => "radio",
		'std'      => "thumb",
		'options'  => array(
			'thumb'        => __('图文模式（建议缩略图尺寸：220*150px）', 'QGG'),
			'text'         => __('文字模式 ', 'QGG'),
			'thumb_if_has' => __('图文模式，无特色图时自动转换为文字模式 ', 'QGG'),
		)
	);
	
	$options[] = array(
		'desc'     => __('开启，显示分类——在左侧特色图片上显示其所属分类', 'QGG'),
		'id'       => 'post_plugin_cat_link',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'    => __('开启，推荐图标——后台文章勾选推荐后显示推荐图标', 'QGG'),
		'id'      => 'post_sticky_open',
		'type'    => "checkbox",
		'std'     => false
	);
	
	$options[] = array(
		'desc'     => __('开启，NEW图标——后台文章为最新发布时显示NEW图标', 'QGG'),
		'id'       => 'post_new_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('NEW 时间限制——默认为 72 小时，控制那些文章判定为最新文章', 'QGG'),
		'id'       => 'post_new_limit_time',
		'type'     => 'text',
		'std'      => 72
	);
	
	$options[] = array(
		'desc'     => __('开启，编辑时间——文章列表显示编辑时间', 'QGG'),
		'id'       => 'post_plugin_date',
		'type'     => "checkbox",
		'std'      => true,
		'class'    => 'op-multicheck'
	);
	
	$options[] = array(
		'desc'     => __('开启，作者姓名——文章列表显示作者姓名', 'QGG'),
		'id'       => 'post_plugin_author',
		'type'     => "checkbox",
		'std'      => true,
		'class'    => 'op-multicheck'
	);
	
	$options[] = array(
		'desc'     => __('开启，作者链接——作者姓名添加作者链接', 'QGG'),
		'id'       => 'author_link',
		'type'     => "checkbox",
		'std'      => true,
		'class'    => 'op-multicheck'
	);
		
	$options[] = array(
		'desc'     => __('开启，阅读数量——文章列表显示阅读数量', 'QGG'),
		'id'       => 'post_plugin_view',
		'type'     => "checkbox",
		'std'      => true,
		'class'    => 'op-multicheck'
	);
	
	$options[] = array(
		'desc'     => __('开启，评论数量——文章列表显示评论数量', 'QGG'),
		'id'       => 'post_plugin_comt',
		'type'     => "checkbox",
		'std'      => true,
		'class'    => 'op-multicheck'
	);
	
	$options[] = array(
		'desc'     => __('开启，点赞数量——文章列表显示点赞数量', 'QGG'),
		'id'       => 'post_plugin_like',
		'type'     => "checkbox",
		'std'      => true,
		'class'    => 'op-multicheck'
	);
	
	$options[] = array(
		'name'     => __('整站缩略图', 'QGG'),
		'desc'     => __('开启，首图作为缩略图——文章未设置特色图像时使用首图作为缩略图', 'QGG'),
		'id'       => 'thumb_postfirstimg_open',
		'type'     => "checkbox",
		'std'      => true
	);

	$options[] = array(
		'desc'     => __('开启，异步加载缩略图——使用 lazyload 实现突破懒加载，提升网页加载速度', 'QGG'),
		'id'       => 'thumbnail_async_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'name'     => __('整站评论系统', 'QGG'),
		'desc'     => __('开启，关闭整站评论——开启后将关闭整站评论', 'QGG'),
		'id'       => 'site_comment_closed_open',
		'type'     => "checkbox",
		'std'      => false
	);
	
	$options[] = array(
		'desc'     => __('评论标题——左上角的标题文字', 'QGG'),
		'id'       => 'comment_title',
		'type'     => 'text',
		'std'      =>'评论'
	);
	
	$options[] = array(
		'desc'     => __('提交按钮文字——评论框右下角提交按钮的文本', 'QGG'),
		'id'       => 'comment_submit_text',
		'type'     => 'text',
		'std'      => '提交评论',
		
	);
	
	$options[] = array(
		'desc'     => __('评论框提示字符——显示在评论输入框内部的提示性文字', 'QGG'),
		'id'       => 'comment_placeholder_text',
		'type'     => 'text',
		'std'      => '你的评论可以一针见血'
	);
	
	$options[] = array(
		'desc'     => __('评论框背景图——显示在评论输入框内部的背景图片', 'QGG'),
		'id'       => 'comment_background_img',
		'type'     => 'upload',
		'std'      => $img_uri.'comment-bgimg.png'
	);
	
	$options[] = array(
		'desc'     => __('开启，评论表情——开启后前端用户可使用 emoji 表情回复', 'QGG'),
		'id'       => 'site_comment_emoji_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('开启，获取 QQ 信息——开启后前端用户输入QQ号自动获取其他评论信息', 'QGG'),
		'id'       => 'site_comment_getqqinfo_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	
	
	
	
	
	$options[] = array(
		'name'     => __( '整站页眉', 'QGG' ),
		'type'     => 'heading'
	);
	
	$options[] = array(
		'name'     => __('顶部导航参数设置', 'QGG'),
		'desc'     => __('开启，关闭TopBar——关闭页面顶部TopBar部分', 'QGG'),
		'id'       => 'topbar_off',
		'type'     => "checkbox",
		'std'      => false
	);
	
	$options[] = array(
		'desc'     => __('开启，滚动公告——顶部显示滚动公告', 'QGG'),
		'id'       => 'announcement_on',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('滚动公告内容——每行一条，回车换行即可。不明白？<a href="http://blog.quietguoguo.com">点击这里</a> 进行留言。', 'QGG'),
		'id'       => 'announcement_list',
		'type'     => 'textarea',
		'std'      => '<a href="https://blog.quietguoguo.com">蝈蝈要安静 | 一个不学无术的伪程序员</a>'
	);
	
	$options[] = array(
		'desc'     => __('站点Logo——显示在导航左侧的Logo图标，建议尺寸：140*32px 格式：PNG', 'QGG'),
		'id'       => 'logo_colorful_src',
		'type'     => 'upload',
		'std'      => $img_uri.'logo-colorful.png'
	);
	
	$options[] = array(
		'desc'     => __('品牌文字——显示在Logo旁边的两个短文字，请换行填写两句文字（短文字介绍）', 'QGG'),
		'id'       => 'brand_text',
		'type'     => 'textarea',
		'std'      => "记录成长\n分享快乐",
		'settings' => array(
			'rows' => 2
		)
	);
	
	$options[] = array(
		'desc'     => __('彩色条带——导航底部的装饰性图片文件', 'QGG'),
		'id'       => 'color_bar',
		'type'     => 'upload',
		'std'      => $img_uri.'colorful-bar.gif'
	);
	
	$options[] = array(
		'name'     => __('首页 SEO 设置',' QGG'),
		'desc'     => __('站点标题——自定义有利于SEO的标题，为空则采用后台-设置-常规中的“站点标题+副标题”的形式',' QGG'),
		'id'       => 'site_title',
		'std'      => '蝈蝈要安静 | 一个不学无术的伪程序员',
		'type'     => 'textarea',
		'settings' => array(
			'rows' => 2
		)
	);
	
	$options[] = array(
		'desc'     => __('关键字——有利于SEO优化，建议个数在5-10之间，用英文逗号隔开', 'QGG'),
		'id'       => 'home_keywords',
		'std'      => '网站建设,WordPress,服务器,运维,程序员,Blog,博客,蝈蝈要安静,蝈蝈',
		'type'     => 'textarea',
		'settings' => array(
			'rows' => 2
		)
	);
	
	$options[] = array(
		'desc'     => __('站点描述——有利于SEO优化，建议字数在30-70之间', 'QGG'),
		'id'       => 'home_description',
		'std'      => '这个网站很棒棒哦~~~',
		'type'     => 'textarea',
		'settings' => array(
			'rows' => 3
		)
	);
	
	
	
	
	
	$options[] = array(
		'name'     => __( '整站页脚', 'QGG' ),
		'type'     => 'heading'
	);
	
	// 全站底部三栏推广区修改
	$options[] = array(
	    'name'     => __('全站底部推广模块', 'QGG'),
		'desc'     => __('开启，底部推广——显示在网站底部的三栏推广模块', 'QGG'),
	    'id'       => 'footer_brand_lmr_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	// 左侧区域自定义		
	$options[] = array(
		'desc'     => __('推广图标——建议尺寸180x42px，默认使用纯色Logo', 'QGG'),
		'id'       => 'footer_brand_lmr_logo',
		'type'     => 'upload',
		'std'      => $img_uri.'logo-pure.png'
	);
	
	$options[] = array(
		'desc'     => __('推广文本——建议100字内，显示在推广模块左侧的文本内容', 'QGG'),
		'id'       => 'footer_brand_lmr_text',
		'type'     => 'textarea',
		'std'      => '蝈蝈要安静博客主域名（quietguoguo.com）申请于2016年4曰1日愚人节，博客始建于2017年3月14日，博客主要分享网站建设中遇到的问题及解决方案、自己在读书过程中的心得体会、一些自己觉得有意义的音视频内容，记录些生活中的琐事，希望博客能督促怠惰的自己不断学习，不断进步。',
		'settings' => array(
			'rows' => 3
		)
	);
		
	// 中间区域自定义
	for ($i=1; $i <= 3; $i++){
	
	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>二维码ID</b>——二维码图片下方的ID名称', 'QGG'),
		'id'       => 'footer_brand_lmr_qr_id_'.$i,
		'type'     => 'text',
		'std'      => '蝈蝈要安静'
	);
		
	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>二维码描述</b>——二维码下方的简单描述，一般指示是什么的二维码', 'QGG'),
		'id'       => 'footer_brand_lmr_qr_desc_'.$i,
		'type'     => 'text',
		'std'      => '微信公众号'
	);
	
	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>二维码图片</b>——请上传您的二维码图片', 'QGG'),
		'id'       => 'footer_brand_lmr_qr_'.$i,
		'type'     => 'upload',
		'std'      => $img_uri.'qrcode.png'
	);
	
	}
	
	// 右侧区域自定义
	$options[] = array(
		'desc'     => __('超链接标题——右侧超链接上方的标题文字', 'QGG'),
		'id'       => 'footer_brand_lmr_caption',
		'std'      => '精彩直达',
		'type'     => 'text'
	);
	
	for ($j=1; $j <= 12; $j++) {
	$options[] = array(
		'desc'     => __('<b><i>'.$j.'</i>按钮文本</b>——超链接的名称', 'QGG'),
		'id'       => 'footer_brand_lmr_qr_title_'.$j,
		'type'     => 'text',
		'std'      => '蝈蝈要安静'
	);
		
	$options[] = array(
		'desc'     => __('<b><i>'.$j.'</i>按钮链接</b>——点击超链接将会调整的位置', 'QGG').$j,
		'id'       => 'footer_brand_lmr_href_'.$j,
		'type'     => 'text',
		'std'      => 'https://blog.quietguoguo.com/'
	);
	}
	
	$options[] = array(
		'name'     => __('整站页脚友情链接', 'QGG'),
		'desc'     => __('开启，页脚友情链接——在网站底部显示一个友情链接模块', 'QGG'),
		'id'       => 'friendly_links_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('友情链接分类——选择一个链接分类作为你网站页脚的友情链接', 'QGG'),
		'id'       => 'home_friendly_links',
		'type'     => 'select',
		'std'      => '',
		'options'  => $options_linkcats
	);
	
	$options[] = array(
		'name'     => __('整站底部自定义内容', 'QGG'),
		'desc'     => __('该部分代码将显示在网站底部友情链接下方，版权信息上方，可自定义图片文字等内容。一般可放些安全认证信息', 'QGG'),
		'id'       => 'site_footer_content',
		'type'     => 'textarea',
		'std'      => '',
		'settings' => array(
			'rows' => 5
		)
	);
	
	$options[] = array(
		'name'     => __('网站底部版权及其他', 'QGG'),
		'desc'     => __('开启，页面加载用时——页面底部显示页面加载所用时间', 'QGG'),
		'id'       => 'webpage_loading_time_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('开启，网站运行时间——基于网站运行初始时间计算的到的时间', 'QGG'),
		'id'       => 'website_running_time_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('初始建站时间——你网站初始建站时的时间，注意格式：2017-04-01 00:00:00', 'QGG'),
		'id'       => 'website_running_time_start',
		'std'      => "2017-04-01 00:00:00",
		'type'     => 'text'
	);
	
	$options[] = array(
		'desc'     => __('自定义代码——显示在网站版权旁边的其他超链接(a标签)信息', 'QGG'),
		'id'       => 'footer_custom_info',
		'type'     => 'textarea',
		'std'      => '<a href="'.site_url('/sitemap.xml').'">'.__('网站地图', 'QGG').'</a>本站由<a href="https://blog.quietguoguo.com/"><i>蝈蝈要安静</i></a>提供技术支持'."\n",
		'settings' => array(
			'rows' => 3
		)
	);
	
	
	
	
	
	
	
	
	$options[] = array(
		'name'     => __( '全屏轮播', 'QGG' ),
		'type'     => 'heading'
	);
	
	// 首页全屏轮播图	
	$options[] = array(
		'name'     => __('首页全屏轮播图设置', 'QGG'),
		'desc'     => __('开启，全屏轮播——开启后将在网站首页显示一个全屏轮播模块', 'QGG'),
		'id'       => 'full_screen_banner_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	for ($i=1; $i <= 5; $i++) { 
	$options[] = array(
		'desc'     => __('<b>开启，<i>'.$i.'</i>轮播模块</b>——开启第'.$i.'张轮播内容', 'QGG'),
		'id'       => 'full_screen_banner_open-'.$i,
		'type'     => 'checkbox',
		'std'      => true
	);
	$j = $i%2+1;
	$options[] = array(
		'desc'     => __('<b>开启，<i>'.$i.'</i>背景图片</b>——建议尺寸：1920*420px', 'QGG'),
		'id'       => 'full_screen_banner_bg-'.$i,
		'type'     => 'upload',
		'std'      => $img_uri.'banner-bg-'.$j.'.png'
	);
	
	$options[] = array(
		'desc'     => __('<b>开启，<i>'.$i.'</i>悬浮图片</b>——建议尺寸：', 'QGG').'240*340px',
		'id'       => 'full_screen_banner_float-'.$i,
		'type'     => 'upload',
		'std'      => $img_uri.'banner-float-'.$j.'.png'
	);
	
	$options[] = array(
		'desc'     => __('<b>开启，<i>'.$i.'</i>左文右图</b>——图片显示在右侧，文案显示在右侧', 'QGG'),
		'id'       => 'full_screen_banner_img_right-'.$i,
		'type'     => 'checkbox',
		'std'      => false
	);

	$options[] = array(
		'desc'     => __('<b>开启，<i>'.$i.'</i>文案标题</b>——显示在描述信息上方的标题','QGG'),
		'id'       => 'full_screen_banner_title-'.$i,
		'type'     => 'text',
		'std'      => '蝈蝈要安静 | 一个不学无术的伪程序员'
	);
	
	$options[] = array(
		'desc'     => __('<b>开启，<i>'.$i.'</i>文案描述</b>——显示在描述信息上方的具体描述信息','QGG'),
		'id'       => 'full_screen_banner_desc-'.$i,
		'type'     => 'textarea',
		'std'      => '分享网站建设中遇到的WordPress、Linux，Apache，Nginx，PHP，HTML，CSS等的问题及解决方案；分享Windows操作系统及其周边的一些经验知识；分享互联网使用过程中遇到的一些问题及其处理技巧；分享一些自己在读书过程中的心得体会；分享一些自己觉得有意义的音视频内容 ... ...',
		'settings' => array(
			'rows' => 4
		)
	); 
	
	// 按钮1
	$options[] = array(
		'desc'     => __('<b>开启，<i>'.$i.'</i>左按钮新窗口打开</b>', 'QGG'),
		'id'       => 'full_screen_banner_lbtn_blank-'.$i,
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>左按钮标题</b>：建议4字内', 'QGG'),
		'id'       => 'full_screen_banner_lbtn-'.$i,
		'type'     => 'text',
		'std'      => '快速直达'
	);
	
	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>左按钮链接</b>', 'QGG'),
		'id'       => 'full_screen_banner_lbtn_href-'.$i,
		'type'     => 'text',
		'std'      => 'https://blog.quietguoguo.com',
	);
	
	// 按钮2
	$options[] = array(
		'desc'     => __('<b>开启，<i>'.$i.'</i>右按钮新窗口打开</b>', 'QGG'),
		'id'       => 'full_screen_banner_rbtn_blank-'.$i,
		'type'     => 'checkbox',
		'std'      => true
	); 
	
	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>右按钮标题</b>：建议4字内', 'QGG'),
		'id'       => 'full_screen_banner_rbtn-'.$i,
		'type'     => 'text',
		'std'      => '了解详情'
	);
	
	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>右按钮链接</b>', 'QGG'),
		'id'       => 'full_screen_banner_rbtn_href-'.$i,
		'type'     => 'text',
		'std'      => 'https://blog.quietguoguo.com',
	);
	
	}
	
	
	
	
	$options[] = array(
		'name'     => __( '首页模块', 'QGG' ),
		'type'     => 'heading'
	);
	
	// 首页推荐盒子	
	$options[] = array(
		'name'     => __('首页专题盒子模块', 'QGG'),
		'desc'     => __('开启，盒子模块——开启后将在首页显示一个盒子模块', 'QGG'),
		'id'       => 'topic_card_box_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	for ($i=1; $i <= 4; $i++) { 
	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>背景图片</b>——建议尺寸：480pxx160px', 'QGG'),
		'id'       => 'topic_card_box_img-'.$i,
		'type'     => 'upload',
		'std'      => $img_uri.'topic.png'
	);

	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>图上文本</b>——悬浮在图片上方的文本，建议 5 字以内','QGG'),
		'id'       => 'topic_card_box_title-'.$i,
		'type'     => 'text',
		'std'      => '蝈蝈要安静'
	);
	
	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>盒子标题</b>——显示在图片下方的标题文本，建议 15 字以内','QGG'),
		'id'       => 'topic_card_box_desc01-'.$i,
		'type'     => 'text',
		'std'      => '一个不学无术的伪程序员'
	); 
	$options[] = array(
		'desc'     => __('<b><i>'.$i.'</i>盒子描述</b>——显示在图片下方的描述文本，建议 10 字以内','QGG'),
		'id'       => 'topic_card_box_desc02-'.$i,
		'type'     => 'text',
		'std'      => '记录成长 | 分享快乐'
	); 
	
	$options[] = array(
		'desc'     =>  __('<b><i>'.$i.'</i>跳转链接</b>——点击盒子将会跳转到何处','QGG'),
		'id'       => 'topic_card_box_link-'.$i,
		'type'     => 'text',
		'std'      => 'https://blog.quietguoguo.com'
	);
	}
	
	// 首页图片盒子	
	$options[] = array(
		'name'     => __('首页图片盒子模块', 'QGG'),
		'desc'     => __('开启，图片盒子——开启后将在首先显示一个图片盒子模块', 'QGG'),
		'id'       => 'img_box_posts_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	$options[] = array(
		'desc'     => __('开启，文章标题——鼠标悬浮后显示文章标题', 'QGG'),
		'id'       => 'img_box_posts_title',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	// 最新文章摘列表	
	$options[] = array(
		'name'     => __('最新文章列表模块', 'QGG'),
		'desc'     => __('开启，最新文章——在首页显示网站最新文章列表，具体设置请前往基本设置进行配置', 'QGG'),
		'id'       => 'new_posts_excerpt_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	// 双栏特性文章列表	
	$options[] = array(
		'name'     => __('双栏特性文章列表', 'QGG'),
		'desc'     => __('开启，双栏特性——在首页显示一个双栏特性(热门、热评、随机、点赞…)文章的列表', 'QGG'),
		'id'       => 'posts_list_double_s1_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('左侧标题——左侧文章列表的显示标题', 'QGG'),
		'id'       => 'feature_posts_list_left_name',
		'type'     => 'text',
		'std'      => '随机推荐'
	);
	
	$options[] = array(
		'desc'     => __('左侧特性——选择一种特性，作为查询的限制条件', 'QGG'),
		'id'       => 'feature_posts_list_left_id',
		'type'     => 'select',
		'std'      => 'rand',
		'options'  => $feature_post
	);
	
	$options[] = array(
		'desc'     => __('右侧标题——右侧文章列表的显示标题', 'QGG'),
		'id'       => 'feature_posts_list_right_name',
		'type'     => 'text',
		'std'      => '最多评论'
	);
	
	$options[] = array(
		'desc'     => __('右侧特性——选择一种特性，作为查询的限制条件', 'QGG'),
		'id'       => 'feature_posts_list_right_id',
		'type'     => 'select',
		'std'      => 'comment',
		'options'  => $feature_post
	);
		
	// 双栏分类文章列表	
	$options[] = array(
		'name'     => __('双栏分类文章列表', 'QGG'),
		'desc'     => __('开启，双栏分类——在网站首页显示一个双栏分类文章模块', 'QGG'),
		'id'       => 'posts_list_double_s2_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('左侧标题——左侧文章列表的显示标题', 'QGG'),
		'id'       => 'cat_posts_list_left_name',
		'type'     => 'text',
		'std'      => '分类推荐'
	);
	
	$options[] = array(
		'desc'     => __('左侧分类——选择一种分类，作为查询的限制条件', 'QGG'),
		'id'       => 'cat_posts_list_left_id',
		'type'     => 'select',
		'std'      => '',
		'options'  => $options_categories
		
	);
	
	$options[] = array(
		'desc'     => __('右侧标题——右侧文章列表的显示标题', 'QGG'),
		'id'       => 'cat_posts_list_right_name',
		'type'     => 'text',
		'std'      => '分类推荐'
	);
	
	$options[] = array(
		'desc'     => __('右侧分类——选择一种分类，作为查询的限制条件', 'QGG'),
		'id'       => 'cat_posts_list_right_id',
		'type'     => 'select',
		'std'      => '',
		'options'  => $options_categories
	);
	
	
	
	
	
	
	$options[] = array(
		'name'     => __('点赞分享', 'QGG'),
		'type'     => 'heading'
	);
	
	$options[] = array(
		'name'     => __('分享模块', 'QGG'),
		'desc'     => __('开启，分享——开启后将显示分享模块', 'QGG'),
		'id'       => 'post_share_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('分享文字——显示在分享模块左侧的文字', 'QGG'),
		'id'       => 'post_share_text',
		'type'     => 'text',
		'std'      => '分享到：'
	);
	
	$options[] = array(
		'name'     => __('点赞模块', 'QGG'),
		'desc'     => __('开启，点赞——开启后将显示点赞模块', 'QGG'),
		'id'       => 'post_like_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('点赞文字——显示在点赞模块上方的文字', 'QGG'),
		'id'       => 'post_like_text',
		'type'     => 'text',
		'std'      => '喜欢'
	);
	
	$options[] = array(
		'name'     => __('海报模块', 'QGG'),
		'desc'     => __('开启，海报——开启后将显示海报模块', 'QGG'),
		'id'       => 'post_poster_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('海报文字——显示在海报模块上方的文字', 'QGG'),
		'id'       => 'post_poster_text',
		'type'     => 'text',
		'std'      => '分享海报'
	);
	
	$options[] = array(
		'desc'     => __('顶部Logo——上传一张图片作为海报顶部的Logo图片', 'QGG'),
		'id'       => 'post_poster_logo',
		'type'     => 'upload',
		'std'      => $img_uri.'logo-pure.png'
	);
	
	$options[] = array(
		'desc'     => __('底部Icon——上传一张图片作为海报底部的ICON图片', 'QGG'),
		'id'       => 'post_poster_siteicon',
		'type'     => 'upload',
		'std'      => $img_uri.'favicon.ico'
	);
	
	$options[] = array(
		'desc'     => __('底部标语——显示在海报底部的宣传性标语', 'QGG'),
		'id'       => 'post_poster_slogan',
		'type'     => 'text',
		'std'      => '扫描左侧二维码，查阅文章详情'
	);
	
	$options[] = array(
		'name'     => __('打赏模块', 'QGG'),
		'desc'     => __('开启，打赏——开启后将显示打赏模块', 'QGG'),
		'id'       => 'post_rewards_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('打赏文字——显示在打赏模块上方的文字', 'QGG'),
		'id'       => 'post_rewards_text',
		'type'     => 'text',
		'std'      => '赏个钱儿'
	);
	
	$options[] = array(
		'desc'     => __('弹窗标题——显示在弹出窗体上方的标题文字', 'QGG'),
		'id'       => 'post_rewards_title',
		'type'     => 'text',
		'std'      => '觉得文章有用就打赏一下文章作者'
	);
	
	$options[] = array(
		'desc'     => __('支付宝二维码——显示在弹出窗体中的支付宝二维码', 'QGG'),
		'id'       => 'post_rewards_alipay',
		'type'     => 'upload',
		'std'      => $img_uri.'qrcode.png'
	);
	
	$options[] = array(
		'desc'     => __('微信二维码——显示在弹出窗体中的微信二维码', 'QGG'),
		'id'       => 'post_rewards_wechat',
		'type'     => 'upload',
		'std'      => $img_uri.'qrcode.png'
	);
	
	
	
	
	
	
	$options[] = array(
		'name'     => __( '文章部件', 'QGG' ),
		'type'     => 'heading'
	);
	
	$options[] = array(
		'name'     => __('文章页尾版权信息', 'QGG'),
		'desc'     => __('开启，版权信息——开启后将在文章末尾显示版权信息', 'QGG'),
		'id'       => 'post_copyright_open',
		'type'     => "checkbox",
		'std'      => true,
	);

	$options[] = array(
		'desc'     => __('版权标题——显示在文章页尾版权提示上方的标题文字', 'QGG'),
		'id'       => 'post_copyright_title',
		'type'     => 'text',
		'std'      => '未经允许不得转载'
	);
	
	$options[] = array(
		'name'     => __('文章页尾作者信息', 'QGG'),
		'desc'     => __('开启，作者信息——开启后将在文章底部显示作者信息', 'QGG'),
		'id'       => 'post_author_open',
		'type'     => "checkbox",
		'std'      => true,
	);
	
	$options[] = array(
		'name'     => __('文章页尾翻页导航', 'QGG'),
		'desc'     => __('开启，页尾导航——开启后将在文章底部显示上一篇下一篇文章', 'QGG'),
		'id'       => 'post_prevnext_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('开启，导航图片——开启后页尾导航将显示缩略图片', 'QGG'),
		'id'       => 'post_prevnext_img',
		'type'     => "checkbox",
		'std'      => true,
	);
	
	$options[] = array(
		'name'     => __('文章页尾相关文章', 'QGG'),
		'desc'     => __('开启，相关文章——开启后将在文章底部显示相关文章', 'QGG'),
		'id'       => 'post_related_open',
		'type'     => "checkbox",
		'std'      => true
	);

	$options[] = array(
		'desc'     => __('开启，图文模式——图文显示更美观', 'QGG'),
		'id'       => 'post_related_thumb',
		'type'     => "checkbox",
		'std'      => false,
	);

	$options[] = array(
		'desc'     => __('模块标题——显示在模块顶部的标题文本', 'QGG'),
		'id'       => 'post_related_title',
		'type'     => 'text',
		'std'      => '相关推荐'
	);

	$options[] = array(
		'desc'     => __('显示数量——控制相关文章的数量', 'QGG'),
		'id'       => 'post_related_num',
		'type'     => 'text',
		'std'      => 8,
		'class'    => 'mini'
	);
	
	
	
	
	
	
	
	
	$options[] = array(
		'name'     => __( '页面模板', 'QGG' ),
		'type'     => 'heading'
	);
	
	$options[] = array(
		'name'     => __('友情链接页面', 'QGG'),
		'desc'     => __('多选友链——选择一个或多个链接分类作为你网站的友情链接，此处无分类时表示没有将链接放在链接分类下', 'QGG'),
		'id'       => 'page_friendly_links',
		'type'     => 'multicheck',
		'std'      => '',
		'options'  => $options_linkcats
	);
	
	$options[] = array(
		'name'     => __('读者墙页面', 'QGG'),
		'desc'     => __('限制时间——限制在多少月内评论的才显示出来，单位：月', 'QGG'),
		'id'       => 'readwall_limit_time',
		'type'     => 'text',
		'std'      => 200,
		'class'    => 'mini'
	);

	$options[] = array(
		'desc'     => __('限制个数——限制读者墙页面最多显示的用户数量', 'QGG'),
		'id'       => 'readwall_limit_number',
		'type'     => 'text',
		'std'      => 200,
		'class'    => 'mini'
	);
	
	$options[] = array(
		'name'     => __('产品分类页面', 'QGG'),
		'desc'     => __('开启，文章数量——分类页面侧栏导航显示该分类下文章数量', 'QGG'),
		'id'       => 'cat_product_show_count',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('开启，二维码图片——分类页面侧栏导航显示当前分类二维码图片', 'QGG'),
		'id'       => 'cat_product_qrcode_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('二维码标题——分类页面侧栏导航显示二维码下方的标题文字', 'QGG'),
		'id'       => 'cat_product_qrcode_title',
		'type'     => 'text',
		'std'      => __('手机扫码查看', 'QGG')
	);
	
	
	
	
	
	
	$options[] = array(
		'name'     => __( '浮动客服', 'QGG' ),
		'type'     => 'heading'
	);
	$options[] = array(
		'name'     => __('整站客服系统', 'QGG'),
		'desc'     => __('开启，侧栏客服——在网站右侧显示一个浮动客服', 'QGG'),
		'id'       => 'rollbar_kefu_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('功能排序_PC——设置PC端各客服功能的显示顺序，用空格隔开。默认：6 5 4 3 2 1', 'QGG'),
		'id'       => 'kefu_sort',
		'type'     => 'text',
		'std'      => '6 5 4 3 2 1'
	);
	
	$options[] = array(
		'desc'     => __('开启，手机端——在手机端显示客服系统，手机底部浮动', 'QGG'),
		'id'       => 'rollbar_kefu_m_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('功能排序_M——独立设置移动端各客服功能的显示顺序，用空格隔开。默认：6 5 4 3 2 1', 'QGG'),
		'id'       => 'kefu_m_sort',
		'type'     => 'text',
		'std'      => '6 5 4 3 2 1'
	);
	
	$options[] = array(
		'desc'     => __('开启，会员中心_M——目前仅在手机端显示会员中心，方便用户登录', 'QGG'),
		'id'       => 'rollbar_user_center_m_open',
		'type'     => "checkbox",
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('<b>会员中心_M</b>——只在开启手机端会员中心时显示。默认：会员中心','QGG'),
		'id'       => 'kefu_user_center_m_tip',
		'type'     => 'text',
		'std'      => '会员中心'
	);
	
	// 去顶部
	$options[] = array(
		'desc'     => __('<b>回顶部_PC</b>——(排序ID:<i>1</i>)设置一个文本内容作为回顶部提示信息。默认：回顶部','QGG'),
		'id'       => 'kefu_top_tip',
		'type'     => 'text',
		'std'      => '回顶部'
	);
	
	$options[] = array(
		'desc'     => __('<b>回顶部_M</b>——(排序ID:<i>1</i>)设置一个文本内容作为回顶部提示信息。默认：回顶','QGG'),
		'id'       => 'kefu_top_m_tip',
		'type'     => 'text',
		'std'      => '回顶'
	);
	
	// 去评论
	$options[] = array(
		'desc'     => __('<b>去评论_PC</b>——(排序ID:<i>2</i>)设置一个文本内容作为去评论提示信息。默认：去评论','QGG'),
		'id'       => 'kefu_comment_tip',
		'type'     => 'text',
		'std'      => '去评论'
	);
	
	$options[] = array(
		'desc'     => __('<b>去评论_M</b>——(排序ID:<i>2</i>)设置一个文本内容作为去评论提示信息。默认：评论','QGG'),
		'id'       => 'kefu_comment_m_tip',
		'type'     => 'text',
		'std'      => '评论'
	);
	
	// 电话
	$options[] = array(
		'desc'     => __('<b>打电话_PC</b>——(排序ID:<i>3</i>)设置一个文本内容作为电话咨询提示信息。默认：电话咨询','QGG'),
		'id'       => 'kefu_tel_tip',
		'type'     => 'text',
		'std'      => '电话咨询'
	);
	
	$options[] = array(
		'desc'     => __('<b>打电话_M</b>——(排序ID:<i>3</i>)设置一个文本内容作为电话咨询提示信息。默认：电话','QGG'),
		'id'       => 'kefu_tel_m_tip',
		'type'     => 'text',
		'std'      => '电话'
	);
	
	$options[] = array(
		'desc'     => __('电话号码——电话咨询时跳转拨打的电话号码','QGG'),
		'id'       => 'kefu_tel_num',
		'type'     => 'text',
		'std'      => '123456789000'
	);
	
	// 企鹅
	$options[] = array(
		'desc'     => __('<b>聊 Q Q_PC</b>——(排序ID:<i>4</i>)设置一个文本内容作为 Q Q咨询提示信息。默认：QQ咨询','QGG'),
		'id'       => 'kefu_qq_tip',
		'std'      => 'QQ咨询',
		'type'     => 'text'
	);
	
	$options[] = array(
		'desc'     => __('<b>聊 Q Q_M</b>——(排序ID:<i>4</i>)设置一个文本内容作为 Q Q咨询提示信息。默认：QQ','QGG'),
		'id'       => 'kefu_qq_m_tip',
		'type'     => 'text',
		'std'      => 'Q Q'
	);
	
	$options[] = array(
		'desc'     => __('QQ号码——唤起QQ对话框是聊天的 Q Q','QGG'),
		'id'       => 'kefu_qq_num',
		'type'     => 'text',
		'std'      => '2220379479'
	);
	
	// 微信
	$options[] = array(
		'desc'     => __('<b>聊微信_PC</b>——(排序ID:<i>5</i>)设置一个文本内容作为微信咨询提示信息。默认：关注微信','QGG'),
		'id'       => 'kefu_wechat_tip',
		'type'     => 'text',
		'std'      => '关注微信'
	);
	
	$options[] = array(
		'desc'     => __('<b>聊微信_M</b>——(排序ID:<i>5</i>)设置一个文本内容作为微信咨询提示信息。默认：微信','QGG'),
		'id'       => 'kefu_wechat_m_tip',
		'type'     => 'text',
		'std'      => '微信'
	);
	
	$options[] = array(
		'desc'     => __('微信二维码——建议图片尺寸：200x200', 'QGG'),
		'id'       => 'kefu_wechat_qr',
		'type'     => 'upload',
		'std'      => $img_uri.'qrcode.png'
	);
	
	// 自定义
	$options[] = array(
		'desc'     => __('<b>自定义_PC</b>——(排序ID:<i>6</i>)自定义客服，用以自定义其他客服类型。默认：在线咨询','QGG'),
		'id'       => 'kefu_diy_tip',
		'std'      => '在线咨询',
		'type'     => 'text'
	);
	$options[] = array(
		'desc'     => __('<b>自定义_M</b>——(排序ID:<i>6</i>)自定义客服，用以自定义其他客服类型。默认：在线','QGG'),
		'id'       => 'kefu_diy_m_tip',
		'type'     => 'text',
		'std'      => '在线'
	);
	
	$options[] = array(
		'desc'     => __('客服链接——点击自定义客服时的链接，可以指定到一个客服页面'),
		'id'       => 'kefu_diy_link',
		'type'     => 'text',
		'std'      => 'https://blog.quietguoguo.com/'
	);
	
	
	
	
	
	
	$options[] = array(
		'name'     => __( '会员中心', 'QGG' ),
		'type'     => 'heading'
	);
	
	$options[] = array(
		'name'     => __( '会员中心', 'QGG' ),
		'desc'     => __( '开启，会员中心——开启会员中心后用户可以注册登录并管理自己的用户中心', 'QGG' ),
		'id'       => 'user_center_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('用户页面——选择一个页面作为会员中心页面，如果没有合适的页面，你需要去新建一个页面再来选择', 'QGG'),
		'id'       => 'user_center_page',
		'type'     => 'select',
		'std'      => '',
		'options'  => $options_pages
	);

	$options[] = array(
		'desc'     => __('密码找回——选择一个页面作为密码找回页面，如果没有合适的页面，你需要去新建一个页面再来选择', 'QGG'),
		'id'       => 'user_reset_passward',
		'type'     => 'select',
		'std'      => '',
		'options'  => $options_pages
	);
	
	$options[] = array(
		'desc'     => __('开启，发布文章——允许用户发布文章，发布文章后需后台审核', 'QGG'),
		'id'       => 'user_publish_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('开启，邮件通知——用户发布文章后通过邮件通知站长，需设置开启允许用户发布文章', 'QGG'),
		'id'       => 'user_publish_mail_send',
		'type'     => 'checkbox',
		'std'      => false
	);
	
	$options[] = array(
		'desc'     => __('通知邮箱——设置一个用于通知的邮箱地址，用户发布文章后将通知此邮箱', 'QGG'),
		'id'       => 'user_publish_mail_to',
		'type'     => 'text',
		'std'      => ''
	);
	
	$options[] = array(
		'desc'     => __('开启，集成 Erphpdown——开启此选项可将 Erphpdown 功能集成到主题中，需下载安装插件', 'QGG'),
		'id'       => 'user_erphpdown_open',
		'type'     => 'checkbox',
		'std'      => false
	);
	
	$options[] = array(
		'name'     => __( '邮件配置', 'QGG' ),
		'desc'     => __( '开启，邮件配置——启用SMTP邮件服务器发送邮件', 'QGG' ),
		'id'       => 'smtp_mail_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('SMTP服务器地址——配置 SMTP 的邮件发送服务器地址：smtp.qq.com', 'QGG'),
		'id'       => 'smtp_mail_host',
		'type'     => 'text',
		'std'      => '',
	);
	
	$options[] = array(
		'desc'     => __('SMTP发送协议——建议选择SSL模式更安全', 'QGG'),
		'id'       => 'smtp_mail_secure',
		'type'     => 'select',
		'std'      => '',
		'options'  => array(
			'ssl'   => __('SSL', 'QGG'),
			'tsl'   => __('TSL', 'QGG'),
			''   => __('无加密', 'QGG')
		)
	);
	
	$options[] = array(
		'desc'     => __('SMTP端口号——配置 SMTP 的邮件发送服务器的端口号，SSL：465或587，TSL：25', 'QGG'),
		'id'       => 'smtp_mail_port',
		'type'     => 'text',
		'std'      => 465,
	);
	
	$options[] = array(
		'desc'     => __('SMTP邮箱地址——配置 SMTP 的邮件发送地址：2220375475@qq.com', 'QGG'),
		'id'       => 'smtp_mail_user',
		'type'     => 'text',
		'std'      => '',
	);
	
	$options[] = array(
		'desc'     => __('SMTP邮箱密码——注意不是邮箱的登录密码', 'QGG'),
		'id'       => 'smtp_mail_pass',
		'type'     => 'text',
		'std'      => '',
	);
	
	$options[] = array(
		'desc'     => __('SMTP发件名称——选填，发送邮件上显示的名称，默认为 WordPress', 'QGG'),
		'id'       => 'smtp_mail_from_name',
		'type'     => 'text',
		'std'      => '蝈蝈要安静',
	);
	
	$options[] = array(
		'desc'     => __('SMTP收件地址——选填，用户回复内容时接收邮件的地址，默认为发件地址', 'QGG'),
		'id'       => 'smtp_mail_reply_to',
		'type'     => 'text',
		'std'      => '',
	);
	
	
	
	
	
	$options[] = array(
		'name'     => __( '功能增强', 'QGG' ),
		'type'     => 'heading'
	);
	
	$options[] = array(
		'name'     => __('网站右键菜单美化', 'QGG'),
		'desc'     => __('开启，网站右键菜单美化', 'QGG'),
		'id'       => 'right_click_menu_open',
		'type'     => 'checkbox',
		'std'      => false
	);
	
	$options[] = array(
		'name'     => __('手机端功能调试', 'QGG'),
		'desc'     => __('开启，手机端功能调试——加载 vConsole 用于手机端调试', 'QGG'),
		'id'       => 'enhance_vconsole_open',
		'type'     => 'checkbox',
		'std'      => false
	);
	
	$options[] = array(
		'name'     => __('禁止 F12 控制台', 'QGG'),
		'desc'     => __('开启，禁止F12控制台——JavaScript禁止鼠标点击', 'QGG'),
		'id'       => 'enhance_f12_forbidden_open',
		'type'     => 'checkbox',
		'std'      => false
	);
	
	
	
	
	
	
	$options[] = array(
		'name'     => __( '广告推广', 'QGG' ),
		'type'     => 'heading'
	);
	
	// 文章底部文字广告
	$options[] = array(
		'name'     => __('文章正文结尾文字广告', 'QGG'),
		'desc'     => __('开启，文末文字广告'),
		'id'       => 'ads_text_post_footer_open',
		'type'     => 'checkbox',
		'std'      => false
	);
	
	$options[] = array(
		'desc'     =>  __( '新窗口打开', 'QGG' ),
		'id'       => 'ads_text_post_footer_blank',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __( '推广标题', 'QGG' ),
		'id'       => 'ads_text_post_footer_title',
		'type'     => 'text',
		'std'      => '蝈蝈要安静'
	);
	
	$options[] = array(
		'desc'     => __( '推广描述', 'QGG' ),
		'id'       => 'ads_text_post_footer_desc',
		'type'     => 'text',
		'std'      => '一个不学无术的伪程序员'
	);
	
	$options[] = array(
		'desc'     =>  __( '推广链接', 'QGG' ),
		'id'       => 'ads_text_post_footer_link',
		'type'     => 'text',
		'std'      => 'https://blog.quietguoguo.com/'
	);
	
	// 整站广告代码
	$options[] = array(
		'name'     => __('最新文章列表广告', 'QGG'),
		'desc'     => __('开启，最新文章列表上广告', 'QGG'),
		'id'       => 'ads_post_list_01_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('最新文章列表上——广告代码：', 'QGG').$ads_desc,
		'id'       => 'ads_post_list_01',
		'type'     => 'textarea',
		'std'      => $ads_01,
		'settings' => array(
			'rows' => 5
		)
	);
	
	$options[] = array(
		'desc'     => __('开启，最新文章列表下广告', 'QGG'),
		'id'       => 'ads_post_list_02_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('最新文章列表下——广告代码：', 'QGG').$ads_desc,
		'id'       => 'ads_post_list_02',
		'type'     => 'textarea',
		'std'      => $ads_01,
		'settings' => array(
			'rows' => 5
		)
	);
	
	$options[] = array(
		'name'     => __('整站评论模块广告', 'QGG'),
		'desc'     => __('开启，整站评论模块上广告', 'QGG'),
		'id'       => 'ads_post_cmnt_01_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	$options[] = array(
		'desc'     => __('整站评论模块上——广告代码', 'QGG').' '.$ads_desc,
		'id'       => 'ads_post_cmnt_01',
		'type'     => 'textarea',
		'std'      => $ads_01,
		'settings' => array(
			'rows' => 5
		)
	);
	
	// 默认文章页广告代码
	$options[] = array(
		'name'     => __('默认文章页广告', 'QGG'),
		'desc'     => __('开启，默认文章正文上广告', 'QGG'),
		'id'       => 'ads_default_post_01_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('默认文章正文上——广告代码：', 'QGG').' '.$ads_desc,
		'id'       => 'ads_default_post_01',
		'type'     => 'textarea',
		'std'      => $ads_01,
		'settings' => array(
			'rows' => 5
		)
	);
	
	$options[] = array(
		'desc'     => __('开启，默认文章正文下广告', 'QGG'),
		'id'       => 'ads_default_post_02_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('默认文章正文下——广告代码：', 'QGG').' '.$ads_desc,
		'id'       => 'ads_default_post_02',
		'type'     => 'textarea',
		'std'      => $ads_01,
		'settings' => array(
			'rows' => 5
		)
	);
	
	// 视频文章页广告代码
	$options[] = array(
		'name'     => __('视频文章页广告', 'QGG'),
		'desc'     => __('开启，视频文章分享模块右广告', 'QGG'),
		'id'       => 'ads_video_post_01_open',
		'type'     => 'checkbox',
		'std'      => true
	);
	
	$options[] = array(
		'desc'     => __('视频文章分享模块右——广告代码：', 'QGG').' '.$ads_desc,
		'id'       => 'ads_video_post_01',
		'type'     => 'textarea',
		'std'      => $ads_02,
		'settings' => array(
			'rows' => 5
		)
	);
	
	
	
	
	
	
	
	$options[] = array(
		'name'     => __( '拓展代码', 'QGG' ),
		'type'     => 'heading'
	);
	
	$options[] = array(
		'name'     => __('整站头部自定义代码', 'QGG'),
		'desc'     => __('位于</head>标签之前，一般是些自定义<meta>、CSS、JS代码等', 'QGG'),
		'id'       => 'site_head_code',
		'type'     => 'textarea',
		'std'      => '',
		'settings' => array(
			'rows' => 5
		)
	);
	$options[] = array(
		'name'     => __('整站底部自定义代码', 'QGG'),
		'desc'     => __('位于</body>标签之前，一般是些自定义CSS、JS代码等，网站主体内容加载完成后执行', 'QGG'),
		'id'       => 'site_foot_code',
		'type'     => 'textarea',
		'std'      => '',
		'settings' => array(
			'rows' => 5
		)
	);
	
	$options[] = array(
		'name'     => __('自定义站内搜索', 'QGG'),
		'desc'     => __('开启，百度搜索——使用百度搜索检索网站文章', 'QGG'),
		'id'       => 'search_baidu_open',
		'type'     => "checkbox",
		'std'      => true,
	);
	
	$options[] = array(
		'desc'     => __('百度站内搜索代码，请自行前往<a href="http://zn.baidu.com/">百度</a>获取', 'QGG'),
		'id'       => 'search_baidu_code',
		'type'     => 'textarea',
		'std'      => '',
		'settings' => array(
			'rows' => 5
		)
	);
	
	$options[] = array(
		'name'     => __('网站统计代码', 'QGG'),
		'desc'     => __('位于网站底部，用于添加第三方溜了数据统计，比如：Google analytics 、百度统计、CNZZ等', 'QGG'),
		'id'       => 'site_track_code',
		'type'     => 'textarea',
		'std'      => '',
		'settings' => array(
			'rows' => 5
		)
	);
	
	
	
	
	
	$options[] = array(
		'name'     => __( '微信公众号', 'QGG' ),
		'type'     => 'heading'
	);
	
	$options[] = array(
		'name'     => __('微信公众号基础信息', 'QGG'),
		'desc'     => __('AppId——公众号开发信息中的开发者ID', 'QGG'),
		'id'       => 'wechat_official_appid',
		'type'     => 'text',
		'std'      => ''
	);
	
	$options[] = array(
		'desc'     => __('AppSecret——公众号开发信息中的开发者密码', 'QGG'),
		'id'       => 'wechat_official_appsecret',
		'type'     => 'text',
		'std'      => ''
	);
	
	$options[] = array(
		'desc'     => __('微信标识——公众号服务器配置中的地址(URL)(https://domain.com/?<i>wechat</i>), ?后面的<i>wechat</i>部分', 'QGG'),
		'id'       => 'wechat_official_requestsign',
		'type'     => 'text',
		'std'      => ''
	);
	
	$options[] = array(
		'desc'     => __('TOKEN——公众号服务器配置中的令牌', 'QGG'),
		'id'       => 'wechat_official_token',
		'type'     => 'text',
		'std'      => ''
	);
	
	$options[] = array(
		'desc'     => __('EncodingAesKey——公众号服务器配置中的消息加解密密钥', 'QGG'),
		'id'       => 'wechat_official_aeskey',
		'type'     => 'text',
		'std'      => ''
	);
	
	$options[] = array(
		'desc'     => __('EncodingMode——公众号服务器配置中的消息加解密方式', 'QGG'),
		'id'       => 'wechat_official_mode',
		'type'     => 'select',
		'std'      => 'encryption',
		'options'  => $wechat_encoding_mode
	);
	
	$options[] = array(
		'desc'     => __('微信二维码——正方形，建议图片尺寸：200x200px', 'QGG'),
		'id'       => 'wechat_official_qrcode',
		'type'     => 'upload',
		'std'      => $img_uri.'qrcode.png'
	);
	
	$options[] = array(
		'name'     => __('微信公众号回复消息', 'QGG'),
		'desc'     => __('默认回复——公众号无法处理请求时默认的回复消息', 'QGG'),
		'id'       => 'wechat_official_reply_default',
		'type'     => 'textarea',
		'std'      => '笨笨的机器人暂时无法理解您的请求，请输入【验证码】查看最新验证消息！',
		'settings' => array(
			'rows' => 3
		)
	);
	
	$options[] = array(
		'name'     => __('微信公众号获取验证码', 'QGG'),
		'desc'     => __('验证码有效时间——设置一个时间生成验证码，超时需重新生成', 'QGG'),
		'id'       => 'wechat_official_captcha_time',
		'type'     => 'text',
		'std'      => 10
	);
	
	
	/**
	 * For $settings options see:
	 * http://codex.wordpress.org/Function_Reference/wp_editor
	 *
	 * 'media_buttons' are not supported as there is no post to attach items to
	 * 'textarea_name' is set by the 'id' you choose
	 */
/* 
	$wp_editor_settings = array(
		'wpautop' => true, // Default
		'textarea_rows' => 5,
		'tinymce' => array( 'plugins' => 'wordpress,wplink' )
	);

	$options[] = array(
		'name' => __( 'Default Text Editor', 'theme-textdomain' ),
		'desc' => sprintf( __( 'You can also pass settings to the editor.  Read more about wp_editor in <a href="%1$s" target="_blank">the WordPress codex</a>', 'theme-textdomain' ), 'http://codex.wordpress.org/Function_Reference/wp_editor' ),
		'id' => 'example_editor',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);
 */
	return $options;
}