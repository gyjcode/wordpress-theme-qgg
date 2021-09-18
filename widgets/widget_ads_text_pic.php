<?php
/**
 * @name 图文盒子
 * @description 在网站侧边栏添加一个图文盒子的侧栏小工具(包含图文标题、图文描述、自定义按钮、自定义链接、自定义背景等)
 */
?>
<?php
class widget_ads_text_pic extends WP_Widget {

    function __construct(){
        parent::__construct( 'widget_ads_text_pic', __('QGG 图文广告', 'QGG'), array( 'classname' => 'widget-ads-text-pic', 'description'=> '图文标题、图文描述、详情按钮、特色图片、跳转链接' ) );
    }
    
    // 后台设置选项
    function form($instance) {
        $defaults = array( 
            'title'   => '子不语 | 一个不学无术的伪程序员', 
            'desc'    => '分享网站建设中遇到的WordPress、Linux，Apache，Nginx，PHP，HTML，CSS等的问题及解决方案；分享Windows操作系统及其周边的一些经验知识；分享互联网使用过程中遇到的一些问题及其处理技巧；分享一些自己在读书过程中的心得体会；分享一些自己觉得有意义的音视频内容 ... ...', 
            'button'  => '了解详情',
            'link'    => 'https://zibuyu.life/', 
            'imglink' => get_template_directory_uri().'/assets/img/thumbnail.png', 
            'blank'   => 'on'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        extract($instance);
        ?>
        <p>
            <label>
                <?php _e( '标题：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat" />
            </label>
        </p>
        <p>
            <label>
                <?php _e( '描述：', 'QGG'); ?>
                <textarea id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>" class="widefat" rows="3"><?php echo $instance['desc']; ?></textarea>
            </label>
        </p>
        <p>
            <label>
                <?php _e( '按钮：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name('button'); ?>" type="text" value="<?php echo $instance['button']; ?>" class="widefat" />
            </label>
        </p>
        <p>
            <label>
                <?php _e( '链接：', 'QGG'); ?>
                <input style="width:100%;" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="url" value="<?php echo $instance['link']; ?>" size="24" />
            </label>
        </p>
        <p>
            <label>
                <?php _e( '背景：', 'QGG'); ?>
                <input style="width:100%;" id="<?php echo $this->get_field_id('imglink'); ?>" name="<?php echo $this->get_field_name('imglink'); ?>" type="url" value="<?php echo $instance['imglink']; ?>" size="24" />
            </label>
        </p>
        <p>
            <label>
                <input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['blank'], 'on' ); ?> id="<?php echo $this->get_field_id('blank'); ?>" name="<?php echo $this->get_field_name('blank'); ?>">
                <?php _e( '新窗口打开', 'QGG'); ?>
            </label>
        </p>
        <?php
    }
    
    // 更新设置选项
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']   = $new_instance['title'];
        $instance['desc']    = $new_instance['desc']; 
        $instance['button']  = $new_instance['button']; 
        $instance['link']    = $new_instance['link']; 
        $instance['imglink'] = $new_instance['imglink'];
        $instance['blank']   = $new_instance['blank']; 
        return $instance;
    }
    
    // 前端生成显示
    function widget( $args, $instance ) {
        extract( $args );
        
        $title   = apply_filters('widget_name', $instance['title']);
        $desc    = isset($instance['desc']) ? $instance['desc'] : '';
        $button  = isset($instance['button']) ? $instance['button'] : '';
        $link    = isset($instance['link']) ? $instance['link'] : '';
        $imglink = isset($instance['imglink']) ? $instance['imglink'] : '';
        $blank   = isset($instance['blank']) ? $instance['blank'] : '';
        $lank    = '';
        if( $blank ) $lank = ' target="_blank"';
        
        echo $before_widget;
        echo '<img src="'.$imglink.'" alt="">';
        echo '
        <div class="content-wrapper site-style-childA-hover-color">
            <div class="content">
                <h3 class="title">'.$title.'</h3>
                <p>'.$desc.'</p>
                <a class="more" href="'.$link.'"'.$lank.'>'.$button.'</a>
            </div>
        </div>';
        echo $after_widget;
    }
}
