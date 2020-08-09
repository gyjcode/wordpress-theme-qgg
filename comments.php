<?php
defined('ABSPATH') or die('This file can not be loaded directly.');

$my_email = get_bloginfo ( 'admin_email' );
$str = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_approved = '1' AND comment_type = '' AND comment_author_email";
$comt_count = $post->comment_count;
date_default_timezone_set('PRC');
$closeTimer = (strtotime(date('Y-m-d G:i:s'))-strtotime(get_the_time('Y-m-d G:i:s')))/86400;
?>
<!-- 广告代码 -->
<?php _the_ads($name='ads_post_cmnt_01', $class='ads-post-cmnt-01') ?>
<!-- 加载评论区域 -->
<div id="comments-area" class="clearfix">
	
	<!-- 加载评论标题 -->
	<div id="comments-title" class="title">
		<h3>
			<?php echo QGG_Options('comment_title') ? QGG_Options('comment_title') : '评论'; ?> 
			<?php echo $comt_count ? '<b>('.$comt_count.')</b>' : '<small>抢沙发</small>'; ?>
		</h3>
	</div>
	
	<!-- 加载评论回复框 -->
	<div id="comments-respond" class="clearfix">
		<?php if ( QGG_Options('site_comment_closed_open') || get_option('default_comment_status') == 'closed' ) { ?>
			<!-- 禁止他人在新文章上发表评论 -->
			<div class="comments-closed">
				<h3 class="site-comments-closed">
					<strong>抱歉，整站评论功能已关闭！</strong>
				</h3>
			</div>
		<?php }elseif( get_option('close_comments_for_old_posts') && $closeTimer > get_option('close_comments_days_old') ) { ?>
			<!-- 自动关闭过期评论 -->
			<div class="comments-closed">
				<h3 class="post-comments-closed">
					<strong>抱歉，当前文章评论已关闭！</strong>
				</h3>
			</div>
		<?php }elseif ( get_option('comment_registration') && !is_user_logged_in() ) { ?>
			<!-- 用户必须注册并登录才可以发表评论  -->
			<div class="comments-sign">
				<h3 class="tips">评论前必须登录</h3>
				<p>
					<a rel="nofollow" href="javascript:;" class="btn btn-default signin-loader">立即登录</a> &nbsp; 
					<a rel="nofollow" href="javascript:;" class="btn btn-primary signup-loader">我要注册</a>
				</p>
			</div>
		<?php }else{ ?>
			<!-- 评论作者必须填入姓名和电子邮件地址  -->
			<form id="comments-form" class="clearfix" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">
				<div class="content">
					<!-- 评论表单左侧头像标题 -->
					<div class="comt-title" id="comt-title">
						<?php 
						if ( is_user_logged_in() ) {
							global $current_user;
							echo _get_the_avatar($user_id=get_current_user_id(), $user_email=$current_user->user_email);
							echo '<p>'.$user_identity.'</p>';
						}else{
							if( $comment_author_email!=='' ) {
								echo _get_the_avatar($user_id='', $user_email=$comment->comment_author_email);
							}
							else{
								echo _get_the_avatar($user_id='', $user_email='');
							}
							if ( !empty($comment_author) ){
								echo '<p class="comment-user-avatar-name">'.$comment_author.'</p>';
								echo '<p class="comment-user-change"><a rel="nofollow" href="javascript:;">更换</a></p>';
							}
						}
						?>
						<!-- 取消编辑回复给别人的评论 -->
						<p><a rel="nofollow" id="cancel-comment-reply-link" href="javascript:;">取消</a></p>
					</div>
					
					<!-- 评论表单右侧评论框 -->
					<div class="comt-box">
						<!--评论文本抗-->
						<textarea id="comment" name="comment" class="comt-area" placeholder="<?php echo QGG_Options('comment_placeholder_text') ?>" style="background-image: url(<?php echo QGG_Options('comment_background_img')?>);" cols="100%" rows="5" tabindex="1" onkeydown="if(event.ctrlKey&amp;&amp;event.keyCode==13){ document.getElementById('submit').click();return false };"></textarea>
						<!--评论工具栏-->
						<div class="comt-ctrl">
							<?php
							// 评论表情开启
							if(QGG_options('site_comment_emoji_open')){
								echo '<span class="comment-emojis" title="表情" data-type="comment-insert-smilie">
									<i class="iconfont qgg-smile" style="color: #999;"></i>
								</span>';
							}
							?>
							<!--评论提示信息-->
							<div class="comt-tips">
								<?php
								comment_id_fields(); 
								do_action('comment_form', $post->ID); 
								?>
							</div>
							<!--评论提交按钮-->
							<button class="btn btn-default" type="submit" name="submit" id="submit" tabindex="5"><?php echo QGG_Options('comment_submit_text') ? QGG_Options('comment_submit_text') : '提交评论' ?></button>
						</div>
					</div>
					
					<?php
					// 评论表情开启
					if(QGG_options('site_comment_emoji_open')){
						include get_template_directory().'/action/get_emojis.php';
					}
					?>
					
					<?php
					$placeholder = "";
					if(QGG_options('site_comment_getqqinfo_open')){
						echo '<script src="'.get_template_directory_uri().'/js/get-qq-info.js" type="text/javascript" charset="utf-8"></script>';
						$placeholder = "输入QQ号快速填写";
					}
					?>
					<!-- 未登录用户加载评论者信息输入框 -->
					<?php if ( !is_user_logged_in() ) { ?>
					<?php if( get_option('require_name_email') ){ ?>
						<div id="comment-author-info" class="comter-info" <?php if ( !empty($comment_author) ) echo 'style="display:none"'; ?>>
							<ul>
								<li class="form-inline">
									<label class="hide" for="author">昵称</label>
									<input type="text" name="author" id="comt-author" value="<?php echo esc_attr($comment_author); ?>" tabindex="2" placeholder="<?php echo $placeholder; ?>">
									<span>昵称<small>(必填)</small></span>
								</li>
								<li class="form-inline">
									<label class="hide" for="email">邮箱</label>
									<input type="text" name="email" id="comt-email" value="<?php echo esc_attr($comment_author_email); ?>" tabindex="3" placeholder="<?php echo $placeholder; ?>">
									<span>邮箱<small>(必填)</small></span>
								</li>
								<li class="form-inline">
									<label class="hide" for="url">ＱＱ</label>
									<input type="text" name="qq" id="comt-qq" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="4" placeholder="<?php echo $placeholder; ?>" onblur="get_qq_info()">
									<span>ＱＱ<small>(选填)</small></span>
								</li>
								<li class="form-inline">
									<label class="hide" for="url">网址</label>
									<input type="text" name="url" id="comt-url" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="5" placeholder="">
									<span>网址<small>(选填)</small></span>
								</li>
							</ul>
						</div>
					<?php } ?>
					<?php } ?>
					
				</div>
				
			</form>
		<?php } ?>
	</div>
	
	<div id="comments-list" class="comments-list">
		<!-- 加载评论列表 -->
		<?php if ( have_comments() ) { ?>
		<ol class="content">
			<?php 
			the_module_loader('module_comments_list', false);
			wp_list_comments( 
				array(
					'respond_id' => "comments-respond",
					'type'        => 'comment',
					'callback'  => "module_comments_list",
				)
			);
			?>
		</ol>
		<div class="page-nav">
			<?php paginate_comments_links('prev_next=0');?>
		</div>
		<?php } ?>
	</div>
</div>