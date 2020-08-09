<?php
/**
 * @name 热门标签
 * @description 在网站侧边栏添加一个热门标签的侧栏小工具可自定义设置显示标签的数量及跳转链接
 */
?>
<?php
class widget_hot_tags extends WP_Widget {

	function __construct(){
		parent::__construct( 'widget_hot_tags', __('QGG 热门标签', 'QGG'), array( 'classname' => 'widget-hot-tags', 'description'=> '热门标签、显示数量、标签限制、更多链接' ) );
	}
	
	// 后台设置选项
	function form($instance) {
		$defaults = array( 
			'title' => '热门标签', 
			'count' => 30, 
			'offset'=> 0,
			'link'  => 'https://blog.quietguoguo.com/'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		extract($instance);
		?>
		<p>
			<label>
				<?php _e('标题名称：', 'QGG'); ?>
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				<?php _e('显示数量：', 'QGG'); ?>
				<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" value="<?php echo $instance['count']; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				<?php _e('去除前几个：', 'QGG'); ?>
				<input id="<?php echo $this->get_field_id('offset'); ?>" name="<?php echo $this->get_field_name('offset'); ?>" type="number" value="<?php echo $instance['offset']; ?>" class="widefat" />
			</label>
		</p>
		<p>
			<label>
				<?php _e('更多链接：', 'QGG'); ?>
				<input style="width:100%;" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="url" value="<?php echo $instance['link']; ?>" size="24" />
			</label>
		</p>
		<?php
	}
	
	// 更新设置选项
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']  = $new_instance['title'];
		$instance['count']  = $new_instance['count']; 
		$instance['offset'] = $new_instance['offset']; 
		$instance['link']   = $new_instance['link']; 
		return $instance;
	}
	
	// 前端生成显示
	function widget( $args, $instance ) {
		extract( $args );
		
		$title  = apply_filters('widget_name', $instance['title']);
		$count  = isset($instance['count']) ? $instance['count'] : 30;
		$offset = isset($instance['offset']) ? $instance['offset'] : 0;
		$link   = isset($instance['link']) ? $instance['link'] : '';
		
		echo $before_widget;
		echo '<div class="title">
				<a href="'.$link.'"><span>更多 <i class="iconfont qgg-cross"></i></span></a>
				<h3>'.$title.'</h3>
			</div>'; 
		echo '<div class="tags">';
		$tags_list = get_tags('orderby=count&order=DESC&number='.$count.'&offset='.$offset);
		if ($tags_list) { 
			foreach($tags_list as $tag) {
				echo '<a href="'.get_tag_link($tag).'">'. $tag->name .' ('. $tag->count .')</a>'; 
			} 
		}else{
			echo '暂无标签！！！';
		}
		echo '</div>';
		echo $after_widget;
	}
}