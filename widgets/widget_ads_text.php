<?php
/**
 * @name 文字广告
 * @description 在网站侧边栏添加一个文字广告的侧栏小工具，可自定义标签、标题、文字内容、超链接等
 */
?>
<?php
class widget_ads_text extends WP_Widget {

	function __construct(){
		parent::__construct( 'widget_ads_text', __('QGG 文字广告', 'QGG'), array( 'classname' => 'widget-ads-text', 'description'=> '文字标签、文字标题、跳转链接、显示样式' ) );
	}
	// 后台设置选项
	function form($instance) {
		$defaults = array( 
			'title'   => '蝈蝈要安静 | 一个不学无术的伪程序员', 
			'tag'     => '吐血推荐', 
			'blank'   => 'on', 
			'content' => '分享网站建设中遇到的WordPress、Linux，Apache，Nginx，PHP，HTML，CSS等的问题及解决方案；
						分享Windows操作系统及其周边的一些经验知识；分享互联网使用过程中遇到的一些问题及其处理技巧；
						分享一些自己在读书过程中的心得体会；分享一些自己觉得有意义的音视频内容 ... ...', 
			'link'    => 'https://blog.quietguoguo.com/', 
			'style'   => 'style02'
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
				<?php _e( '标签：', 'QGG'); ?>
				<input id="<?php echo $this->get_field_id('tag'); ?>" name="<?php echo $this->get_field_name('tag'); ?>" type="text" value="<?php echo $instance['tag']; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				<?php _e( '描述：', 'QGG'); ?>
				<textarea id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>" class="widefat" rows="3"><?php echo $instance['content']; ?></textarea>
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
				<?php _e( '样式：', 'QGG'); ?>
				<select style="width:100%;" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" style="width:100%;">
					<option value="style01" <?php selected('style01', $instance['style']); ?>>海蓝</option>
					<option value="style02" <?php selected('style02', $instance['style']); ?>>橘红</option>
					<option value="style03" <?php selected('style03', $instance['style']); ?>>草绿</option>
					<option value="style04" <?php selected('style04', $instance['style']); ?>>兰紫</option>
					<option value="style05" <?php selected('style05', $instance['style']); ?>>天青</option>
				</select>
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
		$instance['title']    = $new_instance['title'];
		$instance['tag']      = $new_instance['tag']; 
		$instance['blank']    = $new_instance['blank']; 
		$instance['content']  = $new_instance['content']; 
		$instance['link']     = $new_instance['link'];
		$instance['style']    = $new_instance['style']; 
		return $instance;
	}
	
	// 前端生成显示
	function widget( $args, $instance ) {
		extract( $args );
		
		$borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
		
		$title   = apply_filters('widget_name', $instance['title']);
		$tag     = isset($instance['tag']) ? $instance['tag'] : '';
		$content = isset($instance['content']) ? $instance['content'] : '';
		$link    = isset($instance['link']) ? $instance['link'] : '';
		$style   = isset($instance['style']) ? $instance['style'] : '';
		$blank   = isset($instance['blank']) ? $instance['blank'] : '';
		$lank = '';
		if( $blank ) $lank = ' target="_blank"';
		
		echo $before_widget;
		echo '<a class="'.$style.'" style="'.$borderRadius.'" href="'.$link.'"'.$lank.'>';
		echo '<strong>'.$tag.'</strong>';
		echo '<h3>'.$title.'</h3>';
		echo '<p>'.$content.'</p>';
		echo '</a>';
		echo $after_widget;
	}
}