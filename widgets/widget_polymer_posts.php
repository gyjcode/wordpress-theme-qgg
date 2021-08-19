<?php
/**
 * @name 关于站点
 * @description 在网站侧边栏添加一个关于站点的侧栏小工具(包含站点名称、站点副标题，站点描述、自定义链接、文章数、评论数、标签数等)
 */
?>
<?php
class widget_polymer_posts extends WP_Widget {
	
	function __construct() {
		parent::__construct( 'widget_polymer_posts', __('QGG 聚合文章', 'QGG'), array( 'classname' => 'widget-polymer-posts','description' =>'最新文章、热门文章、推荐文章、热门文章等' ) );
	}
	
	// 后台设置选项
	function form( $instance ) {
		$defaults = array( 
			'orderby01'      => 'rand',             // 随机文章
			'orderby01_name' => '随机推荐',          // 随机文章标题
			'orderby02'      => 'views',            // 热读文章
			'orderby02_name' => '火爆美文',          // 随机文章标题
			'orderby03'      => 'comts',            // 热评文章
			'orderby03_name' => '热评文章',          // 随机文章标题
			'orderby04'      => 'likes',            // 最爱文章
			'orderby04_name' => '最多喜欢',          // 随机文章标题
			'views_days'     => '30',               // 热读文章限制天数
			'comts_days'     => '30',               // 热评文章限定天数
			'likes_days'     => '30',               // 最爱文章限定天数 
			'cat_limits'     => '',                 // 最新文章排除分类
			'show_thumb'     => 'on',               // 显示文章缩略图像
			'post_num'       => '6',                // 文章显示数量限制
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		extract($instance);
		?>
		<div class="widget-polymer-posts-form">
			
			<!-- 显示选择设置 -->
			<h4 style="margin: 20px 0 10px;"><?php _e('显示选择', 'QGG'); ?></h4>
			<div>
				<p style="display: block; width: 100%; height:30px; margin: 5px 0;">
					<label class="alignleft" style="display: inline-block; width: 50%;">
						<?php _e( '列表01选择：', 'QGG'); ?>
						<select id="<?php echo $this->get_field_id('orderby01'); ?>" name="<?php echo $this->get_field_name('orderby01'); ?>">
							<option value="rand" <?php selected('rand', $instance['orderby01']); ?>>随机文章</option>
							<option value="views" <?php selected('views', $instance['orderby01']); ?>>热读文章</option>
							<option value="comts" <?php selected('comts', $instance['orderby01']); ?>>热评文章</option>
							<option value="likes" <?php selected('likes', $instance['orderby01']); ?>>最爱文章</option>
							<option value="recent" <?php selected('recent', $instance['orderby01']); ?>>最新文章</option>
							<option value="modified" <?php selected('modified', $instance['orderby01']); ?>>最新更新</option>
						</select>
					</label>
					<label class="alignleft" style="display: inline-block; width: 50%;">
						<?php _e( '标题：', 'QGG'); ?>
						<input id="<?php echo $this->get_field_id('orderby01_name'); ?>" name="<?php echo $this->get_field_name('orderby01_name'); ?>" value="<?php echo $instance['orderby01_name']; ?>" type="text" style="max-width: 60%;"/>
					</label>
				</p>
				<p style="display: block; width: 100%; height:30px; margin: 5px 0;">
					<label class="alignleft" style="display: inline-block; width: 50%;">
						<?php _e( '列表02选择：', 'QGG'); ?>
						<select id="<?php echo $this->get_field_id('orderby02'); ?>" name="<?php echo $this->get_field_name('orderby02'); ?>">
							<option value="rand" <?php selected('rand', $instance['orderby02']); ?>>随机文章</option>
							<option value="views" <?php selected('views', $instance['orderby02']); ?>>热读文章</option>
							<option value="comts" <?php selected('comts', $instance['orderby02']); ?>>热评文章</option>
							<option value="likes" <?php selected('likes', $instance['orderby02']); ?>>最爱文章</option>
							<option value="recent" <?php selected('recent', $instance['orderby02']); ?>>最新文章</option>
							<option value="modified" <?php selected('modified', $instance['orderby02']); ?>>最新更新</option>
						</select>
					</label>
					<label class="alignleft" style="display: inline-block; width: 50%;">
						<?php _e( '标题：', 'QGG'); ?>
						<input id="<?php echo $this->get_field_id('orderby02_name'); ?>" name="<?php echo $this->get_field_name('orderby02_name'); ?>" value="<?php echo $instance['orderby02_name']; ?>" type="text" style="max-width: 60%;"/>
					</label>
				</p>
				<p style="display: block; width: 100%; height:30px; margin: 5px 0;">
					<label class="alignleft" style="display: inline-block; width: 50%;">
						<?php _e( '列表03选择：', 'QGG'); ?>
						<select id="<?php echo $this->get_field_id('orderby03'); ?>" name="<?php echo $this->get_field_name('orderby03'); ?>">
							<option value="rand" <?php selected('rand', $instance['orderby03']); ?>>随机文章</option>
							<option value="views" <?php selected('views', $instance['orderby03']); ?>>热读文章</option>
							<option value="comts" <?php selected('comts', $instance['orderby03']); ?>>热评文章</option>
							<option value="likes" <?php selected('likes', $instance['orderby03']); ?>>最爱文章</option>
							<option value="recent" <?php selected('recent', $instance['orderby03']); ?>>最新文章</option>
							<option value="modified" <?php selected('modified', $instance['orderby03']); ?>>最新更新</option>
						</select>
					</label>
					<label class="alignleft" style="display: inline-block; width: 50%;">
						<?php _e( '标题：', 'QGG'); ?>
						<input id="<?php echo $this->get_field_id('orderby03_name'); ?>" name="<?php echo $this->get_field_name('orderby03_name'); ?>" value="<?php echo $instance['orderby03_name']; ?>" type="text" style="max-width: 60%;"/>
					</label>
				</p>
				<p style="display: block; width: 100%; height:30px; margin: 5px 0;">
					<label class="alignleft" style="display: inline-block; width: 50%;">
						<?php _e( '列表04选择：', 'QGG'); ?>
						<select id="<?php echo $this->get_field_id('orderby04'); ?>" name="<?php echo $this->get_field_name('orderby04'); ?>">
							<option value="rand" <?php selected('rand', $instance['orderby04']); ?>>随机文章</option>
							<option value="views" <?php selected('views', $instance['orderby04']); ?>>热读文章</option>
							<option value="comts" <?php selected('comts', $instance['orderby04']); ?>>热评文章</option>
							<option value="likes" <?php selected('likes', $instance['orderby04']); ?>>最爱文章</option>
							<option value="recent" <?php selected('recent', $instance['orderby04']); ?>>最新文章</option>
							<option value="modified" <?php selected('modified', $instance['orderby04']); ?>>最新更新</option>
						</select>
					</label>
					<label class="alignleft" style="display: inline-block; width: 50%;">
						<?php _e( '标题：', 'QGG'); ?>
						<input id="<?php echo $this->get_field_id('orderby04_name'); ?>" name="<?php echo $this->get_field_name('orderby04_name'); ?>" value="<?php echo $instance['orderby04_name']; ?>" type="text" style="max-width: 60%;"/>
					</label>
				</p>
			</div>
			<div class="clear"></div>
			
			<!-- 文章限制设置 -->
			<h4 style="margin: 10px 0;"><?php _e('文章限制', 'QGG'); ?></h4>
			<div>
				<p style="float: left; display: inline-block; width: 50%; margin: 5px 0;">
					<label for="<?php echo $this->get_field_id('views_days'); ?>"><?php _e('热读文章限定天数：', 'QGG'); ?>
						<input id="<?php echo $this->get_field_id('views_days'); ?>" name="<?php echo $this->get_field_name('views_days'); ?>" type="number" min="1" step="1" value="<?php echo $views_days; ?>" />
					</label>
				</p>
				<p style="float: left; display: inline-block; width: 50%; margin: 5px 0;">
					<label for="<?php echo $this->get_field_id('comts_days'); ?>"><?php _e('热评文章限定天数：', 'QGG'); ?>
						<input id="<?php echo $this->get_field_id('comts_days'); ?>" name="<?php echo $this->get_field_name('comts_days'); ?>" type="number" min="1" step="1" value="<?php echo $comts_days; ?>" />
					</label>
				</p>
				<p style="float: left; display: inline-block; width: 50%; margin: 5px 0;">
					<label for="<?php echo $this->get_field_id('likes_days'); ?>"><?php _e('最爱文章限定天数：', 'QGG'); ?>
						<input id="<?php echo $this->get_field_id('likes_days'); ?>" name="<?php echo $this->get_field_name('likes_days'); ?>" type="number" min="1" step="1" value="<?php echo $likes_days; ?>" />
					</label>
				</p>
				<p style="float: left; display: inline-block; width: 50%; margin: 5px 0;">
					<label for="<?php echo $this->get_field_id('cat_limits'); ?>"><?php _e('最新文章排除分类：', 'QGG'); ?>
						<input id="<?php echo $this->get_field_id('cat_limits'); ?>" name="<?php echo $this->get_field_name('cat_limits'); ?>" type="text" value="<?php echo $cat_limits; ?>" />
					</label>
				</p>
			</div>
			<div class="clear"></div>
			
			<!-- 公共选项设置 -->
			<h4 style="margin: 10px 0;"><?php _e('公共选项', 'QGG'); ?></h4>
			<div>
		
				<p style="display: inline-block; width: 100%; margin: 5px 0;">
					<label for="<?php echo $this->get_field_id("show_thumb"); ?>">
						<?php _e( '显示特色图像：', 'QGG'); ?>
						<input id="<?php echo $this->get_field_id("show_thumb"); ?>" name="<?php echo $this->get_field_name("show_thumb"); ?>" type="checkbox" class="widefat" <?php checked( $instance['show_thumb'], 'on' ); ?> />
					</label>
				</p>
				<p style="display: inline-block; width: 100%; margin: 5px 0;">
					<label for="<?php echo $this->get_field_id('post_num'); ?>">
						<?php _e('显示数量限制：', 'QGG'); ?>
						<input id="<?php echo $this->get_field_id('post_num'); ?>" name="<?php echo $this->get_field_name('post_num'); ?>" type="number" min="1" step="1" value="<?php echo $post_num; ?>" />
					</label>
				</p>
			</div>
			<div class="clear"></div>
			
		</div>
		<?php 
	}
	
	// 更新设置选项
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['orderby01']         = $new_instance['orderby01'];
		$instance['orderby01_name']    = $new_instance['orderby01_name']; 
		$instance['orderby02']         = $new_instance['orderby02']; 
		$instance['orderby02_name']    = $new_instance['orderby02_name']; 
		$instance['orderby03']         = $new_instance['orderby03'];
		$instance['orderby03_name']    = $new_instance['orderby03_name']; 
		$instance['orderby04']         = $new_instance['orderby04'];
		$instance['orderby04_name']    = $new_instance['orderby04_name']; 
		$instance['views_days']        =  $new_instance['views_days'];
		$instance['comts_days']        =  $new_instance['comts_days'];
		$instance['likes_days']        =  $new_instance['likes_days'];
		$instance['cat_limits']        =  $new_instance['cat_limits'];
		$instance['show_thumb']        = $new_instance['show_thumb'];
		$instance['post_num']          = $new_instance['post_num'];
		return $instance;
	}
	
	//前端生成显示
	function widget( $args, $instance ) {
		extract($args);
		
		$orderby01      = isset($instance['orderby01']) ? $instance['orderby01'] : 'rand';
		$orderby01_name = isset($instance['orderby01_name']) ? $instance['orderby01_name'] : '随机推荐';
		$orderby02      = isset($instance['orderby02']) ? $instance['orderby02'] : 'views';
		$orderby02_name = isset($instance['orderby02_name']) ? $instance['orderby02_name'] : '火爆美文';
		$orderby03      = isset($instance['orderby03']) ? $instance['orderby03'] : 'comts';
		$orderby03_name = isset($instance['orderby03_name']) ? $instance['orderby03_name'] : '热评文章';
		$orderby04      = isset($instance['orderby04']) ? $instance['orderby04'] : 'likes';
		$orderby04_name = isset($instance['orderby04_name']) ? $instance['orderby04_name'] : '最多喜欢';
		$views_days     = isset($instance['views_days']) ? $instance['views_days'] : '30';
		$views_days     = isset($instance['comts_days']) ? $instance['comts_days'] : '30';
		$views_days     = isset($instance['likes_days']) ? $instance['likes_days'] : '30';
		$views_days     = isset($instance['cat_limits']) ? $instance['cat_limits'] : '';
		$post_num  = isset($instance['post_num']) ? $instance['post_num'] : '6';
		$show_thumb     = isset($instance['show_thumb']) ? $instance['show_thumb'] : '';
		!$show_thumb ? $class  = "nopic" : "";
		
		echo $before_widget; 
		echo '<div class="title">
				<h3 class="tab-01 actived">'.$orderby01_name.'</h3>
				<h3 class="tab-02">'.$orderby02_name.'</h3>
				<h3 class="tab-03">'.$orderby03_name.'</h3>
				<h3 class="tab-04">'.$orderby04_name.'</h3>
			</div>';
		echo '<div class="content '.$class .'">';
			echo '<ul class="tab-01 actived">';
				get_tab_post_list_by_order( $orderby01, $post_num, $show_thumb );
			echo '</ul>';
			echo '<ul class="tab-02">';
				get_tab_post_list_by_order( $orderby02, $post_num, $show_thumb );
			echo '</ul>';
			echo '<ul class="tab-03">';
				get_tab_post_list_by_order( $orderby03, $post_num, $show_thumb );
			echo '</ul>';
			echo '<ul class="tab-04">';
				get_tab_post_list_by_order( $orderby04, $post_num, $show_thumb );
			echo '</ul>';
		echo '</div>';
		echo '<div class="clear"></div>';
		echo $after_widget; 
	}
}

function get_tab_post_list_by_order( $orderby, $post_num, $show_thumb){
	
	if ( $orderby == "rand" ){
		$orderby = "rand"; $metakey = ''; $meta = get_the_time('Y-m-d');
	}elseif( $orderby == "views" ){
		$orderby = 'meta_value_num'; $metakey = 'views'; $meta = '阅读 ('._get_post_views().')';
	}elseif( $orderby=="comts" ){
		$orderby = 'comment_count'; $metakey = ''; $meta = '评论('.get_comments_number('0', '1', '%').')';
	}elseif( $orderby == "likes" ){
		$orderby = 'meta_value_num'; $metakey = 'likes'; $meta = '喜欢 ('._get_post_likes().')';
	}elseif( $orderby == "recent" ){
		$orderby = 'post_date'; $metakey = ''; $meta = get_the_time('Y-m-d');
	}elseif( $orderby == "modified" ){
		$orderby = 'modified'; $metakey = ''; $meta = get_the_modified_time('Y-m-d');
	}else{
		$orderby = ''; $metakey = ''; $meta = '';
	}
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	$query_post = array(
		'posts_per_page'      => $post_num,
		'meta_key'            => $metakey,
		'ignore_sticky_posts' => 1,
		'post_status'         => 'publish',
		'paged'               => $paged,
		'post_type'           => 'post',
		'orderby'             => $orderby,
	);
	query_posts( $query_post );
	$i = 0;
	while(have_posts()):the_post();
	$i++;
	?>
	<li>
		<a <?php the_post_target_blank(); ?> href="<?php the_permalink(); ?>">
			<?php if( $show_thumb ){ ?>
			<div class="thumb"><?php echo _get_post_thumbnail(); ?></div> 
			<?php }else{ ?>
			<span class="label label-<?php echo $i; ?>"><?php echo $i; ?></span>
			<?php } ?>
			<div class="desc">
				<h4><?php the_title(); ?><?php _get_the_subtitle(); ?></h4>
				<div class="muted">
					<span class="author"><?php echo get_the_author(); ?></span>
					<span class="meta"><?php echo $meta;?></span>
				</div>
			</div>
		</a>
	</li>
	<?php
	endwhile;
	
	wp_reset_query();
}
