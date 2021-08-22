<?php
/**
 * 评论 AJAX
 */

if(!function_exists('get_ajax_comment_callback') || !function_exists('get_ajax_comment_err')) {

    if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
        wp_die('请升级到4.4以上版本');
    }
    
    function get_ajax_comment_err($a) {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: text/plain;charset=UTF-8');
        echo $a;
        exit;
    }

    function get_ajax_comment_callback(){
        $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
        if ( is_wp_error( $comment ) ) {
            $data = $comment->get_error_data();
            if ( ! empty( $data ) ) {
                get_ajax_comment_err($comment->get_error_message());
            } else {
                exit;
            }
        }
        $user = wp_get_current_user();
        do_action('set_comment_cookies', $comment, $user);
        $GLOBALS['comment'] = $comment; //根据你的评论结构自行修改，如使用默认主题则无需修改
        ?>
        <li <?php comment_class(); ?>  id="comment-<?php echo get_comment_ID(); ?>">
        
            <div class="content-wrapper">
                <div class="avatar-wrapper">
                    <?php echo _get_avatar($user_id=$comment->user_id, $user_email=$comment->comment_author_email, $src=true);?>
                </div>
                <div class="content" id="div-comment-'<?php echo get_comment_ID(); ?>">
                    <?php comment_text(); ?>
                    <div class="meta">
                        <span class="item author"><?php echo get_comment_author_link();?></span>
                        <span class="item time"><?php echo _get_time_ago($comment->comment_date); ?></span>
                    </div>
                    <?php
                    if ($comment->comment_approved == '0'){
                        echo '<span class="approved">待审核</span>';
                    }
                    ?>
                </div>
            </div>
        </li>
        <?php die();
    }
    
    add_action('wp_ajax_nopriv_comment', 'get_ajax_comment_callback');
    add_action('wp_ajax_comment', 'get_ajax_comment_callback');    
}
