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
 * If you are making your theme translatable, you should replace 'site-stytle-textdomain'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {

    // Test data
    $test_array = array(
        'one' => __( 'One', 'site-stytle-textdomain' ),
        'two' => __( 'Two', 'site-stytle-textdomain' ),
        'three' => __( 'Three', 'site-stytle-textdomain' ),
        'four' => __( 'Four', 'site-stytle-textdomain' ),
        'five' => __( 'Five', 'site-stytle-textdomain' )
    );

    // Multicheck Array
    $multicheck_array = array(
        'one' => __( 'French Toast', 'site-stytle-textdomain' ),
        'two' => __( 'Pancake', 'site-stytle-textdomain' ),
        'three' => __( 'Omelette', 'site-stytle-textdomain' ),
        'four' => __( 'Crepe', 'site-stytle-textdomain' ),
        'five' => __( 'Waffle', 'site-stytle-textdomain' )
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
    
    // Animate 动画效果
    $animate_effect = array(
        'bounce'        => __( 'bounce', 'QGG' ),
        'pulse'         => __( 'pulse', 'QGG' ),
        'rubberBand'    => __( 'rubberBand', 'QGG' ),
        'shake'         => __( 'shake', 'QGG' ),
        'swing'         => __( 'swing', 'QGG' ),
        'tada'          => __( 'tada', 'QGG' ),
        'wobble'        => __( 'wobble', 'QGG' ),
        'bounceIn'      => __( 'bounceIn', 'QGG' ),
        'bounceInDown'  => __( 'bounceInDown', 'QGG' ),
        'bounceInUp'    => __( 'bounceInUp', 'QGG' ),
        'bounceInLeft'  => __( 'bounceInLeft', 'QGG' ),
        'bounceInRight' => __( 'bounceInRight', 'QGG' ),
        'fadeIn'        => __( 'fadeIn', 'QGG' ),
        'fadeInDown'    => __( 'fadeInDown', 'QGG' ),
        'fadeInUp'      => __( 'fadeInUp', 'QGG' ),
        'fadeInLeft'    => __( 'fadeInLeft', 'QGG' ),
        'fadeInRight'   => __( 'fadeInRight', 'QGG' ),
        'zoomIn'        => __( 'zoomIn', 'QGG' ),
        'zoomInDown'    => __( 'zoomInDown', 'QGG' ),
        'zoomInUp'      => __( 'zoomInUp', 'QGG' ),
        'zoomInLeft'    => __( 'zoomInLeft', 'QGG' ),
        'zoomInRight'   => __( 'zoomInRight', 'QGG' ),
    );

    // Feature Article Lists
    $feature_post = array(
        'rand'     => __( '随机文章', 'QGG' ),
        'view'     => __( '最多阅读', 'QGG' ),
        'like'     => __( '最多喜欢', 'QGG' ),
        'comment'  => __( '最多评论', 'QGG' ),
        'modified' => __( '最新更新', 'QGG' )
    );

    // 微信安全模式
    $wechat_encoding_mode = array(
        'plaintext'  => __( '明文模式', 'QGG' ),
        'compatible' => __( '兼容模式', 'QGG' ),
        'encryption' => __( '安全模式', 'QGG' )
    );
    
    // If using image radio buttons, define a directory path
    $img_uri   =  get_template_directory_uri() . '/assets/img/';
    $ads_desc  =  __('可添加任意广告联盟代码或自定义代码', 'QGG');
    $ads_01    =  __('<a href="https://zibuyu.life/" target="_blank"><img src="'.get_template_directory_uri().'/assets/img/ads-reset-01.png"></a>', 'QGG');
    $ads_02    =  __('<a href="https://zibuyu.life/" target="_blank"><img src="'.get_template_directory_uri().'/assets/img/ads-reset-02.png"></a>', 'QGG');

    /**==================== 正式配置代码开始 ====================*/

    $options = array();

    $options[] = array(
        'name'    => __( '基本配置', 'QGG' ),
        'type'    => 'heading'
    );

    $options[] = array(
        'name'     => __('初始化', 'QGG'),
        'desc'     => __('开启，禁止 WordPress 生成缩略图 # 占空间且后期更改配置后可能造成很多图片 404 ，建议禁止', 'QGG'),
        'id'       => 'disable_wp_thumbnail',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'name'     => __('整站样式', 'QGG'),
        'desc'     => __('开启，整站变灰', 'QGG'),
        'id'       => 'site_style_gray',
        'type'     => "checkbox",
        'std'      => false
    );
    
    $options[] = array(
        'desc'     => __('开启，导航固定：display: fixed;', 'QGG'),
        'id'       => 'nav_fixed_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>内容宽度</b>：.container 类的 max-width 属性', 'QGG'),
        'id'       => 'site_style_width',
        'type'     => 'text',
        'std'      => 1366
    );
    
    $options[] = array(
        'desc'     => __('<b>圆角半径</b>：border-radius 属性', 'QGG'),
        'id'       => 'site_style_border-radius',
        'type'     => 'text',
        'std'      => 5
    );
    
    $options[] = array(
        'desc'     => __("<b>皮肤颜色</b>：color、background-color、border-color 等属性", 'QGG'),
        'id'       => "site_style_skin",
        'type'     => "colorradio",
        'std'      => "45B6F7",
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
        'desc'     => __('<b>代码高亮</b> # 选择一种代码高亮样式', 'QGG'),
        'id'       => "code_highlight_style",
        'type'     => "select",
        'std'      => "monokai-sublime",
        'options'  => array(
            'monokai-sublime' => __('monokai-sublime', 'QGG'),
            'github-dark'     => __('github-dark', 'QGG'),
            'github'          => __('github', 'QGG'),
            'mono-blue'       => __('mono-blue', 'QGG'),
            'monokai'         => __('monokai', 'QGG'),
            'railscasts'      => __('railscasts', 'QGG'),
        )
    );
    
    $options[] = array(
        'name'     => __('Gravatar 头像服务', 'QGG'),
        'desc'     => __('<b>头像获取方式</b>', 'QGG'),
        'id'       => 'gravatar_from',
        'type'     => "select",
        'std'      => "https://gravatar.wp-china-yes.net/avatar/",
        'options'  => array(
            'https://www.gravatar.com/avatar/'          => 'https://www.gravatar.com/avatar/',
            'https://secure.gravatar.com/avatar/'       => 'https://secure.gravatar.com/avatar/',
            'https://gravatar.wp-china-yes.net/avatar/' => 'https://gravatar.wp-china-yes.net/avatar/',
            'https://sdn.geekzu.org/avatar/'            => 'https://sdn.geekzu.org/avatar/',
        )
    );
    $options[] = array(
        'desc'     => __('<b>自定义 Gravatar 头像地址</b>', 'QGG'),
        'id'       => 'gravatar_from_custom',
        'type'     => "text",
        'std'      => "",
    );
    
    $options[] = array(
        'name'     => __('链接新窗口打开', 'QGG'),
        'desc'     => __('开启，网站中超链接点击后将在新窗口中打开', 'QGG'),
        'id'       => 'target_blank',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'name'     => __('整站缩略图', 'QGG'),
        'desc'     => __('开启，首图作为缩略图 # 文章未设置特色图像时使用首图作为缩略图', 'QGG'),
        'id'       => 'thumbnail_postfirstimg_on',
        'type'     => "checkbox",
        'std'      => true
    );

    $options[] = array(
        'desc'     => __('开启，异步加载缩略图 # 使用 lazyload 实现懒加载，提升网页加载速度', 'QGG'),
        'id'       => 'thumbnail_async_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'name'     => __('面包屑导航', 'QGG'),
        'desc'     => __('开启，在内容页上显示一个面包屑导航', 'QGG'),
        'id'       => 'breadcrumbs_on',
        'type'     => "checkbox",
        'std'      => true
    );

    $options[] = array(
        'desc'     => __('开启，显示文章标题而不是【正文】字样', 'QGG'),
        'id'       => 'breadcrumbs_title_on',
        'type'     => "checkbox",
        'std'      => false
    );
    
    $options[] = array(
        'name'     => __('文章内容首行缩进', 'QGG'),
        'desc'     => __('开启，段落缩进——前台文章段落首行缩进，后台编辑时无效', 'QGG'),
        'id'       => 'post_indent_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'name'     => __('最新文章列表', 'QGG'),
        'desc'     => __('<b>模块标题</b> # 显示在模块上方的文字性标题内容', 'QGG'),
        'id'       => 'new_posts_excerpt_title',
        'std'      => __('最新发布', 'QGG'),
        'type'     => 'text'
    );
    
    $options[] = array(
        'desc'     => __('<b>右侧链接</b> # 标题右侧的超链接，可设置多个', 'QGG'),
        'id'       => 'new_posts_excerpt_title_more',
        'std'      => '<a href="https://zibuyu.life/">子不语 | 一个不学无术的伪程序员</a>',
        'type'     => 'textarea',
        'settings' => array(
            'rows' => 3
        )
    );
    
    $options[] = array(
        'desc'     => __('<b>显示方式</b> # 是否显示图片', 'QGG'),
        'id'       => 'new_posts_excerpt_list_type',
        'type'     => "radio",
        'std'      => "thumbnail",
        'options'  => array(
            'thumbnail'        => __('图文模式（建议缩略图尺寸：220*150px）', 'QGG'),
            'text'             => __('文字模式 ', 'QGG'),
            'thumbnail_if_has' => __('图文模式，无特色图时自动转换为文字模式 ', 'QGG'),
        )
    );
    
    $options[] = array(
        'name'    => __('整站文章小部件控制', 'QGG'),
        'desc'    => __('开启，显示文章分类', 'QGG'),
        'id'      => 'post_tag_category_on',
        'type'    => "checkbox",
        'std'     => true
    );
    
    $options[] = array(
        'desc'    => __('开启，显示推荐图标', 'QGG'),
        'id'      => 'post_tag_sticky_on',
        'type'    => "checkbox",
        'std'     => false
    );
    
    $options[] = array(
        'desc'     => __('开启，显示NEW图标', 'QGG'),
        'id'       => 'post_tag_new_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>NEW 时间限制</b> # 默认为 72 小时', 'QGG'),
        'id'       => 'post_new_limit_time',
        'type'     => 'text',
        'std'      => 72
    );
    
    $options[] = array(
        'desc'     => __('开启，显示编辑时间', 'QGG'),
        'id'       => 'post_meta_date_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('开启，显示作者姓名', 'QGG'),
        'id'       => 'post_meta_author_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('开启，添加作者链接', 'QGG'),
        'id'       => 'post_meta_author_link_on',
        'type'     => "checkbox",
        'std'      => true
    );
        
    $options[] = array(
        'desc'     => __('开启，显示阅读数量', 'QGG'),
        'id'       => 'post_meta_view_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('开启，显示喜欢数量', 'QGG'),
        'id'       => 'post_meta_like_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('开启，显示评论数量', 'QGG'),
        'id'       => 'post_meta_comment_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'name'     => __('整站评论系统', 'QGG'),
        'desc'     => __('关闭，关闭整站评论', 'QGG'),
        'id'       => 'comment_off',
        'type'     => "checkbox",
        'std'      => false
    );
    
    $options[] = array(
        'desc'     => __('<b>评论标题</b>', 'QGG'),
        'id'       => 'comment_title',
        'type'     => 'text',
        'std'      =>'评论'
    );
    
    $options[] = array(
        'desc'     => __('<b>提交按钮</b> # 替换文字', 'QGG'),
        'id'       => 'comment_submit_text',
        'type'     => 'text',
        'std'      => '提交评论',
        
    );
    
    $options[] = array(
        'desc'     => __('<b>提示字符</b> # 你的评论可以一针见血', 'QGG'),
        'id'       => 'comment_placeholder_text',
        'type'     => 'text',
        'std'      => '你的评论可以一针见血'
    );
    
    $options[] = array(
        'desc'     => __('<b>背景图</b> # 评论框内的底图', 'QGG'),
        'id'       => 'comment_background_img',
        'type'     => 'upload',
        'std'      => $img_uri.'comment-bgimg.png'
    );
    
    $options[] = array(
        'desc'     => __('开启，Emoji 表情', 'QGG'),
        'id'       => 'comment_emoji_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('开启，输入 QQ 自动获取信息', 'QGG'),
        'id'       => 'comment_getqqinfo_on',
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
        'desc'     => __('开启，滚动公告', 'QGG'),
        'id'       => 'announcement_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>滚动公告内容</b> # 每行一条，回车换行即可。不明白？<a href="http://zibuyu.life">点击这里</a> 进行留言。', 'QGG'),
        'id'       => 'announcement_list',
        'type'     => 'textarea',
        'std'      => '<a href="https://zibuyu.life">子不语 | 一个不学无术的伪程序员</a>',
        'settings' => array(
            'rows' => 3
        )
    );
    
    $options[] = array(
        'desc'     => __('<b>站点Logo</b> # 建议尺寸：140*32px 格式：PNG', 'QGG'),
        'id'       => 'site_logo_src',
        'type'     => 'upload',
        'std'      => $img_uri.'site-logo.png'
    );
    
    $options[] = array(
        'desc'     => __('<b>品牌文字</b> # 换行填写两句文字（建议 4 字内）', 'QGG'),
        'id'       => 'brand_text',
        'type'     => 'textarea',
        'std'      => "记录成长\n分享快乐",
        'settings' => array(
            'rows' => 2
        )
    );
    
    $options[] = array(
        'desc'     => __('<b>彩色条带</b> # 导航底部的装饰性图片', 'QGG'),
        'id'       => 'color_bar',
        'type'     => 'upload',
        'std'      => $img_uri.'colorful-bar.gif'
    );
    
    $options[] = array(
        'name'     => __('首页 SEO 设置',' QGG'),
        'desc'     => __('<b>站点标题</b> # 为空则采用后台【设置/常规】中的“站点标题 + 副标题”的形式',' QGG'),
        'id'       => 'site_title',
        'std'      => '子不语 | 一个不学无术的伪程序员',
        'type'     => 'textarea',
        'settings' => array(
            'rows' => 2
        )
    );
    
    $options[] = array(
        'desc'     => __('<b>关键字</b> # 建议个数在5-10之间，用英文逗号隔开', 'QGG'),
        'id'       => 'site_keywords',
        'std'      => '网站建设,WordPress,服务器,运维,程序员,Blog,博客,子不语,蝈蝈',
        'type'     => 'textarea',
        'settings' => array(
            'rows' => 2
        )
    );
    
    $options[] = array(
        'desc'     => __('<b>描述</b> # 建议字数在30-70之间', 'QGG'),
        'id'       => 'site_description',
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
    
    $options[] = array(
        'name'     => __('友情链接', 'QGG'),
        'desc'     => __('开启，页脚友情链接', 'QGG'),
        'id'       => 'friendly_links_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>链接分类</b> # 选择一个链接分类', 'QGG'),
        'id'       => 'friendly_links_cat',
        'type'     => 'select',
        'std'      => '',
        'options'  => $options_linkcats
    );
    
    $options[] = array(
        'name'     => __('自定义内容', 'QGG'),
        'desc'     => __('<b>自定义内容</b> # 友情链接下方，版权信息上方', 'QGG'),
        'id'       => 'footer_custom_content',
        'type'     => 'textarea',
        'std'      => '',
        'settings' => array(
            'rows' => 5
        )
    );
    
    $options[] = array(
        'name'     => __('版权所有', 'QGG'),
        'desc'     => __('<b>ICP 备案号</b> >>> <a target="_blank" href="https://beian.miit.gov.cn/">官网地址</a>', 'QGG'),
        'id'       => 'site_beian_icp',
        'std'      => "",
        'type'     => 'text'
    );

    $options[] = array(
        'desc'     => __('<b>公网安备号</b> >>> <a target="_blank" href="http://www.beian.gov.cn/">官网地址</a>', 'QGG'),
        'id'       => 'site_beian_gov',
        'std'      => "",
        'type'     => 'text'
    );
    
    $options[] = array(
        'desc'     => __('<b>站点地图</b> # 选择【站点地图】页面模板', 'QGG'),
        'id'       => 'sitemap_html_page',
        'type'     => 'select',
        'std'      => '',
        'options'  => $options_pages
    );

    $options[] = array(
        'desc'     => __('<b>支持作者</b> # 显示技术支持信息', 'QGG'),
        'id'       => 'site_tech_support',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>自定义链接</b> # 版权旁的其他超链接内容', 'QGG'),
        'id'       => 'footer_custom_link',
        'type'     => 'textarea',
        'std'      => '',
        'settings' => array(
            'rows' => 5
        )
    );

    $options[] = array(
        'name'     => __('运行信息', 'QGG'),
        'desc'     => __('开启，页面加载用时', 'QGG'),
        'id'       => 'page_loading_time_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>建站时间</b> # 注意格式：2017-04-01 00:00:00', 'QGG'),
        'id'       => 'site_building_time',
        'std'      => "2017-04-01 00:00:00",
        'type'     => 'text'
    );
    
    
    
    
    
    $options[] = array(
        'name'     => __( '全屏轮播', 'QGG' ),
        'type'     => 'heading'
    );
    
    // 首页全屏轮播图    
    $options[] = array(
        'name'     => __('全屏轮播图', 'QGG'),
        'desc'     => __('开启，显示一个全屏走马灯', 'QGG'),
        'id'       => 'carousel_full_screen_on',
        'type'     => 'checkbox',
        'std'      => true
    );

    $options[] = array(
        'desc'     => __('开启，手机端不显示', 'QGG'),
        'id'       => 'carousel_full_screen_m_off',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    for ($i=1; $i <= 3; $i++) { 
        $options[] = array(
            'desc'     => __('开启，第<i>'.$i.'</i>张轮播内容', 'QGG'),
            'id'       => 'carousel_full_screen_on-'.$i,
            'type'     => 'checkbox',
            'std'      => true
        );
        // 悬浮图
        $options[] = array(
            'desc'     => __('<b>背景图片</b> # 建议尺寸：1920*420px', 'QGG'),
            'id'       => 'carousel_full_screen_bgimg-'.$i,
            'type'     => 'upload',
            'std'      => $img_uri.'carousel-bgImg.png'
        );
        
        $options[] = array(
            'desc'     => __('开启，右图左文', 'QGG'),
            'id'       => 'carousel_full_screen_img_right-'.$i,
            'type'     => 'checkbox',
            'std'      => false
        );

        $options[] = array(
            'desc'     => __('<b>悬浮图片</b> # 建议尺寸：240*340px', 'QGG'),
            'id'       => 'carousel_full_screen_img-'.$i,
            'type'     => 'upload',
            'std'      => $img_uri.'carousel-img.png'
        );
        
        $options[] = array(
            'desc'     => __('<b>悬浮效果</b>：swiper-animate-effect，详见：<a href="https://www.swiper.com.cn/usage/animate/index.html">Swiper Animate</a>','QGG'),
            'id'       => 'carousel_full_screen_img_animate_effect-'.$i,
            'type'     => 'select',
            'std'      => 'bounceInLeft',
            'options'  => $animate_effect

        );

        $options[] = array(
            'desc'     => __('<b>悬浮图片持续时间</b>：swiper-animate-duration，单位：s。详见：<a href="https://www.swiper.com.cn/usage/animate/index.html">Swiper Animate</a>','QGG'),
            'id'       => 'carousel_full_screen_img_animate_duration-'.$i,
            'type'     => 'text',
            'std'      => '0.5'
        );

        $options[] = array(
            'desc'     => __('<b>悬浮图片延迟时间</b>：swiper-animate-delay，单位：s。详见：<a href="https://www.swiper.com.cn/usage/animate/index.html">Swiper Animate</a>','QGG'),
            'id'       => 'carousel_full_screen_img_animate_delay-'.$i,
            'type'     => 'text',
            'std'      => '0.3'
        );
        // 文案
        $options[] = array(
            'desc'     => __('<b>文案标题</b>','QGG'),
            'id'       => 'carousel_full_screen_title-'.$i,
            'type'     => 'text',
            'std'      => '子不语 | 一个不学无术的伪程序员'
        );
        
        $options[] = array(
            'desc'     => __('<b>描述信息</b> # 建议 200 字以内','QGG'),
            'id'       => 'carousel_full_screen_desc-'.$i,
            'type'     => 'textarea',
            'std'      => '分享网站建设中遇到的WordPress、Linux，Apache，Nginx，PHP，HTML，CSS等的问题及解决方案；分享Windows操作系统及其周边的一些经验知识；分享互联网使用过程中遇到的一些问题及其处理技巧；分享一些自己在读书过程中的心得体会；分享一些自己觉得有意义的音视频内容 ... ...',
            'settings' => array(
                'rows' => 3
            )
        ); 
        // 按钮1
        $options[] = array(
            'desc'     => __('<b>左按钮标题</b> # 建议4字内', 'QGG'),
            'id'       => 'carousel_full_screen_lbtn-'.$i,
            'type'     => 'text',
            'std'      => '快速直达'
        );
        
        $options[] = array(
            'desc'     => __('<b>左按钮链接</b>', 'QGG'),
            'id'       => 'carousel_full_screen_lbtn_href-'.$i,
            'type'     => 'text',
            'std'      => 'https://zibuyu.life'
        );
        // 按钮2
        $options[] = array(
            'desc'     => __('<b>右按钮标题</b> # 建议4字内', 'QGG'),
            'id'       => 'carousel_full_screen_rbtn-'.$i,
            'type'     => 'text',
            'std'      => '了解详情'
        );
        
        $options[] = array(
            'desc'     => __('<b>右按钮链接</b>', 'QGG'),
            'id'       => 'carousel_full_screen_rbtn_href-'.$i,
            'type'     => 'text',
            'std'      => 'https://zibuyu.life'
        );
        
        $options[] = array(
            'desc'     => __('<b>文案转场效果</b>：swiper-animate-effect，详见：<a href="https://www.swiper.com.cn/usage/animate/index.html">Swiper Animate</a>','QGG'),
            'id'       => 'carousel_full_screen_txt_animate_effect-'.$i,
            'type'     => 'select',
            'std'      => 'bounceInRight',
            'options'  => $animate_effect

        );

        $options[] = array(
            'desc'     => __('<b>文案转场持续时间</b>：swiper-animate-duration，单位：s。详见：<a href="https://www.swiper.com.cn/usage/animate/index.html">Swiper Animate</a>','QGG'),
            'id'       => 'carousel_full_screen_txt_animate_duration-'.$i,
            'type'     => 'text',
            'std'      => '0.5'
        );

        $options[] = array(
            'desc'     => __('<b>文案转场延迟时间</b>：swiper-animate-delay，单位：s。详见：<a href="https://www.swiper.com.cn/usage/animate/index.html">Swiper Animate</a>','QGG'),
            'id'       => 'carousel_full_screen_txt_animate_delay-'.$i,
            'type'     => 'text',
            'std'      => '0.3'
        );
    }




    
    $options[] = array(
        'name'     => __( '三栏推广', 'QGG' ),
        'type'     => 'heading'
    );
    
    // 全站底部三栏推广区修改
    $options[] = array(
        'name'     => __('全站底部推广模块', 'QGG'),
        'desc'     => __('开启，底部三栏推广模块', 'QGG'),
        'id'       => 'footer_brand_lmr_on',
        'type'     => 'checkbox',
        'std'      => true
    );

    // 左侧区域自定义        
    $options[] = array(
        'desc'     => __('<b>推广图标</b> # 建议尺寸180x42px', 'QGG'),
        'id'       => 'footer_brand_lmr_logo',
        'type'     => 'upload',
        'std'      => $img_uri.'site-logo-pure.png'
    );
    
    $options[] = array(
        'desc'     => __('<b>推广文本</b> # 建议100字内，显示在模块左侧', 'QGG'),
        'id'       => 'footer_brand_lmr_text',
        'type'     => 'textarea',
        'std'      => '子不语博客域名（zibuyu.life）申请于2016年4曰1日愚人节，博客始建于2017年3月14日，博客主要分享网站建设中遇到的问题及解决方案、自己在读书过程中的心得体会、一些自己觉得有意义的音视频内容，记录些生活中的琐事，希望博客能督促怠惰的自己不断学习，不断进步。',
        'settings' => array(
            'rows' => 3
        )
    );
        
    // 中间区域自定义
    for ($i=1; $i <= 3; $i++){
    
    $options[] = array(
        'desc'     => __('<b><i>'.$i.'</i>二维码 ID</b> # 二维码图片下方的 ID', 'QGG'),
        'id'       => 'footer_brand_lmr_qr_id_'.$i,
        'type'     => 'text',
        'std'      => '子不语'
    );
        
    $options[] = array(
        'desc'     => __('<b><i>'.$i.'</i>二维码描述</b> # 二维码下方的简单描述', 'QGG'),
        'id'       => 'footer_brand_lmr_qr_desc_'.$i,
        'type'     => 'text',
        'std'      => '微信公众号'
    );
    
    $options[] = array(
        'desc'     => __('<b><i>'.$i.'</i>二维码图片</b>', 'QGG'),
        'id'       => 'footer_brand_lmr_qr_img_'.$i,
        'type'     => 'upload',
        'std'      => $img_uri.'qrcode.png'
    );
    
    }
    
    // 右侧区域自定义
    $options[] = array(
        'desc'     => __('<b>右侧超链接标题</b>', 'QGG'),
        'id'       => 'footer_brand_lmr_title',
        'std'      => '精彩直达',
        'type'     => 'text'
    );
    
    for ($j=1; $j <= 9; $j++) {
    $options[] = array(
        'desc'     => __('<b><i>'.$j.'</i>按钮文本</b>', 'QGG'),
        'id'       => 'footer_brand_lmr_link_name_'.$j,
        'type'     => 'text',
        'std'      => '子不语'
    );
        
    $options[] = array(
        'desc'     => __('<b><i>'.$j.'</i>按钮链接</b>', 'QGG').$j,
        'id'       => 'footer_brand_lmr_link_href_'.$j,
        'type'     => 'text',
        'std'      => 'https://zibuyu.life/'
    );
    }

    
    
    
    
    $options[] = array(
        'name'     => __( '首页模块', 'QGG' ),
        'type'     => 'heading'
    );
    
    // 首页推荐盒子    
    $options[] = array(
        'name'     => __('首页专题盒子模块', 'QGG'),
        'desc'     => __('开启，专题盒子', 'QGG'),
        'id'       => 'topic_card_box_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    for ($i=1; $i <= 4; $i++) { 
    $options[] = array(
        'desc'     => __('<b><i>'.$i.'</i>背景图片</b> # 建议尺寸：480pxx160px', 'QGG'),
        'id'       => 'topic_card_box_img-'.$i,
        'type'     => 'upload',
        'std'      => $img_uri.'topic.png'
    );

    $options[] = array(
        'desc'     => __('<b><i>'.$i.'</i>图上标题</b> # 建议 5 字以内','QGG'),
        'id'       => 'topic_card_box_title-'.$i,
        'type'     => 'text',
        'std'      => '子不语'
    );
    
    $options[] = array(
        'desc'     => __('<b><i>'.$i.'</i>图下主描述</b> # 建议 15 字以内','QGG'),
        'id'       => 'topic_card_box_desc01-'.$i,
        'type'     => 'text',
        'std'      => '一个不学无术的伪程序员'
    ); 
    $options[] = array(
        'desc'     => __('<b><i>'.$i.'</i>图下副描述</b> # 建议 10 字以内','QGG'),
        'id'       => 'topic_card_box_desc02-'.$i,
        'type'     => 'text',
        'std'      => '记录成长 | 分享快乐'
    ); 
    
    $options[] = array(
        'desc'     =>  __('<b><i>'.$i.'</i>跳转链接</b>','QGG'),
        'id'       => 'topic_card_box_link-'.$i,
        'type'     => 'text',
        'std'      => 'https://zibuyu.life'
    );
    }
    
    // 首页图片盒子    
    $options[] = array(
        'name'     => __('首页图片盒子模块', 'QGG'),
        'desc'     => __('开启，图片盒子', 'QGG'),
        'id'       => 'img_box_posts_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    $options[] = array(
        'desc'     => __('开启，文章标题 # 鼠标悬浮后显示', 'QGG'),
        'id'       => 'img_box_posts_title_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    // 最新文章摘列表    
    $options[] = array(
        'name'     => __('最新文章列表模块', 'QGG'),
        'desc'     => __('开启，最新文章列表', 'QGG'),
        'id'       => 'new_posts_excerpt_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    // 双栏特性文章列表    
    $options[] = array(
        'name'     => __('双栏特性文章列表', 'QGG'),
        'desc'     => __('开启，双栏特性文章(热门、热评、随机、点赞…)', 'QGG'),
        'id'       => 'posts_list_double_s1_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>左侧列表标题</b>', 'QGG'),
        'id'       => 'posts_list_double_s1_title_left',
        'type'     => 'text',
        'std'      => '随机推荐'
    );
    
    $options[] = array(
        'desc'     => __('<b>左侧特性</b> # 热门、热评、随机、点赞…', 'QGG'),
        'id'       => 'posts_list_double_s1_feature_left',
        'type'     => 'select',
        'std'      => 'rand',
        'options'  => $feature_post
    );
    
    $options[] = array(
        'desc'     => __('<b>右侧列表标题</b>', 'QGG'),
        'id'       => 'posts_list_double_s1_title_right',
        'type'     => 'text',
        'std'      => '最多评论'
    );
    
    $options[] = array(
        'desc'     => __('<b>右侧特性</b> # 热门、热评、随机、点赞…', 'QGG'),
        'id'       => 'posts_list_double_s1_feature_right',
        'type'     => 'select',
        'std'      => 'comment',
        'options'  => $feature_post
    );
        
    // 双栏分类文章列表    
    $options[] = array(
        'name'     => __('双栏分类文章列表</b>', 'QGG'),
        'desc'     => __('开启，双栏分类文章列表', 'QGG'),
        'id'       => 'posts_list_double_s2_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>左侧列表标题</b>', 'QGG'),
        'id'       => 'posts_list_double_s2_title_left',
        'type'     => 'text',
        'std'      => '分类推荐'
    );
    
    $options[] = array(
        'desc'     => __('<b>左侧文章分类</b>', 'QGG'),
        'id'       => 'posts_list_double_s2_catId_left',
        'type'     => 'select',
        'std'      => '',
        'options'  => $options_categories
        
    );
    
    $options[] = array(
        'desc'     => __('<b>右侧列表标题</b>', 'QGG'),
        'id'       => 'posts_list_double_s2_title_right',
        'type'     => 'text',
        'std'      => '分类推荐'
    );
    
    $options[] = array(
        'desc'     => __('<b>右侧文章分类</b>', 'QGG'),
        'id'       => 'posts_list_double_s2_catId_right',
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
        'desc'     => __('开启，分享模块', 'QGG'),
        'id'       => 'post_share_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>按钮标题</b> # 分享到：', 'QGG'),
        'id'       => 'post_share_text',
        'type'     => 'text',
        'std'      => '分享到：'
    );
    
    $options[] = array(
        'name'     => __('点赞模块', 'QGG'),
        'desc'     => __('开启，点赞模块', 'QGG'),
        'id'       => 'post_like_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>按钮标题</b> # 喜欢', 'QGG'),
        'id'       => 'post_like_text',
        'type'     => 'text',
        'std'      => '喜欢'
    );
    
    $options[] = array(
        'name'     => __('海报模块', 'QGG'),
        'desc'     => __('开启，海报模块', 'QGG'),
        'id'       => 'post_poster_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>按钮标题</b> # 分享海报', 'QGG'),
        'id'       => 'post_poster_text',
        'type'     => 'text',
        'std'      => '分享海报'
    );
    
    $options[] = array(
        'desc'     => __('<b>顶部Logo</b> # 海报顶部的Logo图片', 'QGG'),
        'id'       => 'post_poster_logo',
        'type'     => 'upload',
        'std'      => $img_uri.'site-logo-pure.png'
    );
    
    $options[] = array(
        'desc'     => __('<b>底部Icon</b> # 海报底部的ICON图片', 'QGG'),
        'id'       => 'post_poster_icon',
        'type'     => 'upload',
        'std'      => $img_uri.'favicon.ico'
    );
    
    $options[] = array(
        'desc'     => __('<b>底部标语</b> # 扫码查阅文章详情', 'QGG'),
        'id'       => 'post_poster_slogan',
        'type'     => 'text',
        'std'      => '扫码查阅文章详情'
    );
    
    $options[] = array(
        'name'     => __('打赏模块', 'QGG'),
        'desc'     => __('开启，打赏模块', 'QGG'),
        'id'       => 'post_rewards_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>打赏文字</b> # 赏个钱儿', 'QGG'),
        'id'       => 'post_rewards_text',
        'type'     => 'text',
        'std'      => '赏个钱儿'
    );
    
    $options[] = array(
        'desc'     => __('<b>弹窗标题</b> # 弹窗上的标题文字', 'QGG'),
        'id'       => 'post_rewards_title',
        'type'     => 'text',
        'std'      => '觉得文章有用就打赏一下文章作者'
    );
    
    $options[] = array(
        'desc'     => __('<b>支付宝二维码</b> # 弹窗中的支付宝二维码', 'QGG'),
        'id'       => 'post_rewards_alipay',
        'type'     => 'upload',
        'std'      => $img_uri.'qrcode.png'
    );
    
    $options[] = array(
        'desc'     => __('<b>微信二维码</b> # 弹窗中的微信二维码', 'QGG'),
        'id'       => 'post_rewards_wechat',
        'type'     => 'upload',
        'std'      => $img_uri.'qrcode.png'
    );
    
    
    



    
    $options[] = array(
        'name'     => __( '浮动客服', 'QGG' ),
        'type'     => 'heading'
    );
    $options[] = array(
        'name'     => __('整站客服系统', 'QGG'),
        'desc'     => __('开启，网站右侧显示一个浮动客服', 'QGG'),
        'id'       => 'rollbar_kefu_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>功能排序_PC</b> # PC端各客服功能的显示顺序，空格隔开。默认：6 5 4 3 2 1', 'QGG'),
        'id'       => 'rollbar_kefu_sort',
        'type'     => 'text',
        'std'      => '6 5 4 3 2 1'
    );
    
    $options[] = array(
        'desc'     => __('开启，手机端底部显示客服系统', 'QGG'),
        'id'       => 'rollbar_kefu_m_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>功能排序_M</b> # 移动端各客服功能的显示顺序，空格隔开。默认：6 5 4 3 2 1', 'QGG'),
        'id'       => 'rollbar_kefu_m_sort',
        'type'     => 'text',
        'std'      => '6 5 4 3 2 1'
    );
    
    $options[] = array(
        'desc'     => __('开启，手机端显示会员中心，方便用户登录', 'QGG'),
        'id'       => 'rollbar_kefu_user_m_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>会员中心_M</b> # 只在开启手机端会员中心时显示。默认：会员中心','QGG'),
        'id'       => 'rollbar_kefu_user_m_tip',
        'type'     => 'text',
        'std'      => '会员中心'
    );
    
    // 去顶部
    $options[] = array(
        'desc'     => __('<b>回顶部_PC</b> # 回顶部(排序ID:<i>1</i>)','QGG'),
        'id'       => 'rollbar_kefu_top_tip',
        'type'     => 'text',
        'std'      => '回顶部'
    );
    
    $options[] = array(
        'desc'     => __('<b>回顶部_M</b> # 回顶(排序ID:<i>1</i>)','QGG'),
        'id'       => 'rollbar_kefu_top_m_tip',
        'type'     => 'text',
        'std'      => '回顶'
    );
    
    // 去评论
    $options[] = array(
        'desc'     => __('<b>去评论_PC</b># 去评论(排序ID:<i>2</i>)','QGG'),
        'id'       => 'rollbar_kefu_comment_tip',
        'type'     => 'text',
        'std'      => '去评论'
    );
    
    $options[] = array(
        'desc'     => __('<b>去评论_M</b> # 评论(排序ID:<i>2</i>)','QGG'),
        'id'       => 'rollbar_kefu_comment_m_tip',
        'type'     => 'text',
        'std'      => '评论'
    );
    
    // 电话
    $options[] = array(
        'desc'     => __('<b>电话咨询_PC</b> # 电话咨询(排序ID:<i>3</i>)','QGG'),
        'id'       => 'rollbar_kefu_tel_tip',
        'type'     => 'text',
        'std'      => '电话咨询'
    );
    
    $options[] = array(
        'desc'     => __('<b>电话咨询_M</b> # 电话(排序ID:<i>3</i>)','QGG'),
        'id'       => 'rollbar_kefu_tel_m_tip',
        'type'     => 'text',
        'std'      => '电话'
    );
    
    $options[] = array(
        'desc'     => __('<b>电话号码</b> # 156########','QGG'),
        'id'       => 'rollbar_kefu_tel_num',
        'type'     => 'text',
        'std'      => '123456789000'
    );
    
    // 企鹅
    $options[] = array(
        'desc'     => __('<b>企鹅客服_PC</b> # QQ咨询(排序ID:<i>4</i>)','QGG'),
        'id'       => 'rollbar_kefu_qq_tip',
        'std'      => 'QQ咨询',
        'type'     => 'text'
    );
    
    $options[] = array(
        'desc'     => __('<b>企鹅客服_M</b> # QQ(排序ID:<i>4</i>)','QGG'),
        'id'       => 'rollbar_kefu_qq_m_tip',
        'type'     => 'text',
        'std'      => 'QQ'
    );
    
    $options[] = array(
        'desc'     => __('<b>QQ号码</b> # 2220379479','QGG'),
        'id'       => 'rollbar_kefu_qq_num',
        'type'     => 'text',
        'std'      => '2220379479'
    );
    
    // 微信
    $options[] = array(
        'desc'     => __('<b>微信客服_PC</b> # 关注微信(排序ID:<i>5</i>)','QGG'),
        'id'       => 'rollbar_kefu_wechat_tip',
        'type'     => 'text',
        'std'      => '关注微信'
    );
    
    $options[] = array(
        'desc'     => __('<b>微信客服_M</b> # 微信(排序ID:<i>5</i>)','QGG'),
        'id'       => 'kefu_wechat_m_tip',
        'type'     => 'text',
        'std'      => '微信'
    );
    
    $options[] = array(
        'desc'     => __('<b>微信二维码</b> # 建议图片尺寸：200x200', 'QGG'),
        'id'       => 'rollbar_kefu_wechat_qr',
        'type'     => 'upload',
        'std'      => $img_uri.'qrcode.png'
    );
    
    // 自定义
    $options[] = array(
        'desc'     => __('<b>自定义客服_PC</b> # 在线咨询(排序ID:<i>6</i>)','QGG'),
        'id'       => 'rollbar_kefu_diy_tip',
        'std'      => '在线咨询',
        'type'     => 'text'
    );
    $options[] = array(
        'desc'     => __('<b>自定义客服_M</b> # 在线(排序ID:<i>6</i>)','QGG'),
        'id'       => 'rollbar_kefu_diy_m_tip',
        'type'     => 'text',
        'std'      => '在线'
    );
    
    $options[] = array(
        'desc'     => __('<b>自定义链接</b> # a 标签的 href 属性值'),
        'id'       => 'rollbar_kefu_diy_link',
        'type'     => 'text',
        'std'      => 'https://zibuyu.life/'
    );



    
    
    
    
    $options[] = array(
        'name'     => __( '文章相关', 'QGG' ),
        'type'     => 'heading'
    );
    
    $options[] = array(
        'name'     => __('通用模块', 'QGG'),
        'desc'     => __('开启，文章末尾显示版权信息', 'QGG'),
        'id'       => 'post_copyright_on',
        'type'     => "checkbox",
        'std'      => true,
    );

    $options[] = array(
        'desc'     => __('<b>版权标题</b> # 显示在文章页尾版权提示上方的标题文字', 'QGG'),
        'id'       => 'post_copyright_title',
        'type'     => 'text',
        'std'      => '未经允许不得转载'
    );
    
    $options[] = array(
        'desc'     => __('开启，在文章底部显示作者信息', 'QGG'),
        'id'       => 'post_author_on',
        'type'     => "checkbox",
        'std'      => true,
    );
    
    $options[] = array(
        'desc'     => __('开启，在文章底部显示上一篇下一篇导航', 'QGG'),
        'id'       => 'post_prevnext_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('开启，文章底部翻页导航显示缩略图片', 'QGG'),
        'id'       => 'post_prevnext_img',
        'type'     => "checkbox",
        'std'      => true,
    );
    
    $options[] = array(
        'desc'     => __('开启，在文章底部显示相关文章', 'QGG'),
        'id'       => 'posts_related_on',
        'type'     => "checkbox",
        'std'      => true
    );

    $options[] = array(
        'desc'     => __('开启，相关文章采用图文盒子样式', 'QGG'),
        'id'       => 'posts_related_thumb_on',
        'type'     => "checkbox",
        'std'      => false,
    );

    $options[] = array(
        'desc'     => __('<b>模块标题</b> # 显示在模块顶部的标题文本', 'QGG'),
        'id'       => 'posts_related_title',
        'type'     => 'text',
        'std'      => '相关推荐'
    );

    $options[] = array(
        'desc'     => __('<b>显示数量</b> # 控制相关文章的数量', 'QGG'),
        'id'       => 'posts_related_num',
        'type'     => 'text',
        'std'      => 8,
        'class'    => 'mini'
    );

    // 视频播放器
    $options[] = array(
        'name'     => __('视频文章', 'QGG'),
        'desc'     => __('<b>电脑端高度</b> # 视频播放器的高度，单位：px', 'QGG'),
        'id'       => 'video_player_height',
        'type'     => 'text',
        'std'      => 500
    );

    $options[] = array(
        'desc'     => __('<b>手机端高度</b> # 视频播放器的高度，单位：px', 'QGG'),
        'id'       => 'video_player_height_m',
        'type'     => 'text',
        'std'      => 300
    );

    $options[] = array(
        'desc'     => __('<b>视频封面</b> # 视频播放器上默认显示的图片', 'QGG'),
        'id'       => 'video_player_poster',
        'type'     => "upload",
        'std'      => $img_uri.'video-poster.png'
    );
    
    for ($i=1; $i <= 3; $i++) {
    $options[] = array(
        'desc'     => __('<b>解析类型</b><i>'.$i.'</i> # 采用什么方式解析', 'QGG'),
        'id'       => 'video_player_jx_type-'.$i,
        'type'     => 'select',
        'std'      => '',
        'options'  => array(
            'dplayer'  => __('DPlayer', 'QGG'),
            'iframe'   => __('IFrame', 'QGG'),
        )
    );

    $options[] = array(
        'desc'     => __('<b>解析名称</b><i>'.$i.'</i> # 前端显示的名称，方便记忆', 'QGG'),
        'id'       => 'video_player_jx_name-'.$i,
        'type'     => 'text',
        'std'      => ''
    );

    $options[] = array(
        'desc'     => __('<b>解析API</b><i>'.$i.'</i> # 接口形式：http(s)://domain.com?url=', 'QGG'),
        'id'       => 'video_player_jx_api-'.$i,
        'type'     => 'text',
        'std'      => ''
    );
    }
    
    
    
    
    
    
    
    $options[] = array(
        'name'     => __( '页面模板', 'QGG' ),
        'type'     => 'heading'
    );
    
    $options[] = array(
        'name'     => __('友情链接', 'QGG'),
        'desc'     => __('<b>友情链接分类</b> # 需定义【链接分类】且有链接', 'QGG'),
        'id'       => 'page_friendly_link_cats',
        'type'     => 'select',
        'std'      => '',
        'options'  => $options_linkcats
    );
    
    $options[] = array(
        'name'     => __('读者墙', 'QGG'),
        'desc'     => __('<b>限制时间</b> # 限制在多少月内评论的才显示出来', 'QGG'),
        'id'       => 'readers_wall_limit_time',
        'type'     => 'text',
        'std'      => 36,
        'class'    => 'mini'
    );

    $options[] = array(
        'desc'     => __('<b>限制个数</b> # 限制最多显示的用户数量', 'QGG'),
        'id'       => 'readers_wall_limit_num',
        'type'     => 'text',
        'std'      => 200,
        'class'    => 'mini'
    );
    
    $options[] = array(
        'name'     => __('产品分类', 'QGG'),
        'desc'     => __('开启，产品分类页面侧栏显示文章数量', 'QGG'),
        'id'       => 'cat_product_show_count',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('开启，产品分类页面侧栏显示页面二维码', 'QGG'),
        'id'       => 'cat_product_qrcode_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>二维码标题</b> # 产品分类页面二维码下方的标题', 'QGG'),
        'id'       => 'cat_product_qrcode_title',
        'type'     => 'text',
        'std'      => __('手机扫码查看', 'QGG')
    );

    $options[] = array(
        'name'     => __('视频分类', 'QGG'),
        'desc'     => __('开启，多重分类筛选功能', 'QGG'),
        'id'       => 'cat_video_filter',
        'type'     => "checkbox",
        'std'      => true
    );

    $options[] = array(
        'desc'     => __('<b>每页视频数量</b> # 将替换【设置/阅读】下的博客页面至多显示数量，注意这个值要大于【设置/阅读】下的配置，否则会报错！', 'QGG'),
        'id'       => 'cat_video_per_page',
        'type'     => 'text',
        'std'      => 60
    );

    $options[] = array(
        'desc'     => __('开启，AJAX 分页无限加载 # 将取代分页导航', 'QGG'),
        'id'       => 'video_ias_on',
        'type'     => "checkbox",
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>无限加载页数</b> # 多少页内无限加载，之后显示【加载更多】文本。<=0：无限加载', 'QGG'),
        'id'       => 'video_ias_num',
        'type'     => 'text',
        'std'      => 3
    );

    $options[] = array(
        'desc'     => __('<b>无限加载提示文本</b> # 点击加载更多', 'QGG'),
        'id'       => 'video_ias_tip',
        'type'     => 'text',
        'std'      => '点击加载更多'
    );
    
    
    
    
    
    
    $options[] = array(
        'name'     => __( '会员中心', 'QGG' ),
        'type'     => 'heading'
    );
    
    $options[] = array(
        'name'     => __( '会员中心', 'QGG' ),
        'desc'     => __( '开启，允许用户注册登录并管理自己的用户中心', 'QGG' ),
        'id'       => 'user_center_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>用户页面</b> # 选择【会员中心】页面模板', 'QGG'),
        'id'       => 'user_center_page',
        'type'     => 'select',
        'std'      => '',
        'options'  => $options_pages
    );

    $options[] = array(
        'desc'     => __('<b>密码找回</b> # 选择【密码找回】页面模板', 'QGG'),
        'id'       => 'user_reset_passward_page',
        'type'     => 'select',
        'std'      => '',
        'options'  => $options_pages
    );
    
    $options[] = array(
        'desc'     => __('开启，允许用户发布文章，发布文章后需后台审核', 'QGG'),
        'id'       => 'user_publish_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('开启，用户发布文章后发送邮件通知站长，需开启允许用户发布文章', 'QGG'),
        'id'       => 'user_publish_alert_mail_on',
        'type'     => 'checkbox',
        'std'      => false
    );
    
    $options[] = array(
        'desc'     => __('<b>通知邮箱</b> # 用户发布文章后将通知此邮箱', 'QGG'),
        'id'       => 'user_publish_alert_mail_to',
        'type'     => 'text',
        'std'      => ''
    );
    
    $options[] = array(
        'desc'     => __('开启，集成 Erphpdown 功能到会员中心中，需下载安装插件', 'QGG'),
        'id'       => 'user_erphpdown_on',
        'type'     => 'checkbox',
        'std'      => false
    );
    
    $options[] = array(
        'name'     => __( 'SMTP 发信', 'QGG' ),
        'desc'     => __( '开启，SMTP 邮件服务器发信', 'QGG' ),
        'id'       => 'smtp_mailer_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>服务器地址</b> # smtp.qq.com', 'QGG'),
        'id'       => 'smtp_mailer_host',
        'type'     => 'text',
        'std'      => '',
    );
    
    $options[] = array(
        'desc'     => __('<b>发信协议</b> # 建议选择SSL模式更安全', 'QGG'),
        'id'       => 'smtp_mailer_secure',
        'type'     => 'select',
        'std'      => '',
        'options'  => array(
            'ssl'   => __('SSL', 'QGG'),
            'tsl'   => __('TSL', 'QGG'),
            ''      => __('无加密', 'QGG')
        )
    );
    
    $options[] = array(
        'desc'     => __('<b>发信端口</b> # SSL：465或587，TSL：25', 'QGG'),
        'id'       => 'smtp_mailer_port',
        'type'     => 'text',
        'std'      => 465,
    );
    
    $options[] = array(
        'desc'     => __('<b>邮箱账号</b> # xxx@xxx.xxx', 'QGG'),
        'id'       => 'smtp_mailer_user',
        'type'     => 'text',
        'std'      => '',
    );
    
    $options[] = array(
        'desc'     => __('<b>邮箱密码</b> # 注意是授权码', 'QGG'),
        'id'       => 'smtp_mailer_pass',
        'type'     => 'text',
        'std'      => '',
    );
    
    $options[] = array(
        'desc'     => __('<b>发送邮件名称</b> # 用户收到邮件时显示谁发来的，默认为 WordPress', 'QGG'),
        'id'       => 'smtp_mailer_from_name',
        'type'     => 'text',
        'std'      => '',
    );
    
    $options[] = array(
        'desc'     => __('<b>回复邮件地址</b> # 用户回复邮件时回复给谁，默认为发件地址', 'QGG'),
        'id'       => 'smtp_mailer_reply_to',
        'type'     => 'text',
        'std'      => '',
    );
    $options[] = array(
        'desc'     => __('<b>发信测试</b> # 收信人地址：xxx@xxx.xxx', 'QGG'),
        'id'       => 'smtp_mailer_test_to',
        'type'     => 'text',
        'std'      => '',
    );
    
    
    
    
    
    $options[] = array(
        'name'     => __( '功能增强', 'QGG' ),
        'type'     => 'heading'
    );
    
    $options[] = array(
        'name'     => __('功能增强', 'QGG'),
        'desc'     => __('开启，网站右键菜单美化', 'QGG'),
        'id'       => 'right_click_menu_on',
        'type'     => 'checkbox',
        'std'      => false
    );
    
    $options[] = array(
        'desc'     => __('开启，手机端功能调试 # 使用 vConsole 用于手机端调试', 'QGG'),
        'id'       => 'enhance_vconsole_on',
        'type'     => 'checkbox',
        'std'      => false
    );
    
    $options[] = array(
        'desc'     => __('开启，禁止 F12 打开控制台 # 开启后也会禁止右键菜单', 'QGG'),
        'id'       => 'enhance_f12_forbidden_on',
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
        'desc'     => __('开启，文章正文结尾文字广告'),
        'id'       => 'ads_post_footer_text_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __( '<b>推广标题</b> # 子不语', 'QGG' ),
        'id'       => 'ads_post_footer_text_title',
        'type'     => 'text',
        'std'      => '子不语'
    );
    
    $options[] = array(
        'desc'     => __( '<b>推广描述</b> # 一个不学无术的伪程序员', 'QGG' ),
        'id'       => 'ads_post_footer_text_desc',
        'type'     => 'text',
        'std'      => '一个不学无术的伪程序员'
    );
    
    $options[] = array(
        'desc'     =>  __( '开启，新窗口打开', 'QGG' ),
        'id'       => 'ads_post_footer_text_blank',
        'type'     => 'checkbox',
        'std'      => true
    );

    $options[] = array(
        'desc'     =>  __( '<b>推广链接</b> # https://zibuyu.life/', 'QGG' ),
        'id'       => 'ads_post_footer_text_link',
        'type'     => 'text',
        'std'      => 'https://zibuyu.life/'
    );
    
    // 整站广告代码
    $options[] = array(
        'name'     => __('文章列表内嵌广告', 'QGG'),
        'desc'     => __('开启，最新文章列表上广告', 'QGG'),
        'id'       => 'ads_post_list_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('文章列表内嵌广告 # 代码', 'QGG').$ads_desc,
        'id'       => 'ads_post_list_code',
        'type'     => 'textarea',
        'std'      => $ads_01,
        'settings' => array(
            'rows' => 3
        )
    );
    
    $options[] = array(
        'name'     => __('整站评论模块广告', 'QGG'),
        'desc'     => __('开启，整站评论模块上广告', 'QGG'),
        'id'       => 'ads_comment_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    $options[] = array(
        'desc'     => __('整站评论模块上——广告代码', 'QGG').' '.$ads_desc,
        'id'       => 'ads_comment_code',
        'type'     => 'textarea',
        'std'      => $ads_01,
        'settings' => array(
            'rows' => 3
        )
    );
    
    // 默认文章页广告代码
    $options[] = array(
        'name'     => __('默认文章页广告', 'QGG'),
        'desc'     => __('开启，默认文章广告', 'QGG'),
        'id'       => 'ads_post_default_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>默认文章广告代码</b>', 'QGG').$ads_desc,
        'id'       => 'ads_post_default_code',
        'type'     => 'textarea',
        'std'      => $ads_01,
        'settings' => array(
            'rows' => 3
        )
    );
    
    // 视频文章页广告代码
    $options[] = array(
        'name'     => __('视频文章页广告', 'QGG'),
        'desc'     => __('开启，视频文章广告', 'QGG'),
        'id'       => 'ads_post_video_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>视频文章广告代码</b>', 'QGG').$ads_desc,
        'id'       => 'ads_post_video_code',
        'type'     => 'textarea',
        'std'      => $ads_01,
        'settings' => array(
            'rows' => 3
        )
    );

    // 产品文章页广告代码
    $options[] = array(
        'name'     => __('产品文章页广告', 'QGG'),
        'desc'     => __('开启，产品文章广告', 'QGG'),
        'id'       => 'ads_post_product_on',
        'type'     => 'checkbox',
        'std'      => true
    );
    
    $options[] = array(
        'desc'     => __('<b>产品文章广告代码</b>', 'QGG').$ads_desc,
        'id'       => 'ads_post_product_code',
        'type'     => 'textarea',
        'std'      => $ads_01,
        'settings' => array(
            'rows' => 3
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
        'id'       => 'search_baidu_on',
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
        'desc'     => __('开启，网站链接微信公众号', 'QGG'),
        'id'       => 'wechat_official_on',
        'type'     => "checkbox",
        'std'      => false,
    );

    $options[] = array(
        'desc'     => __('<b>AppId</b> # 公众号开发信息中的开发者ID', 'QGG'),
        'id'       => 'wechat_official_appid',
        'type'     => 'text',
        'std'      => ''
    );
    
    $options[] = array(
        'desc'     => __('<b>AppSecret</b> # 公众号开发信息中的开发者密码', 'QGG'),
        'id'       => 'wechat_official_appsecret',
        'type'     => 'text',
        'std'      => ''
    );
    
    $options[] = array(
        'desc'     => __('<b>TOKEN</b> # 公众号服务器配置中的令牌', 'QGG'),
        'id'       => 'wechat_official_token',
        'type'     => 'text',
        'std'      => ''
    );
    
    $options[] = array(
        'desc'     => __('<b>EncodingAesKey</b> # 公众号服务器配置中的消息加解密密钥', 'QGG'),
        'id'       => 'wechat_official_aeskey',
        'type'     => 'text',
        'std'      => ''
    );
    
    $options[] = array(
        'desc'     => __('<b>EncodingMode</b> # 公众号服务器配置中的消息加解密方式', 'QGG'),
        'id'       => 'wechat_official_mode',
        'type'     => 'select',
        'std'      => 'encryption',
        'options'  => $wechat_encoding_mode
    );
    
    $options[] = array(
        'desc'     => __('<b>微信二维码</b> # 正方形，建议图片尺寸：200x200px', 'QGG'),
        'id'       => 'wechat_official_qrcode',
        'type'     => 'upload',
        'std'      => $img_uri.'qrcode.png'
    );
    
    $options[] = array(
        'name'     => __('微信公众号回复消息', 'QGG'),
        'desc'     => __('<b>默认自动回复</b> # 公众号无法处理请求时默认的回复消息', 'QGG'),
        'id'       => 'wechat_official_reply_default',
        'type'     => 'textarea',
        'std'      => '笨笨的机器人暂时无法理解您的请求，请输入【验证码】查看最新验证消息！',
        'settings' => array(
            'rows' => 3
        )
    );
    
    $options[] = array(
        'name'     => __('微信公众号获取验证码', 'QGG'),
        'desc'     => __('<b>验证码有效时间</b> # 设置一个时间生成验证码，超时需重新生成', 'QGG'),
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
        'name' => __( 'Default Text Editor', 'site-stytle-textdomain' ),
        'desc' => sprintf( __( 'You can also pass settings to the editor.  Read more about wp_editor in <a href="%1$s" target="_blank">the WordPress codex</a>', 'site-stytle-textdomain' ), 'http://codex.wordpress.org/Function_Reference/wp_editor' ),
        'id' => 'example_editor',
        'type' => 'editor',
        'settings' => $wp_editor_settings
    );
 */
    return $options;
}