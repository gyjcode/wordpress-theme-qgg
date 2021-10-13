<?php
/**
  * Plugin Name: WP Post Template
  * Plugin URI: www.dotsquares.com
  * Description: This plugin allows theme authors to create a post tempate as well as page template for the single post.
  * Author: Dotsquares
  * Version: 1.0
  * Author URI: www.dotsquares.com
*/
// 后台 # 注册一个 Meta 输入框
add_action('add_meta_boxes', '_add_post_template_meta_box');

// 后台 # 添加文章模板选择功能
function _add_post_template_meta_box () {
    add_meta_box(
        '_post_template_meta',
        __('文章模板选择'),
        '_post_template_selector',
        'post',
        'side', 
        'core'
    );
}

// 后台 # 文章模板选择器
function _post_template_selector($post) {
    if ( $post->post_type != 'page' && 0 != count( _get_custom_post_templates() ) ) {
        $template = get_post_meta($post->ID,'_post_template', true);
    ?>
        <label class="screen-reader-text" for="post_template"><?php _e('文章模板') ?></label>
        <p>
            <i><?php _e( '<span style="color:red";>下拉选择您的自定义文章模板！！！</span>'); ?>
            </i>
        </p>
        <select name="post_template" id="post_template">
            <option value='default'><?php _e('默认模板'); ?></option>
            <?php _get_custom_post_templates_item($template); ?>
        </select>
    <?php
    }
}

// 后台 # 获取用户自定义模板
function _get_custom_post_templates() {
    // 获取用户安装的主题
    if(function_exists('wp_get_themes')){
        $themes = wp_get_themes();
    }else{
        $themes = get_themes();
    }
    // 获取当前主题信息
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
// 后台 # 获取自定义下拉项目
function _get_custom_post_templates_item( $default = '' ) {
    $templates = _get_custom_post_templates();
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



// 保存文章时保存 _post_template
add_action('save_post','_save_post_template_meta', 10, 2);
function _save_post_template_meta($post_id, $post) {
    if ($post->post_type !='page' && !empty($_POST['post_template']))
        update_post_meta($post->ID,'_post_template',$_POST['post_template']);
}

// 过滤替换默认模板
add_filter('single_template','_get_post_template_meta');
function _get_post_template_meta( $template ) {
    global $wp_query;
    $post = $wp_query->get_queried_object();
    if ($post) {
        $post_template = get_post_meta($post->ID,'_post_template',true);
        $template_uri = get_stylesheet_directory() . "/{$post_template}";
        if (!empty($post_template) && $post_template!='default' && file_exists($template_uri)){
            $template = $template_uri;
        }
    }

    return $template;
}
