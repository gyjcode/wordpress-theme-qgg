/**==================== require.js 文件配置代码 ====================**/
require.config({
	baseUrl: jsui.uri + '/js',
	urlArgs: 'ver=' + jsui.ver,
	waitSeconds: 0,
	paths: {
		'lazyload'      : 'libs/jquery.lazyload.min',
		'qrcode'        : 'libs/jquery.qrcode.min',
		'cookie'        : 'libs/jquery.cookie.min',
		'prettyprint'   : 'libs/prettyprint.min',
		'jsrender'      : 'libs/jsrender.min',
		'swiper'        : 'libs/swiper.min',
		'animate'       : 'libs/swiper.animate.min',
		'videojs'       : 'libs/video.min',
		'router'        : 'libs/router.min',
		'comment'       : 'comment-ajax',
		'layer'         : 'libs/layer.min',
		'user-center'   : 'user-center',
		'user-sign'     : 'user-sign',
		'poster'        : 'poster'
	},
	shim: {
		'poster': { 
			exports: 'poster',
		},
		'videojs': {
			exports: 'videojs',
		},
	}
});

/** 基于 JQuery 的脚本  */
jQuery(document).ready(function(){
/*==================== jQuery 代码开始 ====================*/
	
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

	// 滚动函数，用于设置页面元素滚动到指定位置
	jsui.scrollTo = function (name, add, speed) {
		if (!speed) speed = 300
		if (!name) {
			$('html,body').animate({
				scrollTop: 0
			}, speed)
		} else {
			if ($(name).length > 0) {
				$('html,body').animate({
					scrollTop: $(name).offset().top + (add || 0)
				}, speed)
			}
		}
	}

	// 校验函数，校验用户输入的 名称 、 网址 、 QQ 、邮箱 是否正确
	jsui.is_name = function (str) { return /.{2,12}$/.test(str) };
	jsui.is_url  = function (str) { return /^((http|https)\:\/\/)([a-z0-9-]{1,}.)?[a-z0-9-]{2,}.([a-z0-9-]{1,}.)?[a-z0-9]{2,}$/.test(str) };
	jsui.is_qq   = function (str) { return /^[1-9]\d{4,13}$/.test(str) };
	jsui.is_mail = function (str) { return /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(str) };

	// 常量定义
	jsui.body = $('body');
	jsui.is_signin = jsui.body.hasClass('logged-in') ? true : false;
	
	// 判断用户登录状态加载 user-sign.js
	if (!jsui.body.hasClass('logged-in')){
		require(['user-sign'], function(usersign) {
			usersign.init();
		});
	};
	
	// 判断用户中心开启加载 user-center.js
	if (jsui.body.hasClass('user-center-open')){
		require(['user-center'], function(usercenter){
			usercenter.init();
		});
	};
	
	// 判断评论开启加载 comment-ajax.js
	if (jsui.body.hasClass('comment-open')){
		require(['comment'], function(comment){
			comment.init();
		});
	};
	
	// 代码高亮加载 prettyprint.min.js
	$('pre').each(function(){
		if( !$(this).attr('style') ) $(this).addClass('prettyprint linenums')
	});
	if( $('.prettyprint').length ){
		require(['prettyprint'], function(prettyprint) {
			prettyPrint()
		})
	};
	
	// 图片懒加载
	require(['lazyload'], function(){
		// 特色图像
		$('.thumb').lazyload({
			effect: "fadeIn",
			data_attribute: 'src',
			placeholder: jsui.uri + '/img/thumbnail.png',
			threshold: 400
		});
		// 用户头像
		$('.avatar').lazyload({
			effect: "fadeIn",
			data_attribute: 'src',
			placeholder: jsui.uri + '/img/avatar-default.png',
			threshold: 400
		});
		// 小工具头像
		$('.widget .avatar').lazyload({
			effect: "fadeIn",
			data_attribute: 'src',
			placeholder: jsui.uri + '/img/avatar-default.png',
			threshold: 400
		});
	});
	
	
	// 导航条滚动锁定
	$(window).scroll(function() {
		var window_width= $(window).width();
		$(window).resize(function(event) {
		    window_width = $(window).width();
		});
		var nav_fixed = jsui.body.hasClass('nav_fixed') ? true : false;
		var scroller = $('.rollbar-totop')
		var current_height = document.documentElement.scrollTop + document.body.scrollTop
		
		if( nav_fixed && current_height > 0 && window_width > 768 ){
			jsui.body.addClass('nav-fixed')
		}else{
			jsui.body.removeClass('nav-fixed')
		}
		current_height > 100 ? scroller.fadeIn() : scroller.fadeOut();
	});
	
	// 手机端导航显示
	$('.mobile-nav-btn').on('click', 'a.mobile-nav-ico', function(){
		$('.site-nav .nav-cover').toggleClass('actived');
	});
	$('.nav-cover').on('click', function(){
		$('.site-nav .nav-cover').removeClass('actived');
	});
	
	// 搜索框显示隐藏
	$('.site-search').on('click', 'a.site-search-ico', function(){
		jsui.body.toggleClass('search-on')
		$('#site-search-box').toggleClass('actived');
		if ($('#site-search-box').hasClass('actived')){
			$('.site-search a.site-search-ico').html('<i class="iconfont qgg-cuohao" aria-hidden="true"></i>');
			 $('.site-search').find('input').focus()
		}else{
			$('.site-search a.site-search-ico').html('<i class="iconfont qgg-search" aria-hidden="true"></i>');
		}
	});
	// 搜索页面过滤显示搜索结果
	if( jsui.body.hasClass('search-results') ){
		var val = $('.site-search-form .search-input').val()
		var reg = eval('/'+val+'/i')
		$('.excerpt h2 a, .excerpt .note').each(function(){
			$(this).html( $(this).text().replace(reg, function(w){ return '<b>'+w+'</b>' }) )
		})
	};
	
	// 首页滚动公告JS代码
	window.scrollAnnouncement = function(){
		var timer;
		var speed = speed ? speed : 10;         // 自定义滚动速度，数值越大滚动越快
		var pause=false;
		var objDiv=$("#scroll-announcement-list");
		var heightLi = $("#scroll-announcement-list li").height();
		var increment = heightLi/(heightLi-speed);
		if (objDiv == null){ return; } 
		
		objDiv.append(objDiv.html());
		objDiv.scrollTop(0);
		objDiv.hover(function(){ pause=true },function(){ pause=false });
		
		function startMove(){
			timer=setInterval(scrolling,30);
			if(!pause){ objDiv.scrollTop(objDiv.scrollTop()+increment); }         //控制滚动速度
		} 
		function scrolling(){
			if( objDiv.scrollTop() % heightLi != 0 ){
				objDiv.scrollTop(objDiv.scrollTop()+increment);         //控制滚动速度
				if( objDiv.scrollTop() >= objDiv[0].scrollHeight/2 ){ objDiv.scrollTop(0); }         // 判断是否一圈滚动完成
			}else{
				clearInterval(timer); 
				setTimeout(startMove,3000);        //设置滚动时间 
			}
		}
		setTimeout(startMove,3000);         //设置滚动时间
	};
	scrollAnnouncement();
	
	// 首页全屏轮播图加载 swiper.min.js
	require(['swiper', 'animate'],function(Swiper, Animate){
		
		var mySwiper = new Swiper ('.swiper-container',{
			
			//history: true,
			direction: 'horizontal', // 垂直切换选项
			loop: false, // 循环模式选项
			speed: 500, // 滑块滑动的速度
			effect : 'coverflow',
			slidesPerView: 1, // Slider 容器同时显示的滑块数量
			centeredSlides: true, // 激活的滑块居中
			coverflowEffect: {
				rotate: 0,
				stretch: 0,
				depth: 10,
				modifier: 0,
				slideShadows : true
			},
			// 如果需要分页器
			pagination: {
				el: '#banner-nav .swiper-pagination',
				clickable :true,
			},
			// 如果需要前进后退按钮
			navigation: {
				nextEl: '.swiper-button-next',
				prevEl: '.swiper-button-prev',
			},
			
			// Animate 动画
			on:{
				init: function(){
					swiperAnimateCache(this); //隐藏动画元素 
					swiperAnimate(this); //初始化完成开始动画
				}, 
				slideChangeTransitionEnd: function(){ 
					swiperAnimate(this); //每个slide切换结束时也运行当前slide动画
					//this.slides.eq(this.activeIndex).find('.ani').removeClass('ani'); //动画只展现一次，去除ani类名
				} 
			}
		});
		// 鼠标悬停
		$('.swiper-slide').mouseenter(function () {
			mySwiper.autoplay.stop();
		})
		$('.swiper-slide').mouseleave(function () {
			mySwiper.autoplay.start();
		})
		
	});
	
	// 网站底部显示运行时间
	window.showSiteRuntime = function(){
		site_runtime = $("#site_runtime");
		if (!site_runtime){return;}
		window.setTimeout("showSiteRuntime()",1000); // 每秒运行一次函数
		start=new Date(jsui.site_time); //在这里修改你的建站时间
		//start=new Date("2017-04-01 00:00:00");
		now=new Date();
		T=(now.getTime() - start.getTime()); // 获取当前时间与指定时间之间的时间间隔（ms）	
		i=24*60*60*1000;
		d=T/i;
		D=Math.floor(d); // 计算天数并向下取整
		h=(d-D)*24;
		H=Math.floor(h); // 计算剩余不足一天的小时数并向下取整
		m=(h-H)*60;
		M=Math.floor(m); // 计算剩余不足一小时的分钟数并向下取整
		s=(m-M)*60
		S=Math.floor(s); // 计算剩余不足一分钟的秒数并向下取整
		site_runtime.html( D + " 天 " + H + " 小时 " + M + " 分 " + S + " 秒 " );
	};
	showSiteRuntime();
	
	// 文章页展开收缩按钮
	$('.collapse-title').click(
		function(){
			$(this).parent().find('.collapse-content').slideToggle('slow');
		}
	);
	
	// 加载 jquery.qrcode.min.js 生成文章二维码
	require(['qrcode'], function(qrcode) {
		$('.post-qrcode').qrcode({
			render:  "canvas",
			width:   200,
			height:  200,
			text:    window.location.href
		});
	});
	$('.post-qrcode-mask').on('click', function(){
		$('.post-qrcode-mask, .post-qrcode').fadeOut()
	});
	// 文章分享模块功能代码
	shareTo = function(stype){
		var post_title = '';
		var img_link = '';
		var lk = '';
		
		//获取文章标题
		post_title = document.title;
		// 获取文章描述信息
		post_desc = document.querySelector('meta[name="description"]') ? document.querySelector('meta[name="description"]').getAttribute('content') : '暂时没有描述信息！';
		//获取网页中内容的第一张图片地址作为分享图
		img_link = document.images[0].src;
		
		if(typeof img_link == 'undefined'){
			img_link='';
		}
		//当内容中没有图片时，设置分享图片为网站logo
		if(img_link == ''){
			lk = 'http://'+window.location.host+'/static/images/logo.png';
		}
		//如果是上传的图片则进行绝对路径拼接
		if(img_link.indexOf('/uploads/') != -1) {
			lk = 'http://'+window.location.host+img_link;
		}
		//百度编辑器自带图片获取
		if(img_link.indexOf('ueditor') != -1){
			lk = img_link;
		}
		//qq空间接口的传参
		if(stype=='qzone'){
			window.open('https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+document.location.href+'?sharesource=qzone&title='+post_title+'&pics='+lk+'&summary='+post_desc);
	   }
		//新浪微博接口的传参
		if(stype=='sina'){
			window.open('http://service.weibo.com/share/share.php?url='+document.location.href+'?sharesource=weibo&title='+post_title+'&pic='+lk+'&appkey=2706825840');
		}
		//qq好友接口的传参
		if(stype == 'qq'){
			window.open('http://connect.qq.com/widget/shareqq/index.html?url='+document.location.href+'?sharesource=qzone&title='+post_title+'&pics='+lk+'&summary='+post_desc);
		}
		//生成二维码给微信扫描分享,使用jquery.qrcode.js插件实现二维码生成
		if(stype == 'wechat'){
			$('.post-qrcode-mask, .post-qrcode').fadeIn()
		}
	};
	
	// 文章喜欢加载 jquery.cookies.min.js
	if( $('.post-like').length ){
		require(['cookie'], function() {
			$('.content').on('click', '[data-event="post-like"]', function(){
				var _ta = $(this)
				var pid = _ta.attr('data-post_id')
	
				if( _ta.hasClass('actived') ) return alert('Yahoo，你已经赞过了哦！')
				
				if ( !pid || !/^\d{1,}$/.test(pid) ) return;
	
				if( !jsui.is_signin ){
					var lslike = lcs.get('likes') || ''
					if( lslike.indexOf(','+pid+',')!==-1 ) return alert('Yahoo，你已经赞过了哦！')
	
					if( !lslike ){
						lcs.set('likes', ','+pid+',')
					}else{
						if( lslike.length >= 160 ){
							lslike = lslike.substring(0,lslike.length-1)
							lslike = lslike.substr(1).split(',')
							lslike.splice(0,1)
							lslike.push(pid)
							lslike = lslike.join(',')
							lcs.set('likes', ','+lslike+',')
						}else{
							lcs.set('likes', lslike+pid+',')
						}
					}
				}
	
				$.ajax({
					url: jsui.uri + '/action/post_like.php',
					type: 'POST',
					dataType: 'json',
					data: {
						key: 'likes',
						pid: pid
					},
					success: function(data, textStatus, xhr) {
						if (data.error) return false;
						_ta.toggleClass('actived')
						_ta.find('span').html(data.response)
					}
				});
			});
		});
	};
	
	// 打赏按钮 JS 代码
	$('[data-event="rewards-popover"]').on('click', function(){
		$('.rewards-popover-mask, .rewards-popover-box').fadeIn()
	});
	$('[data-event="rewards-close"]').on('click', function(){
		$('.rewards-popover-mask, .rewards-popover-box').fadeOut()
	});
	
	// 海报按钮 JS 代码
	$('[data-event="poster-popover"]').on('click', function(){
		$('.poster-popover-mask, .poster-popover-box').fadeIn()
	});
	$('[data-event="poster-close"]').on('click', function(){
		$('.poster-popover-mask, .poster-popover-box').fadeOut()
	});
	
	// 海报生成
	// 加载 jquery.qrcode.min.js 生成海报二维码
	require(['qrcode'], function(qrcode) {
		$('.poster-qrcode').qrcode({
			render:  "canvas",
			width:   200,
			height:  200,
			text:    window.location.href
		});
	});
	if( $('.post-poster').length ){
		require(['poster'],function( poster ){
			
			var qrcanvas = $('.poster-qrcode canvas')[0];    //二维码所在的canvas
			var qrcode_img = convertCanvasToImage(qrcanvas)
			
			function convertCanvasToImage(canvas) {
				var canvas;
				var image = new Image();
				canvas ? image.src = canvas.toDataURL("image/png"): "" ;
				return image;
			}
			// 海报顶部特色图像
			banner_link     = jsui.att_img ? jsui.att_img : document.images[0].src;
			// 海报顶部 logo 图标
			poster_logo     = jsui.logo_pure ? jsui.logo_pure : jsui.uri + '/img/logo-pure.png';
			// 海报中部文章标题
			poster_title    = document.title;
			// 海报中部文章摘要
			poster_desc     = jsui.excerpt ? '        ' + jsui.excerpt + ' … …': '暂时没有描述信息！';
			// 海报中部文章Meta
			poster_meta     = '本文由『'+jsui.author+'』于〔'+jsui.update+'〕更新至《'+jsui.cat_name+'》分类下'
			// 海报底部站点 favicon 图标
			poster_siteicon = jsui.site_icon ? jsui.site_icon : jsui.uri + '/img/favicon.ico';
			// 海报底部站点名称
			poster_sitename = jsui.site_name ? jsui.site_name : '蝈蝈要安静';
			// 海报底部站点标语
			poster_slogan   = jsui.poster_slogan ? jsui.poster_slogan : '一个不学无术的伪程序员';
			// 海报底部文章二维码
			poster_qrcode   = qrcode_img['src'] ? qrcode_img['src'] : jsui.uri + '/img/qrcode.png';
			
			poster.init({
				selector: '.poster-popover-box',
				// 特色图像
				banner : banner_link,
				// logo图标
				logo   : poster_logo,
				// 文章标题
				title : poster_title,
				titleStyle:{
					font     : 'bold normal 48px 微软雅黑',
					color    : 'rgba(66, 66, 66, 1)',
					position : 'left',
				},
				//文章摘要
				content : poster_desc,
				contentStyle:{
					font       : 'italic 24px 微软雅黑',
					color      : 'rgba(99, 99, 99, 1)',
					position   : 'left',
					lineHeight : 1.5,    // 多行文本,用于计算行数
					maxHeight  : 210,    // 多行文本,用于计算行数
				},
				// 文章Meta
				postmeta : poster_meta,
				postmetaStyle:{
					font       : 'normal 24px 微软雅黑',
					color      : 'rgba(66, 200, 120, 1)',
					position   : 'left',
					lineHeight : 1.2,    // 多行文本,用于计算行数
					maxHeight  : 60,     // 多行文本,用于计算行数
				},
				
				// Icon图标
				siteicon : poster_siteicon,
				// 站点名称
				sitename : poster_sitename,
				sitenameStyle:{
					font     : 'normal 36px 微软雅黑',
					color    : 'rgba(66, 66, 66, 1)',
					position : 'left'
				},
				// 站点标语
				slogan : poster_slogan,
				sloganStyle:{
					font: 'normal 24px 微软雅黑',
					color: 'rgba(99, 99, 99, 1)',
					position: 'left'
				},
				// 文章二维码
				qrcode : poster_qrcode,
				
				callback : posterDownload
			});
			
			function posterDownload(container){
				if(container == null) {return;}
				const $btn = container.querySelector('.poster-download')
				const $img = container.querySelector('img')
				$btn.setAttribute('href', $img.getAttribute('src'));
			};
		});
	};
		
	// 产品分类页面加载 jquery.qrcode.min.js
	require(['qrcode'],function(qrcode){
		$('.cat-qrcode').each(function(index, el) {
			$(this).data('url') && $(this).qrcode({
				text:     encodeURI($(this).data('url')), 
				width:    120, 
				height:   120,
			});
		});
	});
	
	// 视频文章页面加载 video.min.js
	define('global/window', [], () => {
		return window;
	});
	
	define('global/document', ['global/window'], (window) => {
		return window.document;
	});
	
	require(['videojs'],function( videojs ){
		if( $("#player").length ){
			window.videojs = videojs;
			// any other initialization you want here
			const player = videojs( "player", {
				autoplay : false,                   // 自动播放
				controls : true,                    // 是否显示控制条
				loop     : false,                   // 循环播放
				muted    : false,                   // 静音
				preload  : "auto",                  // 预加载
				
				fluid    : true,                    // 播放器可变大小
				language : "zh-CN",                 // 设置语言
				notSupportedMessage : "抱歉,当前媒体类型暂不允许播放!",    // 媒体类型错误提示
				controlBar : {
					volumePanel : {
						inline : false
					}
				}
				
			});
			// 点击切换视频
			$('.video-lists-diversity').on('click','a.video-lists-item',function(){
				player.src( $(this).attr('data_src') )
			});
		}
	});
	
	// erphpdown 登录使用主题弹出登录框
	$('.erphp-login-must').each(function(){
	    $(this).addClass('signin-loader')
	});
	
	// 聚合文章小工具 Tab 切换功能
	$('.widget-polymer-posts .title').on('mousemove', 'h3', function(){
		
		taTitle   = $('.widget-polymer-posts .title h3');
		taContent = $('.widget-polymer-posts .content ul');
		index     = $(this).index();
		
		taTitle.siblings().removeClass('actived');
		$(this).addClass('actived');
		taContent.siblings().removeClass('actived');
		taContent.eq(index).addClass('actived')
		
	});
	
/*==================== jQuery 代码结束 ====================*/
});