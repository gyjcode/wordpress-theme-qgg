<?php
/**
 * @ name 用户右键菜单功能增强
 * @ description 在引用位置处加载一个指定分类的友情链接
 */
?>

<div class="right-click-menu">
    <ul>
        <li><a href="javascript:window.location.reload();"><i class=""></i><span>刷新页面</span></a></li>
        <li><a href="javascript:history.go(1);"><i class=""></i><span>前进一页</span></a></li>
        <li><a href="javascript:history.go(-1);"><i class=""></i><span>后退一页</span></a></li>
        <li><a href="javascript:void(0);" onclick="getSelect();"><i class=""></i><span>复制文字</span></a></li>
        <li><a target="_self" href="javascript:void(0);"onclick="printMe();"><i class=""></i><span>打印页面</span></a></li>
        <li><a target="_blank" href="javascript:void(0);" onclick="googleSearch();"><i class=""></i><span>谷歌搜索</span></a></li>
        <li><a target="_blank" href="javascript:void(0);" onclick="baiduSearch();"><i class=""></i><span>百度搜索</span></a></li>
        <li><a target="_blank" href="<?php bloginfo('rss2_url'); ?>"><i class=""></i><span>订阅本站</span></a></li>
    </ul>
</div>

<style type="text/css">
	.right-click-menu{
		z-index: 999999999;
		display: none;
		position: absolute;
		width: 140px;
		height: auto;
		margin: 0;
		border: 0;
		padding: 0;
		font-size: 14px;
		-moz-box-shadow: 1px 1px 3px rgba(0,0,0,.3);
		box-shadow: 1px 1px 3px rgba(0,0,0,.3);
		background: #e0f3ff;
	}
	.right-click-menu ul{
		display: block;
		width: 100%;
		height: auto;
		margin: 0;
		border: 0;
		padding: 0;
		overflow: hidden;
	}
	.right-click-menu ul li{
		display: block;
		margin: 0;
		border: 0;
		padding: 0;
		line-height: 36px;
		border-bottom: 1px solid #fff;
	}
	.right-click-menu ul li:last-child{
		border-bottom: 0;
	}
	.right-click-menu ul li a{
		display: block;
		margin: 0;
		border: 0;
		padding: 0 20px;
		color: #555;
	}
	.right-click-menu ul li a:hover{
		color: #fff;
		background: rgba(36, 160, 240, 0.6);
	}
	.right-click-menu ul li a i{
		margin: 0 10px 0 0;
		border: 0;
		padding: 0;
	}
</style>

<script type="text/javascript">
	/** 鼠标右键菜单功能 */
	(function(a) {
	    a.extend({
	        mouseMoveShow: function(b) {
	            var d = 0,
	                c = 0,
					h = 0,
					k = 0,
					e = 0,
					f = 0;
	            a(window).mousemove(function(g) {
					d = a(window).width();
					c = a(window).height();
					h = g.clientX;
					k = g.clientY;
					e = g.pageX;
					f = g.pageY;
					h + a(b).width() >= d && (e = e - a(b).width() - 5);
					k + a(b).height() >= c && (f = f - a(b).height() - 5);
					a("html").on({
						contextmenu: function(c) {
							3 == c.which && a(b).css({
								left: e,
									top: f
							}).show()
						},
						click: function() {
							a(b).hide()
						}
					})
	            })
	        },
	        disabledContextMenu: function() {
	            window.oncontextmenu = function() {
	                return !1
	            }
	        }
	    })
	})(jQuery);
	// 打印页面
	function printMe() {
	    var global_Html = "";
	    global_Html = document.body.innerHTML;
	    document.body.innerHTML = document.querySelector('body>.container').innerHTML;　　　　　　　　　　　　　　
	    window.print();
	    window.setTimeout(function() {
	        document.body.innerHTML = global_Html;
	    }, 1500);
	} 
	// 复制文本
	function getSelect() {
	    var a = window.getSelection ? window.getSelection() : document.selection.createRange().text;
	    "" == a ? alert("啊噢~~~，你没还没选择文字呢！！！") : document.execCommand("Copy")
	}
	// 百度搜索
	function baiduSearch() {
	    var a = window.getSelection ? window.getSelection() : document.selection.createRange().text;
	    "" == a ? alert("啊噢~~~，你没还没选择文字呢！！！") : window.open("https://www.baidu.com/s?wd=" + a)
	}
	// 谷歌搜索
	function googleSearch() {
	    var a = window.getSelection ? window.getSelection() : document.selection.createRange().text;
	    "" == a ? alert("啊噢~~~，你没还没选择文字呢！！！") : window.open("https://www.google.com/search?q=" + a)
	}
	
	$(function() {
	    for (var a = navigator.userAgent, b = "Android;iPhone;SymbianOS;Windows Phone;iPad;iPod".split(";"), d = !0, c = 0; c < b.length; c++)
	        if (0 < a.indexOf(b[c])) {
		    d = !1;
		    break
		}
	    d && ($.mouseMoveShow(".right-click-menu"), $.disabledContextMenu())
	});
</script>