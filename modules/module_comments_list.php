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
	echo '<li '; comment_class(); echo ' id="comment-'.get_comment_ID().'">';

		if(!$parent_id = $comment->comment_parent ) {
			echo '<span class="comt-order">#'. (get_option('comment_order') === 'desc' ? --$commentcount : ++$commentcount) .'</span>';
		}
		echo '<div class="comt-box">';
			
			echo '<div class="comt-avatar">';
				echo _get_the_avatar($user_id=$comment->user_id, $user_email=$comment->comment_author_email);
			echo '</div>';
			
			echo '<div class="comt-main" id="div-comment-'.get_comment_ID().'">';
				
				comment_text();
			
				if ($comment->comment_approved == '0'){ echo '<span class="comt-approved">待审核</span>'; }

				echo '<div class="comt-meta">';
					echo '<span class="comt-author">'.get_comment_author_link().'</span>';
					echo _get_time_ago($comment->comment_date); 
					if ($comment->comment_approved !== '0'){
						$replyText = get_comment_reply_link( array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
						if( strstr($replyText, 'reply-login') ){
						  echo preg_replace('# class="[\s\S]*?" href="[\s\S]*?"#', ' class="signin-loader" href="javascript:;"', $replyText );
						}else{
						  echo preg_replace('# href=[\s\S]*? onclick=#', ' href="javascript:;" onclick=', $replyText );
						}
					}
					echo '<a class="comt-more" style="display:none;" href="javascript:;">展开</a>';
					
				echo '</div>';
				
			echo '</div>';
			
		echo '</div>';
}
