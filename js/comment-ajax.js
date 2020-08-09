define(function (){

return {
	init: function (){
		
		(function($){
			
			$('#comments-list .url').attr('target','_blank')         // 评论列表内链接新窗口打开
			
			// 更改评论用户信息
			$('.comment-user-change').on('click', function(){
				$(this).hide()         // 隐藏"更换"按钮
				$('#comment-author-info').slideDown(300)        // 显示用户信息录入表单,用于修改评论用户信息
			})
			
			// 评论 AJAX
			var edit_mode   = '0',       // 编辑模式（'1' = 开，'0' = 关）
				txt1        = '<div class="comt-tip comt-loading">评论提交中...</div>',         // 评论提交后显示文本信息
				txt2        = '<div class="comt-tip comt-error">#</div>',
				cancel_edit = '取消编辑',
				edit,
				num         = 1,         // 新增评论数量
				comt_array  = [];        // 评论数组
			
			comt_array.push('');         // push() 方法可向数组的末尾添加一个或多个元素,并返回新的长度。
			
			$comments = $('#comments-title');        // 获取评论标题盒子
			
			$cancel_reply = $('#cancel-comment-reply-link');        // 获取取消回复盒子
			$cancel_reply_text = $cancel_reply.text();              // 获取取消回复盒子内文本
			
			$submit = $('#comments-form #submit');                  // 获取评论提交按钮
			$submit.attr('disabled', false);                        // 设置评论提交按钮为启用
			
			$('.comt-tips').append(txt1 + txt2);          // 向 comt-tips 中添加 HTML 文本
			$('.comt-loading').hide();                    // 隐藏加载中盒子
			$('.comt-error').hide();                      // 隐藏错误信息盒子
			
			$body = (window.opera) ? ( document.compatMode == "CSS1Compat" ? $('html') : $('body') ) : $('html,body');         // Opera 浏览器兼容处理
			
			// 新添加评论插入到评论列表中去
			$('#comments-form').submit(function() {
				
				$('.comt-loading').slideDown(300);                         // 显示评论提交中···
				$submit.attr('disabled', true).fadeTo('slow', 0.5);        // 禁用提交按钮
				/* console.log( edit ) */
				if ( edit ){ 
					$('#comment').after('<input type="text" name="edit_id" id="edit_id" value="' + edit + '" style="display:none;" />');
				}
				
				$.ajax({
					url     : jsui.ajax_url,
					data    : $(this).serialize()+ "&action=comment",
					type    : $(this).attr('method'),
					error   : function(request) {
						$('.comt-loading').slideUp(300);            // 隐藏评论提交中···
						$('.comt-error').slideDown(300).html( request.responseText );         // 显示返回的错误信息
						setTimeout(function() {
							$submit.attr('disabled', false).fadeTo('slow', 1);        // 启用提交按钮
							$('.comt-error').slideUp(300)           // 隐藏返回的错误信息
						},3000)
					},
					success : function(data) {
						$('.comt-loading').slideUp(300);                    // 显示评论提交中···
						comt_array.push($('#comment').val());               // 评论信息添加到数组中
						$('textarea').each(function() {                     // 清空评论框信息
							this.value = ''
						});
						
						var t = addComment,
							cancel  = t.I('cancel-comment-reply-link'),
							temp    = t.I('wp-temp-form-div'),
							respond = t.I(t.respondId),
							post    = t.I('comment_post_ID').value,
							parent  = t.I('comment_parent').value;
							
						if (!edit && $comments.length) {
							n = parseInt($comments.text().match(/\d+/));
							$comments.text($comments.text().replace(n, n + 1))
						}
						// 新评论插入评论列表
						new_htm = '" id="new_comm_' + num + '"></';
						new_htm = (parent == '0') ? ('\n<ol style="clear:both;" class="comment-new' + new_htm + 'ol>') : ('\n<ul class="children' + new_htm + 'ul>');
						
						ok_htm = '\n<span id="success_' + num + '>';
						ok_htm += '</span><span></span>\n';

						if (parent == '0') {
							if ( $('#comments-list .content').length ) {    // 评论列表有评论,在旧评论之前插入
								$('#comments-list .content').before(new_htm);
							} else {    // 评论列表没有评论,直接覆盖html
								$('#comments-list').html(new_htm+$('#comments-list').html());
							}
						} else {
							$('#comments-respond').after(new_htm);    // 子评论回复在输入框后插入评论
							/* console.log(parent); */
						}
						
						$('.comment-user-change').show()
						$('#comment-author-info').slideUp()

						if( !$('.comment-user-avatar-name').length ){
							$('.comt-title img').after('<p class="comment-user-avatar-name"></p>')	
						}
						
						$('.comment-user-avatar-name').text( $('#comments-form #author').val() )

						$('#new_comm_' + num).hide().append(data);
						$('#new_comm_' + num + ' li').append(ok_htm);
						$('#new_comm_' + num).fadeIn(1000);
						$('#new_comm_' + num).find('.comt-avatar .avatar').attr('src', $('.comment-new .avatar:last').attr('src'));
						countdown();        // 延时提交15秒，启用提交按钮
						num++;
						edit = '';
						$('*').remove('#edit_id');
						cancel.style.display = 'none';
						cancel.onclick = null;
						t.I('comment_parent').value = '0';
						if (temp && respond) {
							temp.parentNode.insertBefore(respond, temp);
							temp.parentNode.removeChild(temp)
						}
					}
				});
				return false
			});
			
			addComment = {
				moveForm: function(comtId, parentId, respondId, postId, num) {
					var t = this,
						div, 
						comt    = t.I(comtId),
						respond = t.I(respondId),
						cancel  = t.I('cancel-comment-reply-link'),
						parent  = t.I('comment_parent'),
						post    = t.I('comment_post_ID');
						
					if (edit) exit_prev_edit();
					
					num ? (t.I('comment').value = comt_array[num], edit = t.I('new_comm_' + num).innerHTML.match(/(comment-)(\d+)/)[2], $new_sucs = $('#success_' + num), $new_sucs.hide(), $new_comm = $('#new_comm_' + num), $new_comm.hide(), $cancel_reply.text(cancel_edit)) : $cancel_reply.text( $cancel_reply_text );
					
					t.respondId = respondId;
					
					postId = postId || false;
					
					if (!t.I('wp-temp-form-div')) {
						div = document.createElement('div');
						div.id = 'wp-temp-form-div';
						div.style.display = 'none';
						respond.parentNode.insertBefore(div, respond)
					}
					!comt ? (temp = t.I('wp-temp-form-div'), t.I('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comt.parentNode.insertBefore(respond, comt.nextSibling);
					
					$body.animate({
						scrollTop: $('#comments-respond').offset().top - 180
					},400);
					
					if (post && postId) post.value = postId;
					parent.value = parentId;
					cancel.style.display = '';
					cancel.onclick = function() {
						
						if (edit) exit_prev_edit();
						
						var t = addComment,
							temp = t.I('wp-temp-form-div'),
							respond = t.I(t.respondId);
						t.I('comment_parent').value = '0';
						if (temp && respond) {
							temp.parentNode.insertBefore(respond, temp);
							temp.parentNode.removeChild(temp)
						}
						this.style.display = 'none';
						this.onclick = null;
						return false
					};
					try {
						t.I('comment').focus()
					} catch (e) {}
					return false
				},
				I: function(e) {
					return document.getElementById(e)
				}
			};
			
			
			$('.comment-reply-link').on('click', function (){
				var that = $(this)
				if( !that.attr('onclick') && that.data('belowelement') && that.data('commentid') && that.data('respondelement') && that.data('postid') ){
					return addComment.moveForm( that.data('belowelement'), that.data('commentid'), that.data('respondelement'), that.data('postid') )
				}
			})
			
			
			function exit_prev_edit() {
				$new_comm.show();
				$new_sucs.show();
				$('textarea').each(function() {
					this.value = ''
				});
				edit = ''
			}
			
			// 延时提交，指定 wait 时间后启用提交按钮，防止误点击连续提交
			var wait = 15,
				submit_val = $submit.val();
			function countdown() {
				if (wait > 0) {
					$submit.val(wait);
					wait--;
					setTimeout(countdown, 1000)
				} else {
					$submit.val(submit_val).attr('disabled', false).fadeTo('slow', 1);
					wait = 15
				}
			}
	
		})(jQuery)
		
		// 评论相关 js 代码
		// 评论表情弹窗出选择
		$(".comment-emojis").click(function(){ 
			$(".emojis-box").toggle(500);
			if( $(".comment-emojis i").css("color") == "rgb(153, 153, 153)" ){
				$(".comment-emojis i").attr('style', 'color: #3cb4f0');
			}else{
				$(".comment-emojis i").attr('style', 'color:;');
			}
		});
		
		// 评论显示展开收起
		// 关闭未超出行数按钮
		var comt_num = $(".comt-main").length;
		var comt_meta_height = $(".comt-main .comt-meta").height();
		var comt_padding_top = parseFloat( $(".comt-main").css("padding-top") );
		var comt_padding_bot = parseFloat( $(".comt-main").css("padding-bottom") );
		var line_height = parseFloat( $(".comt-main").children("p").css("line-height") );
		
		for (var i=0; i<comt_num; i++){
			
			var current_height = $(".comt-main")[i].offsetHeight - comt_padding_bot- comt_padding_top - comt_meta_height;
			var row_num = current_height/line_height;
			
			if( row_num >= 3 ){
				$(".comt-main .comt-more")[i].style.display = "inline-block";
			}else{
				$(".comt-main .comt-more")[i].style.display = "none";
			}
		}
		// 点击展开收起
		$("#comments-list .comt-more").click(function(){
			
			if( $(this).text() == "展开" ){
				$(this).parent().parent().children("p").css({
					"-webkit-line-clamp" : "999999999",
				});
				$(this).text("收起")
			}else{
				$(this).parent().parent().children("p").css({
					"-webkit-line-clamp" : "3",
				});
				$(this).text("展开")
			}
			
		});
		
	},
	
}

});