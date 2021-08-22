<?php
/**
 * @name 图片广告
 * @description 在网站侧边栏添加一个图片广告的侧栏小工具，可自定义广告名称方便管理，自定义广告代码并以图片形式显示出来
 */
?>
<?php
class widget_ads_img extends WP_Widget {
    
    function __construct(){
        parent::__construct( 'widget_ads_img', __('QGG 图片广告', 'QGG'), array( 'classname' => 'widget-ads-img', 'description'=> '广告图片、跳转链接' ) );
    }
    
    // 后台设置选项
    function form($instance) {
        $defaults = array( 
            'title' => '图片广告',
            'code' => '<a href="https://zibuyu.life/" target="_blank"><img src="'.get_template_directory_uri().'/assets/img/thumbnail.png"></a>'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        extract($instance);
        ?>
        <p>
            <label>
                <?php _e( '广告名称：', 'QGG'); ?>
                <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat" />
            </label>
        </p>
        <p>
            <label>
                <?php _e( '广告代码：', 'QGG'); ?>
                <textarea id="<?php echo $this->get_field_id('code'); ?>" name="<?php echo $this->get_field_name('code'); ?>" class="widefat" rows="12" style="font-family:Courier New;"><?php echo $instance['code']; ?></textarea>
            </label>
        </p>
        <?php
    }
    
    // 更新设置选项
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['code']  = $new_instance['code']; 
        return $instance;
    }
    
    // 前端生成显示
    function widget( $args, $instance ) {
        extract( $args );
        
        $title = apply_filters('widget_name', $instance['title']);
        $code = isset($instance['code']) ? $instance['code'] : '';
        
        echo $before_widget;
        echo $code;
        echo $after_widget;
    }
}
