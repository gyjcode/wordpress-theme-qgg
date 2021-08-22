<?php
/**
 * @name 评论列表模板
 * @description 在引用位置处加载一个评论列表，获取用户评论列表，分级显示加载
 */
?>

<?php
function module_comments_list($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    global $commentcount, $wpdb, $post;
    if(!$commentcount) {                                   //初始化楼层计数器

        $page  = get_query_var('cpage');                   //获取当前评论列表页码
        $cpp   = get_option('comments_per_page');          //获取每页评论显示数量
        $pcs   = get_option('page_comments');              //分页开关
        $page  = $page ? $page : 0;
        $comts = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type = '' AND comment_approved = '1' AND !comment_parent");
        $cnt   = count($comts);                            //获取主评论总数量

        if ( get_option('comment_order') === 'desc' ) {    //倒序
            if (!$pcs || ceil($cnt / $cpp) == 1 || ($page > 1 && $page  == ceil($cnt / $cpp))) {
                $commentcount = $cnt + 1;                  //如果评论只有1页或者是最后一页，初始值为主评论总数
            } else {
                $commentcount = $cpp * $page + 1;
            }
        }else{                                             //顺序
            if( !$pcs ){
                $commentcount = 0;
            }else{
                $page = $page-1;
                $commentcount = $cpp * $page;
            }
        }
    }
    // 生成评论列表
    echo '<li class="'.implode(" ", get_comment_class()).'" id="comment-'.get_comment_ID().'">';
        // 顶级评论添加顺序号
        if(!$parent_id = $comment->comment_parent ) {
            echo '<span class="order">#'. (get_option('comment_order') === 'desc' ? --$commentcount : ++$commentcount) .'</span>';
        }
        // 评论主体内容
        echo '
        <div class="content-wrapper">
            <!-- 头像 -->
            <div class="avatar-wrapper">'._get_avatar($user_id=$comment->user_id, $user_email=$comment->comment_author_email).'</div>
            <!-- 内容 -->
            <div class="content" id="div-comment-'.get_comment_ID().'">';

                comment_text();

                echo '
                <div class="meta">
                    <span class="item author">'.get_comment_author_link().'</span>
                    <span class="item time">'._get_time_ago($comment->comment_date).'</span>
                    <a class="more" href="javascript:;" id="comment-toggle">展开</a>';

                    // 已审核 # 显示回复
                    if ($comment->comment_approved !== '0'){
                        $replyText = get_comment_reply_link(
                            array_merge( $args, array(
                                'add_below' => 'div-comment',    // 回复表单显示在哪个元素下面
                                'depth'     => $depth,
                                'max_depth' => $args['max_depth']
                            ))
                        );
                        // 正则匹配，替换原有字符串
                        if( strstr($replyText, 'reply-login') ){    //  用户必须注册并登录才可以发表评论：将 comment-reply-login 替换为 signin-loader
                            $pattern     = '/class=(\"|\')(.*?)(\"|\') href=(\"|\')(.*?)(\"|\')/';
                            $replacement = 'class="signin-loader" href="javascript:;"';
                        } else {
                            $pattern     = '/href=(\"|\')(.*?)(\"|\')/i';
                            $replacement = 'href="javascript:;"';
                        }
                        echo preg_replace($pattern, $replacement, $replyText);
                    } else {    // 未审核
                        echo '<span class="unapproved">待审核</span>';
                    }
                    
                echo '
                </div>
            </div>
        </div>';
}
