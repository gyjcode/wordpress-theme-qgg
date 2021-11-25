/**
 * 用户登录弹窗
 * GSM.body      ：获取 body 对象
 * GSM.reset_pwd ：找回密码页面地址
 * GSM.is_signin ：判断用户是否登录
 * GSM.uri       ：统一资源定位地址
 * 
 */
jsdefine('user-sign', function (){

return {
    init: function (){
        
        (function($){
            var signHtml = '\
                <div id="user-sign" class="user-sign">\
                    <div class="sign-mask"></div>\
                    <div class="content-wrapper site-style-childA-hover-color">\
                        <a href="javascript:;" class="sign-close"><i class="fal fa-times"></i></a>\
                        <!-- 登录表单 -->\
                        <div id="signin" class="content signin">\
                            <div class="title">\
                                <h3 class="site-style-color">登录</h3>\
                                <small class="signup-loader">切换注册</small>\
                            </div>\
                            <form>\
                                <h6>\
                                    <input type="text" name="username" class="form-control site-style-focus-border-color" placeholder="用户名或邮箱">\
                                </h6>\
                                <h6>\
                                    <input type="password" name="password" class="form-control site-style-focus-border-color" placeholder="请输入登录密码">\
                                </h6>\
                                <div class="remember">\
                                    <input type="checkbox" name="remember" checked="checked" id="remember" value="forever">记住我\
                                </div>\
                                '+( GSM.reset_pwd ? '<div class="reset-pwd"><a href="'+ GSM.reset_pwd +'">找回密码？</a></div>' : '' )+'\
                                <div class="submit-wrapper">\
                                    <input type="button" class="sign-submit site-style-background-color" name="submit" value="登录">  \
                                    <input type="hidden" name="action" value="signin">\
                                </div>\
                            </form>\
                        </div>\
                        <!-- 注册表单 -->\
                        <div id="signup" class="content signup">\
                            <div class="title">\
                                <h3 class="site-style-color">注册</h3>\
                                <small class="signin-loader">切换登录</small>\
                            </div>\
                            <form> \
                                <h6>\
                                    <input type="text" name="username" class="form-control site-style-focus-border-color" placeholder="请输入以字母开头的登录名称">\
                                </h6>\
                                <h6>\
                                    <input type="email" name="email" class="form-control site-style-focus-border-color" placeholder="请输入常用邮箱">\
                                </h6>\
                                <h6>\
                                    <input type="password" name="password" class="form-control site-style-focus-border-color" placeholder="设置密码">\
                                </h6>\
                                <h6>\
                                    <input type="password" name="password2" class="form-control site-style-focus-border-color" placeholder="确认密码">\
                                </h6>\
                                <div class="submit-wrapper">\
                                    <input type="button" class="sign-submit" name="submit" value="快速注册">  \
                                    <input type="hidden" name="action" value="signup">  \
                                </div>\
                            </form>\
                        </div>\
                        <div class="sign-tips"></div>\
                    </div>\
                </div>\
            ';


            // 添加登陆窗体 HTML 代码
            GSM.body.append( signHtml );
            // 登陆弹窗 # 显示隐藏
            if( $('.is-sign-show').length ){
                GSM.body.addClass('sign-show')
                setTimeout(function(){
                    $('#signin').show().find('input:first').focus()
                }, 300);
                $('#signup').hide()
            }
            // 登录弹窗 # 按钮关闭
            $('.sign-close').on('click', function(){
                GSM.body.removeClass('sign-show')
            });
            // 登录弹窗 # 遮罩关闭
            $('.sign-mask').on('click', function(){
                GSM.body.removeClass('sign-show')
            });
            // 切换 # 登录 ：全局调佣类 .signin-loader
            $('.signin-loader').on('click', function(){
                GSM.body.addClass('sign-show')
                setTimeout(function(){
                    $('#signin').show().find('input:first').focus()
                }, 300);
                $('#signup').hide()
            });
            // 切换 # 注册 ：全局调用类 .sinup-loader
            $('.signup-loader').on('click', function(){
                GSM.body.addClass('sign-show')
                $('#signup input[type="text"]').val("");
                $('#signup input[type="email"]').val("");
                $('#signup input[type="password"]').val("");
                setTimeout(function(){
                    $('#signup').show().find('input:first').focus()
                }, 300);
                $('#signin').hide()
            });
            // 提交表单 # 回车
            $('#user-sign form').keydown(function(e){
                var e = e || event,
                keycode = e.which || e.keyCode;
                if (keycode==13) {
                    $(this).find('.sign-submit').trigger("click");
                }
            });
            // 提交表单 # 按钮，AJAX 请求 user_sign.php 文件
            $('#user-sign .sign-submit').on('click', function(){
                // 登录用户直接跳出
                if( GSM.is_signin ){ return; }
                // 用户未登录
                var form = $(this).parent().parent();
                var formData = form.serializeObject();
                var isreg = (formData.action == 'signup') ? true : false;
                if( !formData.action ){ return; }
                
                if( isreg ){
                    if( !GSM.is_name(formData.username) ){
                        tips('用户名需以字母开头，且为字母数字下划线组合的3-20位字符')
                        return;
                    }
                    if( !GSM.is_mail(formData.email) ){
                        tips('邮箱格式错误：xxx@xxx.xxx')
                        return;
                    }
                    if( formData.password.length < 6 ){
                        tips('密码太短，请确保密码至少6位')
                        return;
                    }
                    if( formData.password.length != formData.password2.length ){
                        tips('两次密码不一致，请重新输入密码')
                        return;
                    }
                };
                
                $.ajax({  
                    type: "POST",
                    url:  GSM.uri+'/action/user_sign.php',
                    data: formData,
                    dataType: 'json',
                    success: function(data){
                        if( data.msg ){ tips(data.msg) };
                        if( data.error ){ return; };
                        if( !isreg ){
                            location.reload()
                        }else{
                            if( data.goto ) location.href = data.goto
                        };
                    },
                    error:function(err){
                        // console.log(err.responseText)
                        tips("数据提交错误，请稍后重试")
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
            var timer
            function tips(str){
                if( !str ) return false;
                timer && clearTimeout(timer)
                $('.sign-tips').html(str).animate({
                    height: 30
                }, 220)
                timer = setTimeout(function(){
                    $('.sign-tips').animate({
                        height: 0
                    }, 220)
                }, 5000)
            }
        })(jQuery);
        
    }
}

});