<?php
/**
 * @ name 右键菜单美化
 * @ description 在引用位置处加载一个指定分类的友情链接
 */
?>

<div id="newContextMenu" class="right-click-menu">
    <ul>
        <li><a href="javascript:window.location.reload();"><i class="fal fa-redo"></i><span>刷新页面</span></a></li>
        <li><a href="javascript:history.go(1);"><i class="fal fa-hand-point-right"></i><span>前进一页</span></a></li>
        <li><a href="javascript:history.go(-1);"><i class="fal fa-hand-point-left"></i><span>后退一页</span></a></li>
        <li><a href="javascript:void(0);" onclick="copyText();"><i class=""></i><span>复制文本</span></a></li>
        <li><a href="javascript:void(0);" onclick="pasteText();"><i class=""></i><span>粘贴文本</span></a></li>
        <li><a target="_self" href="javascript:void(0);"onclick="printMe();"><i class=""></i><span>打印页面</span></a></li>
        <li><a target="_self" href="javascript:void(0);" onclick="googleSearch();"><i class=""></i><span>谷歌搜索</span></a></li>
        <li><a target="_self" href="javascript:void(0);" onclick="baiduSearch();"><i class=""></i><span>百度搜索</span></a></li>
        <li><a target="_self" href="<?php bloginfo('rss2_url'); ?>"><i class=""></i><span>订阅本站</span></a></li>
    </ul>
</div>

<script type="text/javascript">
    // 鼠标位置显示新右键菜单 # HTML
    function enableNewContextMenu (domMenu) {
        window.onmousemove = function(cursor) {
            // 鼠标靠右重新计算菜单 X 轴位置
            newLeft = cursor.pageX;
            if ( (cursor.clientX + domMenu.scrollWidth) >= window.innerWidth  ) {
                newLeft = cursor.pageX - domMenu.scrollWidth - 5;
            }
            // 鼠标靠下重新计算菜单 Y 轴位置
            newTop = cursor.pageY ;
            if ( (cursor.clientY + domMenu.scrollHeight) >= window.innerHeight ) {
                newTop = newTop - domMenu.scrollHeight  - 5;
            }
            // 监听右键菜单事件
            document.addEventListener('contextmenu', function(event) {
                if(event.which == 3){
                    domMenu.style.display = 'block';
                    domMenu.style.left = newLeft + 'px';
                    domMenu.style.top = newTop + 'px';
                }
            });
            // 监听鼠标点击事件
            document.addEventListener('click', function() {
                domMenu.style.display = 'none';
            });
        }
    }
    enableNewContextMenu( document.getElementById("newContextMenu") );

    // 禁用原有右键菜单
    function disableOldContextMenu () {
        window.oncontextmenu = function() {
            return false;
        }
    }
    disableOldContextMenu();

    // 打印页面
    function printMe() {
        var globalHtml = "";
        globalHtml = document.body.innerHTML;
        document.body.innerHTML = document.querySelector('body>.container').innerHTML;　　　　　　　　　　　　　　
        window.print();
        window.setTimeout(function() {
            document.body.innerHTML = globalHtml;
        }, 1500);
    }
    // 复制文本
    async function copyText() {
        if ( navigator.clipboard ) {
            await navigator.clipboard.readText()
        } else {
            document.execCommand('copy')
        }
    }
    // 粘贴文本
    async function pasteText() {
        if ( navigator.clipboard ) {
            await navigator.clipboard.writeText()
        } else {
           const result = document.execCommand('paste')
        }
    }
    // 百度搜索
    function baiduSearch() {
        var txt = window.getSelection ? window.getSelection() : document.selection.createRange().text;
        if ( txt != '' ) {
            window.open("https://www.baidu.com/s?wd=" + txt);
        } else {
            alert("啊噢~~~，你没还没选择文字呢！"); 
            return false;
        }
    }
    // 谷歌搜索
    function googleSearch() {
        var txt = window.getSelection ? window.getSelection() : document.selection.createRange().text;
        if ( txt != '' ) {
            window.open("https://www.google.com/search?q=" + txt)
        } else {
            alert("啊噢~~~，你没还没选择文字呢！");
            return false;
        }
    }
    
</script>

<style type="text/css">
    .right-click-menu{
        z-index: 999999999;
        display: none;
        position: absolute;
        width: 140px;
        height: auto;
        font-size: 1rem;
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
        line-height: 2.5rem;
        border-bottom: 1px solid #fff;
    }
    .right-click-menu ul li:last-child{
        border-bottom: 0;
    }
    .right-click-menu ul li a{
        text-decoration: none;
        display: block;
        padding: 0 1rem;
        color: #555;
    }
    .right-click-menu ul li a:hover{
        color: #fff;
        background: rgba(36, 160, 240, 0.6);
    }
    .right-click-menu ul li a i{
        margin: 0 5px 0 0;
    }
</style>