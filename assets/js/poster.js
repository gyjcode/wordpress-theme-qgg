/*! @overbool/poster v1.0.1 | (c) 2019 Overbool | https://github.com/overbool/poster */


define(function (){
    "use strict";
    // post class
    var poster = function (){
        var DEBUG = false;
        var WIDTH = 600;
        var HEIGHT = 1000;
        function init(config){    // 传入的外部配置对象
            // 获取组成海报的文本元素
            var $container = document.querySelector(config.selector);
            var $wrapper   = createDom('div', 'id', 'wrapper');
            var $canvas    = createDom('canvas', 'id', 'canvas', 'block');
            var $day       = createDom('canvas', 'id', 'day');
            var $date      = createDom('canvas', 'id', 'date');
            
            var $title     = createDom('canvas', 'id', 'title');
            var $content   = createDom('canvas', 'id', 'content');
            var $postmeta  = createDom('canvas', 'id', 'postmeta');
            var $sitename  = createDom('canvas', 'id', 'sitename');
            var $slogan    = createDom('canvas', 'id', 'slogan');
            // 组合各海报文本元素
            appendChilds($wrapper, $canvas, $day, $date, $title, $content, $postmeta, $sitename, $slogan);
            $container ? $container.appendChild($wrapper) : "";
            
            // 特色图像
            var $banner = new Image();
            $banner.setAttribute("crossOrigin",'Anonymous');
            $banner.src = config.banner;
            // Logo 图标
            var $logo = new Image();
            $logo.setAttribute("crossOrigin",'Anonymous');
            $logo.src = config.logo;
            // 当前日期
            var date = new Date(); 
            // 日
            var dayStyle = {
                font: 'italic bold 80px Arial',
                color: 'rgba(255, 255, 255, 1)',
                position: 'left'
            };
            var format_day = ("0" + date.getDate()).slice(-2);
            drawOneline($day, dayStyle, format_day); 
            // 年月
            var dateStyle = {
                font: 'normal 30px Arial',
                color: 'rgba(255, 255, 255, 1)',
                position: 'left'
            };
            var format_date = date.getFullYear() + ' / ' + ("0" + (date.getMonth() + 1)).slice(-2) + ' / ';
            drawOneline($date, dateStyle, format_date); 
            // 文章标题
            var titleStyle = {
                font     : 'normal 36px 微软雅黑',
                color    : 'rgba(66, 66, 66, 1)',
                position : 'left',
            };
            titleStyle.font     = config.titleStyle && config.titleStyle.font || titleStyle.font;
            titleStyle.color    = config.titleStyle && config.titleStyle.color || titleStyle.color;
            titleStyle.position = config.titleStyle && config.titleStyle.position || titleStyle.position;
            drawOneline($title, titleStyle, config.title); 
            // 文章摘要
            var contentStyle = {
                font       : 'normal 24px 微软雅黑',
                color      : 'rgba(99, 99, 99, 1)',
                position   : 'left',
                lineHeight : 1.25,    // 多行文本,用于计算行数
                maxHeight  : 150,    // 多行文本,用于计算行数
            };
            contentStyle.font       = config.contentStyle && config.contentStyle.font || contentStyle.font;
            contentStyle.color      = config.contentStyle && config.contentStyle.color || contentStyle.color;
            contentStyle.position   = config.contentStyle && config.contentStyle.position || contentStyle.position;
            contentStyle.lineHeight = config.contentStyle && config.contentStyle.lineHeight || contentStyle.lineHeight;
            contentStyle.maxHeight  = config.contentStyle && config.contentStyle.maxHeight || contentStyle.maxHeight;
            drawMoreLines($content, contentStyle, config.content); 
            // 文章Meta
            var postmetaStyle = {
                font       : 'normal 24px 微软雅黑',
                color      : 'rgba(66, 200, 120, 1)',
                position   : 'left',
                lineHeight : 1.25,    // 多行文本,用于计算行数
                maxHeight  : 60,     // 多行文本,用于计算行数
            };
            postmetaStyle.font       = config.postmetaStyle && config.postmetaStyle.font || postmetaStyle.font;
            postmetaStyle.color      = config.postmetaStyle && config.postmetaStyle.color || postmetaStyle.color;
            postmetaStyle.position   = config.postmetaStyle && config.postmetaStyle.position || postmetaStyle.position;
            postmetaStyle.lineHeight = config.postmetaStyle && config.postmetaStyle.lineHeight || postmetaStyle.lineHeight;
            postmetaStyle.maxHeight  = config.postmetaStyle && config.postmetaStyle.maxHeight || postmetaStyle.maxHeight;
            drawMoreLines($postmeta, postmetaStyle, config.postmeta);
            
            //二维码图片
            var $qrcode = new Image();
            $qrcode.src = config.qrcode;
            // 站点ICON
            var $siteicon = new Image();
            $siteicon.setAttribute("crossOrigin",'Anonymous');
            $siteicon.src = config.siteicon;
            // 站点标题
            var sitenameStyle = {
                font     : 'normal 36px 微软雅黑',
                color    : 'rgba(66, 66, 66, 1)',
                position : 'left'
            };
            sitenameStyle.font     = config.sitenameStyle && config.sitenameStyle.font || sitenameStyle.font;
            sitenameStyle.color    = config.sitenameStyle && config.sitenameStyle.color || sitenameStyle.color;
            sitenameStyle.position = config.sitenameStyle && config.sitenameStyle.position || sitenameStyle.position;
            drawOneline($sitename, sitenameStyle, config.sitename); 
            // 宣传标语
            var sloganStyle = {
                font: 'normal 24px 微软雅黑',
                color: 'rgba(99, 99, 99, 1)',
                position: 'left'
            };
            sloganStyle.font     = config.sloganStyle && config.sloganStyle.font || sloganStyle.font;
            sloganStyle.color    = config.sloganStyle && config.sloganStyle.color || sloganStyle.color;
            sloganStyle.position = config.sloganStyle && config.sloganStyle.position || sloganStyle.position;
            drawOneline($slogan, sloganStyle, config.slogan); 
                        
            // 生成海报图片
            var onload = function onload(){
                $canvas.width = WIDTH;
                $canvas.height = HEIGHT;
                
                var index = 0,
                    allImg = [$logo, $siteicon, $qrcode];
                var totalNum = allImg.length;
                for(var i = 0 ; i < totalNum ; i++){
                    allImg[i].onload=function (){
                        index ++;      // 做个循环,避免有图片未加载
                        if(index >= totalNum){
                            drawMyPoster();
                        }
                    }
                }
                // 绘制海报
                function drawMyPoster(){
                    var ctx = $canvas.getContext('2d');
                    
                    ctx.fillStyle = 'rgba(255, 255, 255, 1)';
                    ctx.fillRect(0, 0, $canvas.width, $canvas.height);
                    // 绘制 Banner 图片  context.drawImage(img,x,y,width,height);
                    ctx.drawImage($banner, 0, 0, $canvas.width, 320);
                    // 绘制灰色遮罩
                    ctx.fillStyle="#00000036";    
                    ctx.globalCompositeOperation="source-over";
                    ctx.fillRect(0, 0, $canvas.width, 320);
                    // 绘制 Logo 图片
                    ctx.drawImage($logo, $canvas.width - 295, 15, 280, 64);
                    // 绘制当前日期
                    ctx.drawImage($day, 165, 205);
                    ctx.drawImage($date, 5, 245);
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = '#fff';
                    ctx.moveTo(15, 290);
                    ctx.lineTo(175, 290);
                    ctx.stroke(); 
                    ctx.beginPath();
                    ctx.lineWidth = 5;
                    ctx.strokeStyle = '#fff';
                    ctx.moveTo(15, 300);
                    ctx.lineTo(275, 300);
                    ctx.stroke(); 
                    
                    // 绘制文章内容
                    ctx.drawImage($title, 20, 340);
                    ctx.drawImage($content, 20, 420);
                    ctx.drawImage($postmeta, 20, 600);
                    
                    // 绘制装饰线条
                    ctx.beginPath();
                    ctx.lineWidth = 3;
                    ctx.strokeStyle = '#eee';
                    ctx.moveTo(0, 700);
                    ctx.lineTo(600, 700);
                    ctx.stroke();
                    // 绘制二维码图片
                    ctx.drawImage($qrcode, 40, 740,200,200);
                    // 绘制站点ICON
                    ctx.drawImage($siteicon, 380, 750, 80, 80);
                    // 绘制站点名称
                    ctx.drawImage($sitename, 340, 840,);
                    // 绘制站点标语
                    ctx.drawImage($slogan, 310, 890,);
                    
                    var img = new Image();
                    img.src = $canvas.toDataURL('image/png');
                    var radio = config.radio || 0.8;
                    img.width = WIDTH * radio;
                    img.height = HEIGHT * radio;
                    ctx.clearRect(0, 0, $canvas.width, $canvas.height);
                    $canvas.style.display = 'none';
                    $container ? $container.appendChild(img) : "";
                    $container ? $container.removeChild($wrapper) : "";
                if (config.callback) {
                    config.callback($container);
                }
            };
            };
            onload();
        }
        
        //生成 DOM 元素
        function createDom(name, key, value) {
            var display = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 'none';          // 第四个参数设置 display,默认为 none
            var $dom = document.createElement(name);          // 创建DOM元素 document.createElement(nodename)
            $dom.setAttribute(key, value);          // 设置元素属性 element.setAttribute(attributename,attributevalue)
            $dom.style.display = display;
            $dom.width = WIDTH;
            return $dom;
        }
        // 组装 DOM 元素
        function appendChilds(parent) {
            for (var _len = arguments.length, doms = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
                doms[_key - 1] = arguments[_key];
            }

            doms.forEach(function (dom) {
                parent.appendChild(dom);
            });
        }
        // 绘制单行文本
        function drawOneline(canvas, style, content) {
            var ctx = canvas.getContext('2d');
            canvas.height = parseInt(style.font.match(/\d+/), 10) + 20;
            ctx.font = style.font;
            ctx.fillStyle = style.color;
            ctx.textBaseline = 'top';
            var lineWidth = 0;
            var idx = 0;
            var truncated = false;
            for (var i = 0; i < content.length; i++) {
                lineWidth += ctx.measureText(content[i]).width;
                if (lineWidth > canvas.width - 60) {
                    truncated = true;
                    idx = i;
                    break;
                }
            }
            var padding = 10;
            if (truncated) {
                content = content.substring(0, idx);
                padding = canvas.width / 2 - lineWidth / 2;
            }
            if (DEBUG) {
                ctx.strokeStyle = "#6fda92";
                ctx.strokeRect(0, 0, canvas.width, canvas.height);
            }
            if (style.position === 'center') {
                ctx.textAlign = 'center';
                ctx.fillText(content, canvas.width / 2, padding);
            } else if (style.position === 'left') {
                ctx.textAlign = 'left';
                ctx.fillText(content, padding, padding);
            } else {
                ctx.textAlign = 'right';
                ctx.fillText(content, canvas.width - padding, padding);
            }
        }
        // 绘制多行文本
        function drawMoreLines(canvas, style, content) {
            var ctx = canvas.getContext('2d');
            var fontHeight = parseInt(style.font.match(/\d+/), 10);
            canvas.height = style.maxHeight ? style.maxHeight : 150;
            if (DEBUG) {
                ctx.strokeStyle = "#6fda92";
                ctx.strokeRect(0, 0, canvas.width, canvas.height + 10);
            }
            ctx.font         = style.font;
            ctx.fillStyle    = style.color;
            ctx.textBaseline = 'top';
            ctx.textAlign    = 'left';
            var alignX = 0;
            if (style.position === 'center') {
                alignX = canvas.width / 2;
            } else if (style.position === 'left') {
                ctx.textAlign = 'left';
                alignX = 0;
            } else {
                ctx.textAlign = 'right';
                alignX = canvas.width;
            }
            var lineWidth = 0;
            var lastSubStrIndex = 0;
            var offsetY = 5;
            for (var i = 0; i < content.length; i++) {
                // 累加字体长度（px）
                lineWidth += ctx.measureText(content[i]).width;
                // 字体长度满一行后绘制
                if ( lineWidth > canvas.width - 60 ) {
                    ctx.fillText(content.substring(lastSubStrIndex, i), alignX, offsetY);
                    offsetY += fontHeight * style.lineHeight;
                    lineWidth = 0;
                    lastSubStrIndex = i;
                }
                // 字体长度不足一行时绘制
                if (i === content.length - 1) {
                    ctx.fillText(content.substring(lastSubStrIndex, i + 1), alignX, offsetY);
                }
            }
        }

        return {
            init: init
        };
    }();
    return poster;
})