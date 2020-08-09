define(function (){

return {
	init: function (){
		
		(function($){
			var signHtml = '\
				<div class="sign">\
					<div class="sign-mask"></div>\
					<div class="container">\
						<a href="javascript:;" class="close-link sign-close"><i class="iconfont qgg-cuohao"></i></a>\
						<!-- 登录表单 -->\
						<form id="sign-in">\
							<h3><small class="signup-loader">切换注册</small>登录</h3>\
							<h6>\
								<input type="text" name="signinName" class="form-control" id="signinName" placeholder="用户名或邮箱">\
							</h6>\
							<h6>\
								<input type="password" name="signinPassword" class="form-control" id="signinPassword" placeholder="请输入登录密码">\
							</h6>\
							<div class="sign-remember"><input type="checkbox" name="signinRemember" checked="checked" id="signinRemember" value="forever">记住我</div>\
							'+( jsui.reset_pwd ? '<div class="sign-resetpwd"><a href="'+jsui.reset_pwd+'">找回密码？</a></div>' : '' )+'\
							<div class="sign-submit">\
								<input type="button" class="btn btn-default signsubmit-loader" name="submit" value="登录">  \
								<input type="hidden" name="action" value="signin">\
							</div>\
						</form>\
						<!-- 注册表单 -->\
						<form id="sign-up"> \
							<h3><small class="signin-loader">切换登录</small>注册</h3>\
							<h6>\
								<input type="text" name="signupName" class="form-control" id="signupName" placeholder="请输入以字母开头的登录名称">\
							</h6>\
							<h6>\
								<input type="email" name="signupEmail" class="form-control" id="signupEmail" placeholder="请输入常用邮箱">\
							</h6>\
							<h6>\
								<input type="password" name="signupPassword" class="form-control" id="signupPassword" placeholder="设置密码">\
							</h6>\
							<h6>\
								<input type="password" name="signupPassword2" class="form-control" id="signupPassword2" placeholder="确认密码">\
							</h6>\
							<div class="sign-submit">\
								<input type="button" class="btn btn-primary btn-block signsubmit-loader" name="submit" value="快速注册">  \
								<input type="hidden" name="action" value="signup">  \
							</div>\
						</form>\
						<div class="sign-tips"></div>\
					</div>\
				</div>\
			';
			// 添加登陆窗体 HTML 代码
			jsui.body.append( signHtml );
			// 用户中心弹窗登陆
			if( $('.is-sign-show').length ){
				jsui.body.addClass('sign-show')
				setTimeout(function(){
					$('#sign-in').show().find('input:first').focus()
				}, 300);
				$('#sign-up').hide()
			}
			// 切换登陆按钮
			$('.signin-loader').on('click', function(){
				jsui.body.addClass('sign-show')
				setTimeout(function(){
					$('#sign-in').show().find('input:first').focus()
				}, 300);
				$('#sign-up').hide()
			});
			// 切换注册按钮
			$('.signup-loader').on('click', function(){
				jsui.body.addClass('sign-show')
				setTimeout(function(){
					$('#sign-up').show().find('input:first').focus()
				}, 300);
				$('#sign-in').hide()
			});
			// 按钮关闭登陆窗体
			$('.sign-close').on('click', function(){
				jsui.body.removeClass('sign-show')
			});
			// 遮罩关闭登陆窗体
			$('.sign-mask').on('click', function(){
				jsui.body.removeClass('sign-show')
			});
			// 回车提交表单信息
			$('.sign form').keydown(function(e){
				var e = e || event,
				keycode = e.which || e.keyCode;
				if (keycode==13) {
					$(this).find('.signsubmit-loader').trigger("click");
				}
			});
			// 点击提交按钮，AJAX 请求 user_sign.php 文件
			$('.signsubmit-loader').on('click', function(){
				if( jsui.is_signin ){ return; }
				var form = $(this).parent().parent()
				var inputs = form.serializeObject()
				var isreg = (inputs.action == 'signup') ? true : false
				if( !inputs.action ){ return; }
				
				if( isreg ){
					if( !jsui.is_mail(inputs.signupEmail) ){
						logtips('邮箱格式错误')
						return;
					}
					if( !/^[a-z\d_]{3,20}$/.test(inputs.signupName) ){
						logtips('昵称是以字母数字下划线组合的3-20位字符')
						return;
					}
					if( inputs.signupPassword.length < 6 ){
						logtips('密码太短，请确保密码至少6位')
						return;
					}
					if( inputs.signupPassword.length != inputs.signupPassword2.length ){
						logtips('两次密码不一致，请重新输入密码')
						return;
					}
				};
				
				$.ajax({  
					type: "POST",
					url:  jsui.uri+'/action/user_sign.php',
					data: inputs,
					dataType: 'json',
					success: function(data){
						if( data.msg ){ logtips(data.msg) };
						if( data.error ){ return; };
						if( !isreg ){
							location.reload()
						}else{
							if( data.goto ) location.href = data.goto
						};
					},
					error:function(){
						logtips("数据提交错误，请稍后重试")
					}
				});
			});
			
			// 序列化 form 表单函数
			serializeObject = function(){
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
			
			// 生成提示信息
			var _loginTipstimer
			function logtips(str){
				if( !str ) return false;
				_loginTipstimer && clearTimeout(_loginTipstimer)
				$('.sign-tips').html(str).animate({
					height: 29
				}, 220)
				_loginTipstimer = setTimeout(function(){
					$('.sign-tips').animate({
						height: 0
					}, 220)
				}, 5000)
			}
		})(jQuery);
		
	}
}

});