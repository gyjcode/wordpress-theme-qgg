<?php
/** 
  * @ name 文章新增相关 Meta
  * @description 创建 Meta 时请参照如下方式
  * $box_conf = array(
  *     'box_id'    => "box_id",
  *     'box_title' => "box_title",
  *     'ipt_id'    => "ipt_id",
  *     'ipt_name'  => "ipt_name",
  *     'div_class' => "div_class",
  * );
  * $meta_conf = array(
  *     array(
  *         'type'   => 'text',
  *         'name'   => "Meta_name",    // 对应数据库中meta_key
  *         'title'  => 'Meta_title：'
  *         'value'  => "", 
  *     ),
  * );
  * $new_post_meta = new _CreatePostMeta($box_conf,$meta_conf);
  */

class _CreatePostMeta {
    
    var $box_conf, $meta_conf, $post_id;
    
    function __construct( $box_conf,$meta_conf) {
        $this -> box_conf   = $box_conf;
        $this -> meta_conf  = $meta_conf;
        
        add_action('admin_menu', array($this, '_register_post_meta_box'));
        add_action('save_post', array($this, '_save_post_meta_box'));
    }
    
    // 注册 MetaBox
    public function _register_post_meta_box(){
        if ( function_exists('add_meta_box') ){
            add_meta_box(
                $this -> box_conf['box_id'],
                $this -> box_conf['box_title'],
                array(&$this, '_add_meta_box_init'),
                'post', 'normal', 'high'
            );
        }
    }

    // Meta Box 初始化
    public function _add_meta_box_init( $post_id ){
        $post_id = $_GET['post'];
        $class = $this -> box_conf['div_class'] ?: '';
        
        foreach($this->meta_conf as $meta_box){
            $type              = $meta_box['type'] ?: 'text';
            $name              = $meta_box['name'] ?: '';
            $title             = $meta_box['title'] ?: '未定义';
            $attr_placeholder  = $meta_box['placeholder'] ? 'placeholder="'.$meta_box['placeholder'].'"' : '';
            $attr_pattern      = $meta_box['pattern'] ? 'pattern="'.$meta_box['pattern'].'"' : '';
            $attr_step         = $meta_box['step'] ? 'step="'.$meta_box['step'].'"' : '';
            $attr_max          = $meta_box['max'] ? 'max="'.$meta_box['max'].'"' : '';
            $attr_min          = $meta_box['min'] ? 'min="'.$meta_box['min'].'"' : '';


            $value = get_post_meta($post_id, $meta_box['name'], true) ?: $meta_box['value'];

            if( $name != '' ){
                echo '
                <div class= '.$class.'>
                    <p>
                        <lable>'.$title.'</lable>：
                        <input type="'.$type.'" name="'.$name.'" value="'.$value.'" '.$attr_placeholder .' '.$attr_pattern.' '.$attr_step.' '.$attr_max.' '.$attr_min.'>
                    </p>
                </div>';
            }
        }
        echo '<input type="hidden" name="'.$this -> box_conf['ipt_name'].'" id="'.$this -> box_conf['ipt_id'].'" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
    }
    
    // 存储|更新 Meta Box 数据
    public function _save_post_meta_box( $post_id ){
        $post_id = $_POST['post_ID'];
        
        if ( !wp_verify_nonce( isset($_POST[ $this -> box_conf['ipt_name'] ]) ? $_POST[ $this -> box_conf['ipt_name'] ] : '', plugin_basename(__FILE__) ))
            return;
        if ( !current_user_can( 'edit_posts', $post_id ))
            return;
            
        foreach($this->meta_conf as $meta_box) {
            
            $data = $_POST[$meta_box['name']] ? $_POST[$meta_box['name']] : "";

            if(get_post_meta($post_id, $meta_box['name']) == ""){
                add_post_meta($post_id, $meta_box['name'], $data, true);
            }elseif($data != get_post_meta($post_id, $meta_box['name'], true)){
                update_post_meta($post_id, $meta_box['name'], $data);
            }elseif($data == ""){
                delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
            }
        }
    }
}
