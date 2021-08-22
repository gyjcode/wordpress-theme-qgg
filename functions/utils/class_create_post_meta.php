<?php
/** 
  * @ name 文章新增相关 Meta
  * @description 创建 Meta 时请参照如下方式
  *        $meta_conf = array(
  *            'box_id'    => "box_id",
  *            'box_title' => "box_title",
  *            'ipt_id'    => "ipt_id",
  *            'ipt_name'  => "ipt_name",
  *            'div_class' => "div_class",
  *        );
  *        $my_meta = array(
  *            array(
  *                'name'   => "Meta_name",                             // 对应数据库中meta_key
  *                'std'    => "", 
  *                'title'  => 'Meta_title：'
  *            ),
  *        );
  *        $new_post_meta = new CreateMyMetaBox($meta_conf,$my_meta);
  */

class CreateMyMetaBox{
    
    var $meta_conf, $my_meta, $post_id;
    
    function __construct( $meta_conf,$my_meta) {
        $this -> meta_conf   = $meta_conf;
        $this -> my_meta     = $my_meta;
        
        add_action('admin_menu', array(&$this, 'my_meta_box_create'));
        add_action('save_post', array(&$this, 'my_meta_box_save'));
    }
    
    public function my_meta_box_create(){
        if ( function_exists('add_meta_box') ){
            add_meta_box( $this -> meta_conf['box_id'], __($this -> meta_conf['box_title'], 'QGG'), array(&$this, 'my_meta_box_init'), 'post', 'normal', 'high' );
        }
    }
    
    public function my_meta_box_init( $post_id ){
        
        $class = $this -> meta_conf['div_class'] ? $this -> meta_conf['div_class'] : '';
        $post_id = $_GET['post'];
        
        foreach($this->my_meta as $meta_box){
            $meta_box_value = get_post_meta($post_id, $meta_box['name'], true);
            if($meta_box_value == ""){
                $meta_box_value = $meta_box['std'];
            }
            if( isset($meta_box['title']) ){
                echo'<div class= '.$class.'>
                    <p>
                    <lable>'.$meta_box['title'].'</lable>：
                    <input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'">
                    </p>
                </div>';
            }
        }
        echo '<input type="hidden" name="'.$this -> meta_conf['ipt_name'].'" id="'.$this -> meta_conf['ipt_id'].'" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
    }
    
    public function my_meta_box_save( $post_id ){
        
        $post_id = $_POST['post_ID'];
        
        if ( !wp_verify_nonce( isset($_POST[ $this -> meta_conf['ipt_name'] ]) ? $_POST[ $this -> meta_conf['ipt_name'] ] : '', plugin_basename(__FILE__) ))
            return;
        if ( !current_user_can( 'edit_posts', $post_id ))
            return;
            
        foreach($this->my_meta as $meta_box) {
            
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