<?php
/**
 * @name 关于站点
 * @description 在网站侧边栏添加一个关于站点的侧栏小工具(包含站点名称、站点副标题，站点描述、自定义链接、文章数、评论数、标签数等)
 */
?>
<?php
class widget_about_site extends WP_Widget{
    
    function __construct(){
        parent::__construct( 'widget_about_site', __('QGG 关于站点', 'QGG'), array( 'classname' => 'widget-about-site', 'description'=> '名称、标题、描述、评论数、文章数、标签数、用户数' ) );
    }
    
    // 后台设置选项
    function form($instance) {
        $defaults = array( 
            'title'    => get_bloginfo('name'),  
            'subtitle' => get_bloginfo('description'), 
            'content'  => '分享网站建设中遇到的WordPress、Linux，Apache，Nginx，PHP，HTML，CSS等的问题及解决方案；分享Windows操作系统及其周边的一些经验知识；分享互联网使用过程中遇到的一些问题及其处理技巧；分享一些自己在读书过程中的心得体会；分享一些自己觉得有意义的音视频内容 ... ...', 
            'link'     => 'https://zibuyu.life/contact-us'    
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        extract($instance);
        ?>
        <p>
            <label>
                <?php _e( '站点名称：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" class="widefat" value="<?php echo $instance['title']; ?>"/>
            </label>
        </p>
        <p>
            <label>
                <?php _e( '站点副标题：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" class="widefat" value="<?php echo $instance['subtitle']; ?>"/>
            </label>
        </p>
        <p>
            <label>
                <?php _e( '站点描述：', 'QGG'); ?>
                <textarea id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>" type="textarea" class="widefat" rows="5"><?php echo $instance['content']; ?></textarea>
            </label>
        </p>
        <p>
            <label>
                <?php _e( '关于链接：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="url" class="widefat"  value="<?php echo $instance['link']; ?>" size="24" />
            </label>
        </p>
        <?php
    }
    
    // 更新设置选项
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']     = $new_instance['title'];
        $instance['subtitle']  = $new_instance['subtitle']; 
        $instance['content']   = $new_instance['content']; 
        $instance['link']      = $new_instance['link']; 
        return $instance;
    }
    
    // 前端生成显示
    function widget( $args, $instance ){
        extract( $args );
        extract( $instance );
        
        $title      = isset($instance['title']) ? $instance['title'] : '';
        $subtitle   = isset($instance['subtitle']) ? $instance['subtitle'] : '';
        $content    = isset($instance['content']) ? $instance['content'] : '';
        $link       = isset($instance['link']) ? $instance['link'] : '';
        
        global $wpdb;
        $postcount  = wp_count_posts();            // 文章数
        $commtcount = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments");            // 评论数
        $tagcount   = wp_count_terms('post_tag');            // 标签数
        $usercount  = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");            // 用户数
        
        echo $before_widget;
        echo '
        <div class="content-wrapper">
            <div class="header">
                <h3 class="title"><a class="site-style-color" href="'.$link.'">'.$title.'</a></h3>
                <div class="sub-title">'.$subtitle.'</div>
            </div>
            <div class="content">
                <p>'.$content.'</p> 
            </div>
            <ul class="footer">
                <li><span>文章</span><b class="site-style-color">'.$postcount->publish.'</b></li>
                <li><span>评论</span><b class="site-style-color">'.$commtcount.'</b></li>
                <li><span>标签</span><b class="site-style-color">'.$tagcount.'</b></li>
                <li><span>用户</span><b class="site-style-color">'.$usercount.'</b></li>
            </ul>
        </div>';
        echo $after_widget;
    }
}