<?php
/**
 * @name 作者信息
 * @description 在网站侧边栏添加一个作者信息的侧栏小工具用于显示当前文章的作者信息(包含站点名称、站点副标题，站点描述、自定义链接、文章数、评论数、标签数等)
 */
?>
<?php
class widget_post_author extends WP_Widget {
    
    function __construct(){
        parent::__construct( 'widget_post_author', __('QGG 作者信息', 'QGG'), array( 'classname' => 'widget-post-author', 'description'=> '显示当前文章的作者信息' ) );
    }
    // 后台设置选项
    function form($instance) {
        $defaults = array( 
            'img_link'    => get_template_directory_uri() . '/assets/img/sidebar-banner.png',
            'post_num'    => 5, 
            'show_role'   => 'on',
            'show_sns'    => 'on',
            'show_tongji' => 'on',
            'show_desc'   => 'on',
            'show_posts'  => 'on',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        extract($instance);
        ?>
        <p>
            <label>
                <?php _e('顶部招贴图像：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('img_link'); ?>" name="<?php echo $this->get_field_name('img_link'); ?>" value="<?php echo $instance['img_link']; ?>" type="url" class="widefat"/>
            </label>
        </p>
        <p>
            <label>
                <?php _e('显示文章数目：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('post_num'); ?>" name="<?php echo $this->get_field_name('post_num'); ?>" value="<?php echo $instance['post_num']; ?>" type="number" class="widefat"/>
            </label>
        </p>
        <p>
            <label>
                <input id="<?php echo $this->get_field_id('show_role'); ?>" name="<?php echo $this->get_field_name('show_role'); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_role'], 'on' ); ?> >
                <?php _e('显示作者角色', 'QGG'); ?>
            </label>
        </p>
        <p>
            <label>
                <input id="<?php echo $this->get_field_id('show_sns'); ?>" name="<?php echo $this->get_field_name('show_sns'); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_sns'], 'on' ); ?> >
                <?php _e('显示社交信息', 'QGG'); ?>
            </label>
        </p>
        <p>
            <label>
                <input id="<?php echo $this->get_field_id('show_tongji'); ?>" name="<?php echo $this->get_field_name('show_tongji'); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_tongji'], 'on' ); ?> >
                <?php _e('显示统计信息', 'QGG'); ?>
            </label>
        </p>
        <p>
            <label>
                <input id="<?php echo $this->get_field_id('show_desc'); ?>" name="<?php echo $this->get_field_name('show_desc'); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_desc'], 'on' ); ?> >
                <?php _e('显示作者描述', 'QGG'); ?>
            </label>
        </p>
        <p>
            <label>
                <input id="<?php echo $this->get_field_id('show_posts'); ?>" name="<?php echo $this->get_field_name('show_posts'); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_posts'], 'on' ); ?> >
                <?php _e('显示更多文章', 'QGG'); ?>
            </label>
        </p>
        <?php
    }
    
    // 更新设置选项
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['img_link']    = $new_instance['img_link'];
        $instance['post_num']    = $new_instance['post_num']; 
        $instance['show_role']   = $new_instance['show_role']; 
        $instance['show_sns']    = $new_instance['show_sns']; 
        $instance['show_tongji'] = $new_instance['show_tongji'];
        $instance['show_desc']   = $new_instance['show_desc'];
        $instance['show_posts']  = $new_instance['show_posts'];
        return $instance;
    }
    
    // 前端显示内容
    function widget($args, $instance){
        extract( $args );
        
        $img_link    = isset($instance['img_link']) ? $instance['img_link'] : get_template_directory_uri().'/assets/img/sidebar-banner.png';
        $post_num    = isset($instance['post_num']) ? $instance['post_num'] : 5;
        $show_role   = isset($instance['show_role']) ? $instance['show_role'] : '';
        $show_sns    = isset($instance['show_sns']) ? $instance['show_sns'] : '';
        $show_tongji = isset($instance['show_tongji']) ? $instance['show_tongji'] : '';
        $show_desc   = isset($instance['show_desc']) ? $instance['show_desc'] : '';
        $show_posts  = isset($instance['show_posts']) ? $instance['show_posts'] : '';
        
        $author_id    = get_the_author_meta('ID');
        $author_name  = get_the_author_meta('display_name');
        $author_email = get_the_author_meta('user_email');
        $my_post_num  = get_the_author_posts();
        $my_view_num  = get_author_posts_views( $author_id, false );
        $my_cmnt_num  = get_author_posts_comments( $author_id, $author_email, false );
        
    echo $before_widget;
    echo '<img class="banner" src="'.$img_link.'" alt="">';
    echo '<div class="author-info site-style-childA-hover-color">';
        // 用户头像
        echo'<div class="avatar-wrapper">
                <a href="'.get_author_posts_url( $author_id ).'">'._get_avatar( $author_id, $author_email, true, 80 ).'</a>
            </div>';
        // 用户角色
        echo '<div class="role">';
                echo get_the_author_posts_link();
                if ($show_role){
                echo '<span>';
                    if( user_can($author_id,'install_plugins') ){
                        echo '管理员';
                    }elseif( user_can($author_id,'edit_others_posts') ){
                        echo '编辑';
                    }elseif( user_can($author_id,'publish_posts') ){
                        echo'作者';
                    }elseif( user_can($author_id,'delete_posts') ){
                        echo'投稿者';
                    }elseif( user_can($author_id,'read') ){
                        echo'订阅者';
                    }
                echo '</span>';
                }
            echo '</div>';
        echo '<div class="clear"></div>';
        // 社交信息
        if ($show_sns){
        echo '<div class="sns">';
            if( get_the_author_meta( "user_url" ) ){
                echo '<span class="sns-item">
                    <a href="'.get_the_author_meta( "user_url" ).'" rel="nofollow" target="_blank"><i class="fal fa-user"></i><b>用户</b></a>
                </span>';
            }
            if( get_the_author_meta( "qq" ) ){
                echo '<span class="sns-item">
                    <a href="http://wpa.qq.com/msgrd?v=3&uin='.get_the_author_meta( "qq" ).'" rel="nofollow" target="_blank"><i class="fab fa-qq"></i><b>QQ</b></a>
                </span>';
            }
            if( get_the_author_meta( "wechat" ) ){
                echo '<span class="sns-item">
                    <a href="'.get_the_author_meta( "wechat" ).'" rel="nofollow" target="_blank"><i class="fab fa-weixin"></i><b>微信</b></a>
                </span>';
            }    
            if( get_the_author_meta( "weibo" ) ){
                echo '<span class="sns-item">
                    <a href="'.get_the_author_meta( "weibo" ).'" rel="nofollow" target="_blank"><i class="fab fa-weibo"></i><b>微博</b></a>
                </span>';
            }
            if( get_the_author_meta( "email" ) ){
                echo '<span class="sns-item">
                    <a href="mailto:'.get_the_author_meta( "email" ).'" rel="nofollow" target="_blank"><i class="fal fa-envelope"></i><b>邮箱</b></a>
                </span>';
            }
        echo '</div>';
        }
        // 用户统计
        if ($show_tongji){
        echo '<div class="tongji">
                <div class="card">
                    <span class="tag">文章数</span>
                    <b class="num site-style-color">'.$my_post_num.'</b>
                </div>
                <div class="card">
                    <span class="tag">浏览量</span>
                    <b class="num site-style-color">'.$my_view_num.'</b>
                </div>
                <div class="card">
                    <span class="tag">评论数</span>
                    <b class="num site-style-color">'.$my_cmnt_num.'</b>
                </div>
            </div>';
        }
        // 用户描述
        if ($show_desc){
        echo '<div class="desc">
                '.get_the_author_description().'
            </div>';
        }
        // 用户文章
        if ($show_posts){
        echo '<div class="posts">
                <div class="title">
                    <span class="more">
                        <a href="'.get_author_posts_url( $author_id ).'" rel="nofollow" target="_blank">更多<i class="iconfont qgg-cross"></i></a>
                    </span>
                    <h3 class="site-style-color">最新文章</h3>
                </div>
                <ul>';
                global $wpdb;
                $result = $wpdb->get_results( "SELECT comment_count, ID, post_title, post_date FROM $wpdb->posts WHERE post_status='publish' AND post_type='post' AND post_author = $author_id ORDER BY ID DESC LIMIT 0 , $post_num" );
                $i = 0;
                foreach ($result as $post) {
                    $i++;
                    setup_postdata($post);
                    $post_id     = $post->ID;
                    $post_title  = $post->post_title;
                    echo '<li>
                        <span class="tag tag-'.$i.'">'.$i.'</span><a href="'.get_permalink($post_id).'" title="'.$post_title.'">'.$post_title.'</a> 
                    </li>';
                }
            echo '</ul>
            </div>';
        }
    echo '</div>';
    echo $after_widget; 
    
    }
}

//获取作者所有文章浏览量
if(!function_exists('get_author_posts_views')) {
    function get_author_posts_views($author_id = 1 ,$display = true) {

        // 数据库查询
        global $wpdb;
        $sql = "SELECT SUM(meta_value+0)
                FROM $wpdb->posts
                LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
                WHERE meta_key = 'views' AND post_author = $author_id ";

        $query_result = intval($wpdb->get_var($sql));
        
        // 处理查询结果
        if( $display ) {
            echo number_format_i18n($query_result);
        } else {
            return $query_result;
        }
    }
}

//获取作者参与评论的评论数
if(!function_exists('get_author_posts_comments')) {
    function get_author_posts_comments( $author_id = 1, $author_email='', $display = true ) {

        // 数据库查询
        global $wpdb;
        $sql = "SELECT COUNT(comment_author)
                FROM $wpdb->comments
                WHERE comment_approved='1' AND comment_type='' AND (user_id = '$author_id'  OR comment_author_email='$author_email' )";

        $query_result = intval( $wpdb->get_var($sql) );
        
        // 处理查询结果
        if($display) {
            echo number_format_i18n( $query_result );
        } else {
            return $query_result;
        }
    }
}

