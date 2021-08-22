<?php
function _edit_user_avatar_profile($user){
    $avatar    = get_user_meta($user->ID, 'avatar', true);
    $user_cap = current_user_can( 'upload_files' ) ? 'YES' : 'NO';
    $avatar_default    = $avatar ? $avatar : get_template_directory_uri().'/img/avatar-default.png';
?>
    <h2>用户头像</h2>
    <table class="form-table" cellspacing="0">
        <tbody>
            <tr id="user_avatar" class="user-avatar" valign="center">
                <th scope="row"><label for="avatar">自定义头像</label></th>
                <td class="user-avatar-img">
                    <img id="user-avatar-new" src="<?php echo $avatar_default; ?>" alt="本地头像"/>
                </td>
                <td class="user-avatar-wrap">
                    <input type="hidden" name="avatar" id="avatar" class="regular-text" value="<?php echo $avatar; ?>">
                    <?php if ( current_user_can( 'upload_files' ) ) { ?>
                    <!-- 有 upload_files 权限媒体库选择图片文件 -->
                    <span class="desc">在媒体库中选择一个图片作为头像：</span>
                    <div class="user-avatar-btn wp-media-buttons">
                        <div data-user_cap="<?php echo $user_cap; ?>" data-item_type="url" class="media-library-upload button add_media">添加图片</div>
                    </div>
                    <?php } ?>
                    <!-- 无 upload_files 权限计算机上传图片文件 -->
                    <span class="desc">从计算机中上传一张图片作为头像：</span><br />
                    <div class="user-avatar-btn wp-media-buttons">
                        <input type="file" name="local-avatar-upload" id="local-avatar-upload" class="local-avatar-upload" />
                        <?php wp_nonce_field( 'local-avatar-upload', 'local-avatar-upload_nonce' ); ?>
                    </div>        
                </td>
            </tr>
        </tbody>
    </table>
<?php
}
add_action('show_user_profile', '_edit_user_avatar_profile', 1);
add_action('edit_user_profile', '_edit_user_avatar_profile', 1);

// 媒体库选择文件更新 avatar 值(用户有 upload_files 权限)
function _edit_user_avatar_profile_update($user_id){
    if(current_user_can('edit_users') || get_current_user_id() == $user_id){
        $avatar    = $_POST['avatar'] ? : '';
        if($avatar){
            update_user_meta($user_id, 'avatar', $avatar);
        }else{
            delete_user_meta($user_id, 'avatar');
        }
    }
}
add_action('personal_options_update','_edit_user_avatar_profile_update');
add_action('edit_user_profile_update','_edit_user_avatar_profile_update');

// 用户上传文件更新 avatar (用户无 upload_files 权限)
function _upload_user_avatar_profile_update($user_id){
    $user_id = get_current_user_id();
    // check nonces
    if( empty( $_POST['local-avatar-upload_nonce'] ) || ! wp_verify_nonce( $_POST['local-avatar-upload_nonce'], 'local-avatar-upload' ) ){
        return;
    }
    // 判断 media_handle_upload() 是否存在
    if ( ! function_exists( 'media_handle_upload' ) ){
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
    }
    
    // 自定义头像上传路径（ Upload 文件夹下的路径 ）
    function avatar_upload_dir( $dir ) {
        $sub_dir = get_currentuserinfo()->ID;
        return array(
            'path'   => $dir['basedir'] . '/avatars/' . $sub_dir,
            'url'    => $dir['baseurl'] . '/avatars/' . $sub_dir,
            'subdir' => '/avatars/' . $sub_dir,
        ) + $dir;
    }
    
    add_filter( 'upload_dir', 'avatar_upload_dir');
    
    $avatar_id = media_handle_upload( 'local-avatar-upload', 0, array(), array(
        'mimes'     => array(
            'jpg|jpeg|jpe'    => 'image/jpeg',
            'gif'            => 'image/gif',
            'png'            => 'image/png',
        ),
        'test_form'    => false
    ));
    
    remove_filter( 'upload_dir', 'avatar_upload_dir' );
    
    if(!is_numeric($avatar_id)){ return; }    // 未上传文件的话直接返回
    if ( is_wp_error( $avatar_id ) ) { //
        // 图片上传错误处理信息
        function user_avatar_upload_errors( WP_Error $errors ) {
            $error_message = function_exists('get_error_message') ? $avatar_id->get_error_message() : "未知错误，请仔细检查文件！";
            $avatar_upload_error = '<strong>' . __( 'Avatar 头像上传错误:'.gettype($avatar_id) , 'local-avatar-upload' ) . '</strong> ' . esc_html( $error_message );
            $errors->add( 'avatar_error', $avatar_upload_error );
        }
        add_action( 'user_profile_update_errors', 'user_avatar_upload_errors' );
        
        return;
    }else{
        $avatar_url = wp_get_attachment_image_src( $avatar_id, 'full')[0];
        update_user_meta($user_id, 'avatar', $avatar_url);
    }
}
add_action( 'personal_options_update', '_upload_user_avatar_profile_update' );
add_action( 'edit_user_profile_update', '_upload_user_avatar_profile_update' );

// 确保配置文件表单具有正确的编码类型, 一般默认的 enctype="application/x-www-form-urlencoded";不能用于上传文件
function user_edit_form_tag() {
    echo 'enctype="multipart/form-data"';
}
add_action( 'user_edit_form_tag', 'user_edit_form_tag' );

// 用户有上传权限调用 wp.media 库
function admin_enqueue_scripts(){
    if ( current_user_can( 'upload_files' ) ){
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'admin_enqueue_scripts');

add_action('admin_footer', function(){
    ?>
    <style type="text/css">
    .form-table .user-avatar-img{
        display: inline-block;
        width: 120px;
        height: 120px;
        margin: 0;
        padding: 0;
        border: 1px solid #999;
        border-radius: 9px;
        overflow: hidden;
    }
    .form-table .user-avatar-img img{
        width: 100%;
        height: 100%;
    }
    .form-table .user-avatar-wrap {
        display: inline-block;
        width: auto;
        height: 110px;
        margin: 0;
        padding: 5px 20px;
        border: 0;
    }
    .form-table .user-avatar-wrap .user-avatar-btn{
        width: 80px;
        margin: 5px 0;
        padding: 0;
        border: 0;
        font-size: 14px;
    }
    </style>
    
    <script>
    (function($){
        // 有 upload_files 权限媒体库选择图片文件
        $('body').on('click', '.media-library-upload', function(e) {
            // 阻止事件默认行为
            e.preventDefault();    
            
            var user_cap    = $(this).data('user_cap');
            if (user_cap=="NO"){alert("抱歉，你没有上传图片的权限！")}
            var item_type    = $(this).data('item_type');
            var input_tag    = $('#avatar');
            var img_tag     = $('#user-avatar-new');
            
            //唤起 WordPress 默认媒体上传
            custom_uploader = wp.media.frames.local_avatar_frame = wp.media({
                title:        '选择图片',
                library:    { type: 'image' },
                button:        { text: '选择图片' },
                multiple:    false 
            });
            
            wp.media.frames.local_avatar_frame.on('select', function() {
                var attachment = wp.media.frames.local_avatar_frame.state().get('selection').first().toJSON();
                var img_value  = (item_type == 'url') ? attachment.url : attachment.id;
                
                // 将图像路径赋值给 input 的 value
                input_tag.val(img_value);
                img_tag.attr('src', img_value);
                
            });
            
            wp.media.frames.local_avatar_frame.open();
            
        });
        
        // 无 upload_files 权限本地上传图片文件
        $('body').on('change', '.local-avatar-upload', function(e) {
            var file = this.files[0];
            // 限制文件类型
            var fileType = /^image\//;
            if ( !fileType.test(file.type) ) {
                alert("请选择一张图片！");
                $(this).val('')
                return;
            }
            //限制文件大小
            var imgSize = file.size;
            if(imgSize> 200*1024){
                alert('上传的图片不得大于200KB！');
                $(this).val('')
                return false;
            }
            // 预览图片
            filePath = URL.createObjectURL(file);
            $('#user-avatar-new').attr('src', filePath);
        });
    })(jQuery)
        
    </script>
    <?php
});