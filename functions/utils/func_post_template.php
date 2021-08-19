<?php
/**
  * Plugin Name: WP Post Template
  * Plugin URI: www.dotsquares.com
  * Description: This plugin allows theme authors to create a post tempate as well as page template for the single post.
  * Author: Dotsquares
  * Version: 1.0
  * Author URI: www.dotsquares.com
*/
add_action('add_meta_boxes','wp_add_post_custom_template');
add_action('save_post','wp_save_custom_post_template',10,2);
add_filter('single_template','wp_get_custom_post_template_for_template_loader');
add_action('add_meta_boxes', 'wp_add_post_custom_template');

function wp_add_post_custom_template($postType) {
	
	if(get_option('wp_custom_post_template') == ''){ //get option value
		$postType_title = 'post';
		$postType_arr[] = $postType_title;
	}else{
		$postType_title = get_option('wp_custom_post_template');
		$postType_arr = explode(',',$postType_title);
	}
	if(in_array($postType, $postType_arr)){
		add_meta_box(
			'postparentdiv',
			__('文章模板选择'),
			'wp_custom_post_template_meta_box',
			$postType,
			'side', 
			'core'
		);
	}
}
function wp_custom_post_template_meta_box($post) {
	if ( $post->post_type != 'page' && 0 != count( wp_get_post_custom_templates() ) ) {
		$template = get_post_meta($post->ID,'_post_template',true);
	?>
		<label class="screen-reader-text" for="post_template"><?php _e('文章模板') ?></label>
		<p>
			<i><?php _e( '<span style="color:red";>下拉选择您的自定义文章模板！！！</span>'); ?>
			</i>
		</p>
		<select name="post_template" id="post_template">
			<option value='default'><?php _e('默认模板'); ?></option>
			<?php wp_custom_post_template_dropdown($template); ?>
		</select>
	<?php
	}
}?>

<?php 
function wp_get_post_custom_templates() {
	if(function_exists('wp_get_themes')){
		$themes = wp_get_themes();
	}else{
		$themes = get_themes();
	}
				
	$theme = get_option( 'template' );
	$templates = $themes[$theme]['Template Files'];
	$post_templates = array();

	if ( is_array( $templates ) ) {
		$base = array( trailingslashit(get_template_directory()), trailingslashit(get_stylesheet_directory()) );

		foreach ( $templates as $template ) {
			$basename = str_replace($base, '', $template);
			if ($basename != 'functions.php') {
				// don't allow template files in subdirectories
				//if ( false !== strpos($basename, '/') )
				//continue;
				
				// 排除当前文件
				if ( false !== strpos($basename, 'func_post_template') )
				continue;
				
				$template_data = implode( '', file( $template ));

				$name = '';
				
				if ( preg_match( '|WP Post Template:(.*)$|mi', $template_data, $name ) ){
				
					$name = _cleanup_header_comment($name[1]);

					if ( !empty( $name ) ) {
						$post_templates[trim( $name )] = $basename;
					}
				}
			}
		}
	}
	return $post_templates;
}

function wp_custom_post_template_dropdown( $default = '' ) {
	$templates = wp_get_post_custom_templates();
	ksort( $templates );
	foreach (array_keys( $templates ) as $template ): 
		if ( $default == $templates[$template] ){
			$selected = " selected='selected'";
		}else{
			$selected = '';
		}	
		echo "\n\t<option value='".$templates[$template]."' $selected>$template</option>";
	endforeach;
}

function wp_save_custom_post_template($post_id,$post) {
	if ($post->post_type !='page' && !empty($_POST['post_template']))
		update_post_meta($post->ID,'_post_template',$_POST['post_template']);
}

function wp_get_custom_post_template_for_template_loader($template) {
	global $wp_query;
	$post = $wp_query->get_queried_object();
	if ($post) {
		$post_template = get_post_meta($post->ID,'_post_template',true);
		if (!empty($post_template) && $post_template!='default')
			$template = get_stylesheet_directory() . "/{$post_template}";
	}
	return $template;
}
?>