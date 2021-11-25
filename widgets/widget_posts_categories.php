<?php
/**
 * @name 分类文章
 * @description 在网站侧边栏添加一个分类文章的侧栏小工具，可自定义选择指定分类文章予以展示，可自定义设置显示标题、显示文章数、是否显示图片、排序方式等
 */
?>
<?php
class widget_posts_categories extends WP_Widget {
    
    function __construct(){
        parent::__construct( 'widget_posts_categories', __('QGG 分类文章', 'QGG'), array( 'classname' => 'widget-posts-categories', 'description'=> '分类限制、排序方式、文章数目、显示图片、显示评论' ) );
    }
    
    // 后台设置选项
    function form( $instance ) {
        $defaults = array( 
            'title'       => '分类文章', 
            'orderby'     => 'comment_count', 
            'cats_limit'  => '', 
            'post_num'    => 6, 
            'show_thumb'  => 'on',
            'show_comts'  => 'on',
            'show_views'  => 'on',
            'show_likes'  => 'on',
            'show_author' => 'on',
            'link'        => 'https://zibuyu.life'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        extract($instance);
        ?>
        <p>
            <label>
                <?php _e('侧栏标题：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat"/>
            </label>
        </p>
        <p>
            <label>
                <?php _e('设置排序：', 'QGG'); ?>
                <select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" class="widefat">
                    <option value="comment_count" <?php selected('comment_count', $instance['orderby']); ?>>评论数量</option>
                    <option value="views" <?php selected('views', $instance['orderby']); ?>>浏览数量</option>
                    <option value="date" <?php selected('date', $instance['orderby']); ?>>发布时间</option>
                    <option value="rand" <?php selected('rand', $instance['orderby']); ?>>随机排序</option>
                </select>
            </label>
        </p>
        <p>
            <label>
                <?php _e('分类限制：', 'QGG'); ?>
                <a style="font-weight:bold;color:#f60;text-decoration:none;" href="javascript:;" title="格式：1,2 &nbsp;表示限制ID为1,2分类的文章;&#13;&#10;格式：-1,-2 &nbsp;表示排除分类ID为1,2的文章;&#13;&#10;也可直接写1或者-1；注意逗号须是英文的!"> ？</a>
                <input id="<?php echo $this->get_field_id('cats_limit'); ?>" name="<?php echo $this->get_field_name('cats_limit'); ?>" value="<?php echo $instance['cats_limit']; ?>" type="text" class="widefat"/>
            </label>
        </p>
        <p>
            <label>
                <?php _e('显示数目：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('post_num'); ?>" name="<?php echo $this->get_field_name('post_num'); ?>" value="<?php echo $instance['post_num']; ?>" type="number" class="widefat"/>
            </label>
        </p>
        <p>
            <label>
                <?php _e('更多链接：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" value="<?php echo $instance['link']; ?>" type="url" class="widefat"/>
            </label>
        </p>
        <p>
            <label>
                <input id="<?php echo $this->get_field_id('show_thumb'); ?>" name="<?php echo $this->get_field_name('show_thumb'); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_thumb'], 'on' ); ?> >
                <?php _e('显示特色图像', 'QGG'); ?>
            </label>
        </p>
        <p>
            <label>
                <input id="<?php echo $this->get_field_id('show_author'); ?>" name="<?php echo $this->get_field_name('show_author'); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_author'], 'on' ); ?> >
                <?php _e('显示作者信息', 'QGG'); ?>
            </label>
        </p>
        <p>
            <label>
                <input id="<?php echo $this->get_field_id('show_comts'); ?>" name="<?php echo $this->get_field_name('show_comts'); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_comts'], 'on' ); ?> >
                <?php _e('显示评论数量', 'QGG'); ?>
            </label>
        </p>
        <p>
            <label>
                <input id="<?php echo $this->get_field_id('show_views'); ?>" name="<?php echo $this->get_field_name('show_views'); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_views'], 'on' ); ?> >
                <?php _e('显示阅读数量', 'QGG'); ?>
            </label>
        </p>
        <p>
            <label>
                <input id="<?php echo $this->get_field_id('show_likes'); ?>" name="<?php echo $this->get_field_name('show_likes'); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_likes'], 'on' ); ?> >
                <?php _e('显示喜爱数量', 'QGG'); ?>
            </label>
        </p>
        <?php
    }
    
    // 更新设置选项
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']       = $new_instance['title'];
        $instance['orderby']     = $new_instance['orderby']; 
        $instance['cats_limit']  = $new_instance['cats_limit']; 
        $instance['post_num']    = $new_instance['post_num']; 
        $instance['show_thumb']  = $new_instance['show_thumb'];
        $instance['show_author'] = $new_instance['show_author']; 
        $instance['show_comts']  = $new_instance['show_comts'];
        $instance['show_views']  = $new_instance['show_views'];
        $instance['show_likes']  = $new_instance['show_likes']; 
        $instance['link']        = $new_instance['link'];
        return $instance;
    }
    
    // 前端生成显示
    function widget( $args, $instance ) {
        extract( $args );
        
        $title       = apply_filters('widget_name', $instance['title']);
        $post_num    = isset($instance['post_num']) ? $instance['post_num'] : 6;
        $cats_limit  = isset($instance['cats_limit']) ? $instance['cats_limit'] : '';
        $orderby     = isset($instance['orderby']) ? $instance['orderby'] : 'comment_count';
        $show_thumb  = isset($instance['show_thumb']) ? $instance['show_thumb'] : '';
        $show_author = isset($instance['show_author']) ? $instance['show_author'] : '';
        $show_comts  = isset($instance['show_comts']) ? $instance['show_comts'] : '';
        $show_views  = isset($instance['show_views']) ? $instance['show_views'] : '';
        $show_likes  = isset($instance['show_likes']) ? $instance['show_likes'] : '';
        $link        = isset($instance['link']) ? $instance['link'] : '';
        $style       = '';
        if( !$show_thumb ) $style = "nopic";
        
        echo $before_widget;
        echo '
        <div class="title site-style-childA-hover-color">
            <a class="more" href="'.$link.'">更多 <i class="fa fa-angle-right"></i></a>
            <h3>'.$title.'</h3>
        </div>'; 
        echo '<ul class="'.$style.' site-style-childA-hover-color">';
        categories_posts_list( $orderby, $post_num, $cats_limit, $show_thumb, $show_author, $show_comts, $show_views, $show_likes );
        echo '</ul>';
        echo $after_widget;
    }
}

// 获取指定分类文章
function categories_posts_list( $orderby, $post_num, $cats_limit, $show_thumb, $show_author, $show_comts, $show_views, $show_likes ){
    $query_post = array(
        'cat'                 => $cats_limit,
        'order'               => 'DESC',
        'showposts'           => $post_num,
        'ignore_sticky_posts' => 1
    );
    
    if( $orderby !== 'views' ){
        $query_post['orderby'] = $orderby;
    }else{
        $query_post['orderby'] = 'meta_value_num';
        $query_post['meta_query'] = array(
            array(
                'key'   => 'views',
                'order' => 'DESC'
            )
        );
    }
    
    query_posts($query_post);
    while (have_posts()) : the_post();
    ?>
    <li>
        <a <?php _post_target_blank(); ?> href="<?php the_permalink(); ?>">
            <?php if( $show_thumb ){ ?>
            <div class="thumb"><?php echo _get_the_post_thumbnail(); ?></div> 
            <?php } ?>
            <div class="desc">
                <h4><?php the_title(); ?><?php _get_the_post_subtitle(); ?></h4>
                <div class="meta">
                    <span class="time"><?php the_time('m-d');?></span>
                    <?php if( $show_author ){ ?>
                    <span class="author"><?php echo get_the_author(); ?></span>
                    <?php } ?>
                    <?php if( !QGG_Options('site_comment_closed_on') && $show_comts ){ ?>
                    <span class="comts"><?php echo '评论&nbsp(', comments_number('0', '1', '%'), ')'; ?></span>
                    <?php } ?>
                    <?php if( $show_views ){ ?>
                    <span class="views"><?php echo '阅读&nbsp(', _get_the_post_views(), ')'; ?></span>
                    <?php } ?>
                    <?php if( $show_likes ){ ?>
                    <span class="likes"><?php echo '喜欢&nbsp(', _get_the_post_likes(), ')'; ?></span>
                    <?php } ?>
                </div>
            </div>
        </a>
    </li>
    <?php
    endwhile;
    wp_reset_query();
}