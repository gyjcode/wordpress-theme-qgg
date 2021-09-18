<?php
/**
 * @name 最新评论
 * @description 在网站侧边栏添加一个最新评论的侧栏小工具，可自定义设置最新评论的数量并自定义跳转的链接
 */
?>
<?php
class widget_comments_new extends WP_Widget {
    
    function __construct(){
        parent::__construct( 'widget_comments_new', __('QGG 最新评论', 'QGG'), array( 'classname' => 'widget-comments-new', 'description'=> '评论标题、显示数目、排除用户、排除文章、更多链接' ) );
    }
    
    // 后台设置选项
    function form($instance) {
        $defaults = array( 
            'title'   => '最新评论', 
            'limit'   => 8, 
            'outuser' => '0',
            'outpost' => '',
            'link'    =>'https://zibuyu.life'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        extract($instance);
        ?>
        <p>
            <label>
                <?php _e( '评论标题：', 'QGG'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
            </label>
        </p>
        <p>
            <label>
                <?php _e( '显示数目：', 'QGG'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
            </label>
        </p>
        <p>
            <label>
                <?php _e( '排除用户ID：', 'QGG'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('outuser'); ?>" name="<?php echo $this->get_field_name('outuser'); ?>" type="number" value="<?php echo $instance['outuser']; ?>" />
            </label>
        </p>
        <p>
            <label>
                <?php _e( '排除文章ID：', 'QGG'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('outpost'); ?>" name="<?php echo $this->get_field_name('outpost'); ?>" type="number" value="<?php echo $instance['outpost']; ?>" />
            </label>
        </p>
        <p>
            <label>
                <?php _e( '更多链接：', 'QGG'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="url" value="<?php echo $instance['link']; ?>" size="24" />
            </label>
        </p>
        <?php
    }
    
    // 更新设置选项
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']    = $new_instance['title'];
        $instance['limit']    = $new_instance['limit']; 
        $instance['outuser']  = $new_instance['outuser']; 
        $instance['outpost']  = $new_instance['outpost']; 
        $instance['link']     = $new_instance['link'];
        return $instance;
    }
    
    // 前端生成显示
    function widget( $args, $instance ) {
        extract( $args );
    
        $title   = apply_filters('widget_name', $instance['title']);
        $limit   = isset($instance['limit']) ? $instance['limit'] : 8;
        $outuser = isset($instance['outuser']) ? $instance['outuser'] : '0';
        $outpost = isset($instance['outpost']) ? $instance['outpost'] : '';
        $link    = isset($instance['link']) ? $instance['link'] : '';
    
        echo $before_widget;
        echo '
        <div class="title site-style-childA-hover-color">
            <a class="more" href="'.$link.'">更多 <i class="fa fa-angle-right"></i></a>
            <h3>'.$title.'</h3>
        </div>
        <ul>'._new_comments( $limit, $outpost, $outuser) ?: '暂无评论'.'</ul>';
        echo $outpost;
        echo $after_widget;
    }
}

// 获取最新评论
function _new_comments( $limit=8, $outpost=-1, $outuser=-1 ){
    
    // 数据库查询
    global $wpdb;
    $sql = "SELECT DISTINCT comment_ID, comment_post_ID, comment_author, user_id as comment_author_id, comment_author_email, comment_author_url, comment_date_gmt, comment_approved, SUBSTRING(comment_content, 1, 100) AS comment_excerpt, post_title, post_password
            FROM $wpdb->comments
            LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID)
            WHERE comment_post_ID != '.$outpost.' AND user_id != '.$outuser.' AND comment_approved = '1' AND post_password = ''
            ORDER BY comment_date_gmt
            DESC
            LIMIT $limit";

    $query_result = $wpdb->get_results($sql);

    // 处理查询结果
    $output = '';
    foreach ( $query_result as $comment ) {
        $output .= '
        <li>
            <a href="'.get_comment_link($comment->comment_ID).'" title="'.$comment->post_title.'上的评论">
                <div class="avatar-wrapper">'._get_avatar($user_id=$comment->comment_author_id, $user_email=$comment->comment_author_email).'</div>
                <div class="content-wrapper">
                    <span class="site-style-color">'.$comment->comment_author.'<b>'._get_time_ago( $comment->comment_date_gmt ).'说：</b></span>
                    <p>'.convert_smilies(strip_tags($comment->comment_excerpt)).'</p>
                </div>
            </a>
        </li>';
    }
    
    return $output;
};
