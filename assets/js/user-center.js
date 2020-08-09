define(['router', 'jsrender'], function (){
	// JSONP
	(function($) {
		$.ajaxTransport('jsonpi', function (opts, originalOptions, jqXHR){
			var jsonpCallback = opts.jsonpCallback = jQuery.isFunction(opts.jsonpCallback) ? opts.jsonpCallback() : opts.jsonpCallback,
				previous = window[jsonpCallback],
				replace = "$1" + jsonpCallback + "$2",
				url = opts.url;
			
			if (opts.type == 'GET'){
				opts.params[opts.jsonp] = jsonpCallback;
			}else{
				url += (/\?/.test( url ) ? "&" : "?") + opts.jsonp + "=" + jsonpCallback;
			}
			
			return {
				send: function(_, completeCallback) {
					var name = 'jQuery_iframe_' + jQuery.now(),
						iframe, form;
						
					// Install callback
					window[jsonpCallback] = function(data) {
						// TODO: How to handle errors? Only 200 for now
						completeCallback(200, 'success', {
							'jsonpi': data
						});
						iframe.remove();
						form.remove();
						window[jsonpCallback] = previous;
					};
					
					iframe = $('<iframe name="'+name+'">') //ie7 bug fix
						//.attr('name', name)
						.appendTo('head');

					form = $('<form>')
						.attr('method', opts.type) // GET or POST
						.attr('action', url)
						.attr('target', name);
					
					$.each(opts.params, function(k, v) {
						$('<input>')
							.attr('type', 'hidden')
							.attr('name', k)
							.attr('value', v)
							.appendTo(form);
					});
					form.appendTo('body').submit();
				},
				abort: function() {
					// TODO
				}
		   };
		});
	})(jQuery);
	
	return {
		init: function (){
			
			(function($){
			
			var _iframe = $('#content-frame'),
				_main = $('.user-main'),
				_homepage = 'comments',
				cache_postmenu = null,
				cache_userdata = null,
				cache_orderdata = null,
				cache_coupondata = null,

				rp_post = /^#post\//,
				rp_comment = /^#comment/,
				rp_like = /^#like/,
				ajax_url = jsui.uri+'/action/user_center.php',
				
				_msg = {
					// 1-2位：类型；3-4位：01-69指客户端操作提示，71-99指服务端操作提示
					1101: '该栏目下暂无数据！',
					1079: '服务器异常，请稍候再试！',
					1201: '暂无文章！',
					1301: '暂无评论！'
				}
				
			function is_comment(){
				return rp_comment.test(location.hash) ? true : false
			}
			
			var routes = {
				// 全部
				'posts/all': function(){
					get_post_data('all', 1)
					$('.user-post-menu a:eq(0)').addClass('active')
				},
				'posts/all/:paged': function(paged){
					get_post_data('all', paged)
					$('.user-post-menu a:eq(0)').addClass('active')
				},
				//已发布
				'posts/publish': function(){
					get_post_data('publish', 1)
					$('.user-post-menu a:eq(1)').addClass('active')
				},
				'posts/publish/:paged': function(paged){
					get_post_data('publish', paged)
					$('.user-post-menu a:eq(1)').addClass('active')
				},
				// 定时
				'posts/future': function(){
					get_post_data('future', 1)
					$('.user-post-menu a:eq(2)').addClass('active')
				},
				'posts/future/:paged': function(paged){
					get_post_data('future', paged)
					$('.user-post-menu a:eq(2)').addClass('active')
				},
				// 待审
				'posts/pending': function(){
					get_post_data('pending', 1)
					$('.user-post-menu a:eq(3)').addClass('active')
				},
				'posts/pending/:paged': function(paged){
					get_post_data('pending', paged)
					$('.user-post-menu a:eq(3)').addClass('active')
				},
				// 草稿
				'posts/draft': function(){
					get_post_data('draft', 1)
					$('.user-post-menu a:eq(4)').addClass('active')
				},
				'posts/draft/:paged': function(paged){
					get_post_data('draft', paged)
					$('.user-post-menu a:eq(4)').addClass('active')
				},
				// 回收站
				'posts/trash': function(){
					get_post_data('trash', 1)
					$('.user-post-menu a:eq(5)').addClass('active')
				},
				'posts/trash/:paged': function(paged){
					get_post_data('trash', paged)
					$('.user-post-menu a:eq(5)').addClass('active')
				},
				// 评论
				'comments': function(){
					get_comment_data(1)
				},
				'comments/:paged': function(paged){
					get_comment_data(paged)
				},
				// 修改用户资料相关代码
				'info': function(){
					menuActive('info')
					loading( _main )
					
					if( !cache_userdata ){
						$.ajax({
							url: ajax_url,
							type: 'POST',
							dataType: 'json',
							data: {
								action: 'info'
							},
							success: function(data, textStatus, xhr) {
								if( data.user ){
									cache_userdata = data.user
									_main.html(
										$('#temp-info').render( data.user )
									)
								}else{
									loading(_main, _msg['1101'])
								}
							},
							error: function(xhr, textStatus, errorThrown) {
								loading(_main, _msg['1079'])
							}
						});
					}else{
						_main.html(
							$('#temp-info').render( cache_userdata )
						)
					}
				},
				// 修改用户密码相关代码
				'password': function(){
					menuActive('password')
					_main.html(
						$('#temp-password').render()
					)
				},
				
				// 我的文章：获取用户最新文章
				'publish': function(){
					menuActive('publish')
					_main.html(
						$('#temp-publish').render()
					)
					$('.user-main').hide()
					$('.user-main-publish').show()
				},
				/** 集成Erphpdown 开始 */
				// 我的资产
				'property': function(){
					menuActive('property')
					_main.html(
						$('#temp-property').render()
					)
					$('.user-main').hide()
					$('.user-main-property').show()				
				},
				// 申请提现
				'application': function(){
					menuActive('application')
					_main.html(
						$('#temp-application').render()
					)
				},
				// 我的推广
				'tuiguang': function(){
					menuActive('tuiguang')
					_main.html(
						$('#temp-tuiguang').render()
					)
				},
				// 会员服务
				'vipservice': function(){
					menuActive('vipservice')
					_main.html(
						$('#temp-vipservice').render()
					)
				},
				/** 集成 Erphpdown 结束 */
			}
			
			var router = Router(routes);
			router.configure({
				on: function(){
					if( location.hash.indexOf('posts/')<=0 ){
						$('.user-post-menu').remove()
					}
				},
				before: function(){
					$('.user-main').show()
					$('.user-main-publish').hide()
					/** 集成 Erphpdown */
					$('.user-main-property').hide()
				},
				notfound: function(){
					location.hash = _homepage
				}
			})
			router.init();
			
			if( !location.hash ) location.hash = _homepage
			
			// 函数部分
			// 文章数据获取函数
			function get_post_data(status, paged, callback){
				
				menuActive('posts')
				$('.user-post-menu a').removeClass()
				loading( _main )
				
				var datas = {
					action: 'posts',
					status: status,
					paged: paged
				}
				
				if( !cache_postmenu ){ datas.first = true }
				
				$.ajax({
					url: ajax_url,
					type: 'POST',
					dataType: 'json',
					data: datas,
					success: function(data, textStatus, xhr) {
						
						if( !cache_postmenu && data.menus ){
							cache_postmenu = data.menus
						}
						
						if( (cache_postmenu || (!cache_postmenu && data.menus)) && !$('.user-post-menu').length ){
							_main.before( '<div class="user-post-menu"></div>' )
							$('.user-post-menu').html(
								$('#temp-post-menu').render( cache_postmenu || data.menus )
							)
						}
						
						if( data.items ){
							_main.html('<ul class="user-post-list"></ul>')
							$('.user-post-list').html(
								$('#temp-post-item').render( data.items )
							).after( paging(data.max, paged, '#posts/'+status+'/') )
							
							thumb_lazyload()
						}else{
							loading(_main, _msg['1201'])
						}
						callback && callback()
					},
					error: function(xhr, textStatus, errorThrown) {
						loading(_main, _msg['1079'])
					}
				});
			}
			// 评论数据获取函数
			function get_comment_data(paged){
				
				menuActive('comments')
				loading( _main )
				
				$.ajax({
					url: ajax_url,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'comments',
						paged: paged
					},
					success: function(data, textStatus, xhr) {
						if( data.items ){
							_main.html( '<ul class="user-comment-list"></ul>' )
							$('.user-comment-list').html(
								$('#temp-comment-item').render( data.items )
							).after( paging(data.max, paged, '#comments/') )
						}else{
							loading(_main, _msg['1301'])
						}
					},
					error: function(xhr, textStatus, errorThrown) {
						loading(_main, _msg['1079'])
					}
				});
			}
			
			// 菜单激活函数
			function menuActive(name){
				$('.user-menus li').removeClass('active')
				$('.user-menu-'+name).addClass('active')
			}
			// 加载中函数
			function loading(el, msg){
				if( !msg ){
					msg = '<i class="iconfont qgg-loading" style="position:relative;top:1px;margin-right:5px;"></i> 数据加载中'
				}
				el.html('<div class="user-loading">'+msg+'</div>')
			}
			// 图片加载异步加载 jquery.lazyload.min.js
			function thumb_lazyload(){
				require(['lazyload'], function(){
					$('.user-main .thumb').lazyload({
						data_attribute : 'src',
						placeholder    : jsui.uri + '/img/thumbnail.png',
						threshold      : 400
					});
				});
			}
			// 页码函数
			function paging(max, current, plink, step){
				var show = 2
				if( !step ) step = 10
				if ( max <= step ) { return }
				max = Math.ceil(max/step)
				var html = '<div class="pagination user-pagination"><ul>'
				
				if ( !current ) current = 1
				current = Number(current)
				if ( current > show + 1 ) html += '<li><a href="'+plink+'1">1</a></li>'
				if ( current > show + 2 ) html += '<li><span>...</span></li>'
				for( i = current - show; i <= current + show; i++ ) { 
					if ( i > 0 && i <= max ){
						html += (i == current) ? '<li class="active"><span>'+i+'</span></li>' : '<li><a href="'+plink+i+'">'+i+'</a></li>'
					}
				}
				
				if ( current < max - show - 1 ) html += '<li><span>...</span></li>'
				if ( current < max - show ) html += '<li><a href="'+plink+max+'">'+max+'</a></li>'
				
				html += '<li><span>共'+max+'页</span></li>'
				html += '</ul></div>'
				
				return html
			}
			
			// 提示信息函数
			var _tipstimer
			function tips(str){
				if( !str ) return false
				_tipstimer && clearTimeout(_tipstimer)
				$('.user-tips').html(str).animate({
					top: 0
				}, 220)
				_tipstimer = setTimeout(function(){
					$('.user-tips').animate({
						top: -30
					}, 220)
				}, 5000)
			}
			
			// 点击事件 AJAX 请求
			$('#user-center').on('click', function(event){
				event = event || window.event;
				var target = event.target || event.srcElement
				var _ta = $(target)
				
				if( _ta.parent().attr('evt') ){
					_ta = $(_ta.parent()[0])
				}else if( _ta.parent().parent().attr('evt') ){
					_ta = $(_ta.parent().parent()[0])
				}
				
				var type = _ta.attr('evt')
				
				if( !type || _ta.hasClass('disabled') ){ return }
				
				switch( type ){
					// 投稿提交
					case 'publish.submit':
						
						var form = _ta.parent().parent().parent()
						var inputs = form.serializeObject()

						if( !window.tinyMCE ){
							tips('数据异常');  
							return
						}
						
						inputs.post_content = tinyMCE.activeEditor.getContent();
						
						var title   =  $.trim(inputs.post_title)
						var url     =  $.trim(inputs.post_url)
						var content =  $.trim(inputs.post_content)
						
						if ( !title || title.length > 50 ) {
							tips('标题不能为空，且小于50个字符');  
							return
						}
						if ( !content || content.length > 10000 || content.length < 10 ) {
							tips('文章内容不能为空，且介于10-10000字之间');  
							return
						}
						if ( !url && url.length > 200 ) {
							tips('来源链接不能大于200个字符');  
							return
						}
						
						$.ajax({  
							type: 'POST',  
							url:  ajax_url,  
							data: inputs,  
							dataType: 'json',
							success: function(data){  
								if( data.error ){
									if( data.msg ){
										tips(data.msg)
									}
									return
								}
								form.find('.form-control').val('')
								location.hash = 'posts/draft'
							}  
						});  
						break;
						
					// 头像上传
					case 'avatar.upload':
						$('#local-avatar-upload').trigger('click');
						// 无 upload_files 权限本地上传图片文件
						$('body').on('change', '#local-avatar-upload', function(e) {
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
							$('.user-avatar-img img').attr('src', filePath);
							$('#avatar').attr('value', filePath);
							alert('Error：头像上传功能待开发，该版本暂不支持！')
						});
						
						break;
						
					// 信息修改
					case 'info.submit':
						
						var form = _ta.parent().parent().parent();
						var inputs = form.serializeObject();
						var avatar = $('#local-avatar-upload');
						if( avatar.val() ){
							inputs['avatar_value'] = avatar.val();
							inputs['avatar_name'] = avatar[0].name;
							inputs['avatar_type'] = avatar[0].type;
							/* inputs['avatar_error'] = avatar.error; */
							inputs['avatar_size'] = avatar[0].size;						
						}
						if( !inputs.action ){
							return;
						}
						if( !/.{2,20}$/.test(inputs.nickname) ){
							tips('昵称限制在2-20字内');
							return;
						}
						if( inputs.url && (!jsui.is_url(inputs.url) || inputs.url.length>100) ){
							tips('网址格式错误,注意以http开头,后面不要加反斜杠/');
							return;
						}
						if( inputs.qq && !jsui.is_qq(inputs.qq) ){
							tips('QQ格式错误');
							return;
						}
						if( inputs.wechat && inputs.wechat.length>30 ){
							tips('微信字数过长，限制在30字内');
							return;
						}
						if( inputs.weibo && (!jsui.is_url(inputs.weibo) || inputs.weibo.length>100) ){
							tips('微博格式错误');
							return;
						}
						
						$.ajax({
							type: 'POST',
							url:  ajax_url,
							data: inputs,
							dataType: 'json',
							success: function(data){
								if( data.error ){
									if( data.msg ){
										tips(data.msg)
									}
									return
								}
								tips('恭喜您，修改成功！')
								cache_userdata = null
							} 
						});
						break;
						
					// 密码修改
					case 'password.submit':
						
						var form = _ta.parent().parent().parent()
						var inputs = form.serializeObject()
						
						if( !inputs.action ){ 
							return 
						}
						if( !$.trim(inputs.passwordold) ){
							tips('请输入原密码')
							return
						}
						if( !inputs.password || inputs.password.length < 6 ){
							tips('新密码不能为空且至少6位')
							return
						}
						if( inputs.password !== inputs.password2 ){
							tips('两次密码输入不一致')
							return
						}
						if( inputs.passwordold === inputs.password ){
							tips('新密码和原密码不能相同')
							return
						}
						
						$.ajax({  
							type: 'POST',  
							url:  ajax_url,  
							data: inputs,  
							dataType: 'json',
							success: function(data){  
								if( data.error ){
									if( data.msg ){
										tips(data.msg)
									}
									return
								}
								tips('修改成功！下次登录请使用新密码！')
								$('input:password').val('')
							}
						});  
						break;
				}
			})
			
			// 将表单转换为json对象
			$.fn.serializeObject = function(){
				var o = {};
				var a = this.serializeArray();
				$.each(a, function() {
					if (o[this.name] !== undefined) {
						if (!o[this.name].push) {
							o[this.name] = [o[this.name]];
						}
						o[this.name].push(this.value || '');
					} else {
						o[this.name] = this.value || '';
					}
				});
				return o;
			};
			
			
			})(jQuery);
		}
	}
});