<?php 
/**
 * @name: 视频播放器
 * @description: 短代码插入视频列表
 */


 class _VideoPlayer {

    /**
     * 构造函数 # 初始化
     * add_shortcode: https://developer.wordpress.org/reference/functions/add_shortcode/
     * add_action: https://developer.wordpress.org/reference/functions/add_action/
     */
    public function __construct(
        $jxSources = array(),
        $picture   = '',
        $height    = 500,
        $height_m  = 300
    ){
        $this->jxSources = array_merge( array(
            array(
                "id"   => "iframe",
                "name" => "不解析IFrame播放",
                "type" => "iframe",
                "api"  => ""
            ),
            array(
                "id"   => "dplayer",
                "name" => "不解析Dplayer播放",
                "type" => "dplayer",
                "api"  => ""
            ),
        ), $jxSources );
        $this->poster = isset($poster) ? $poster : get_template_directory_uri().'/assets/img/video-poster.png';
        $this->height = wp_is_mobile() ? $height_m : $height;

        /**
         * TinyMCE 编辑器添加功能
         * mce_buttons: https://developer.wordpress.org/reference/hooks/mce_buttons/
         * mce_external_plugins: https://developer.wordpress.org/reference/hooks/mce_external_plugins/
         * admin_action_{$action}: https://developer.wordpress.org/reference/hooks/admin_action_action/
         */
        add_filter('mce_buttons', array($this, '_register_tinymce_buttons_videoplayer'), 999);
        add_filter("mce_external_plugins", array($this, "_add_tinymce_buttons_videoplayer"), 999);
        add_action('admin_action_videoplayer', array($this, '_tinymce_buttons_videoplayer_window'));
        add_action('admin_print_footer_scripts',  array($this, '_add_qtags_button_videoplayer'));
		add_shortcode('VideoPlayer', array($this, '_shortcode_video_player'));  //register shortcode
	}

    /**
     * 文章编辑器添加按钮
     */
    // 注册 tinyMCE 按钮
    public function _register_tinymce_buttons_videoplayer( $buttons ){
        array_push($buttons, "|", "_videoplayer");
		return $buttons;
    }

    // 添加 tinyMCE 按钮
    public function _add_tinymce_buttons_videoplayer( $plugin_array ){
        $plugin_array['_videoplayer'] = get_template_directory_uri().'/assets/js/tinymce.editor.js';
        return $plugin_array;
    }

    // tinyMCE 按钮弹窗
    public function _tinymce_buttons_videoplayer_window(){
        $sources = '';
		if(is_array($this->jxSources)){
			foreach($this->jxSources as $key=>$value){
				$sources .= '<option value="'.$value['id'].'">'.$value['name'].'</option>';
			}
		}

        // HTML
        $html = '
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <base target="_self"/>
                <title>视频播放器</title>
                <script type="text/javascript" src="'.get_option('siteurl').'/wp-includes/js/tinymce/tiny_mce_popup.js?ver='.$GLOBALS['wp_version'].'"></script>
            </head>
            <body>
                <div id="video-player" class="video-player-container">
                    <div class="sources">
                        <label class="header">视频来源</label>
                        <div class="content">
                            <select id="video-sources" class="video-sources" name="video-sources">
                            '. $sources.'
                            </select>
                        </div>
                    </div>
                    <div class="urls">
                       <label class="header">视频链接</label>
                       <div class="content">
                           <textarea id="video-urls" class="video-urls" type="text" name="video-urls" placeholder="请填写视频链接 一行一条数据"></textarea>
                       </div>
                    </div>
                    <div class="submit">
                        <div class="content">
                            <button id="video-url-check" class="video-url-check" onclick="videoUrlCheck()">校正链接</button>
                            <button id="videos-submit" class="videos-submit" onclick="insertVideoPlayerShortcode()">添加视频</button>
                        </div>
                    </div>
                </div>
            </body>
        </html>';
        echo $html;

        // CSS
        $css = '
        <style type="text/css">
            .video-player-container{
                padding: 20px;
            }
            .video-player-container>div{
                margin: 20px auto;
            }
            .video-player-container .header{
                float: left;
                display:block;
                width: 100px;
                line-height: 30px;
                font-size: 15px;
                font-weight: bold;
                text-align: left;
            }
            .video-player-container .content{
                margin-left: 100px;
                font-size: 15px;
                font-weight: bold;
                text-align: left;
            }
            .video-player-container .video-sources{
                outline: none;
                width: 200px;
                height: 30px;
            }
            .video-player-container .video-urls{
                outline: none;
                width: 100%;
                min-height: 200px;
                padding: 10px;
                overflow-x: hidden;
                overflow-y: auto;
            }
            .video-player-container button{
                cursor: pointer;
                outline: none;
                width: 100px;
                height: 30px;
                line-height: 30px;
                margin-right: 15px;
                border: 0;
                color: #fff;
                background: #24a0f0;
            }
            .video-player-container button:hover{
                opacity: 0.8;
            }
            .video-player-container .video-url-check{
                background: green;
            }
        </style>';
        echo $css;

        // JavaScript
        $script = '
        <script type="text/javascript">
            // 获取已插入视频
            function getEditedVideoUrl(){
                var tinyMCEContent = tinyMCE.activeEditor.getContent();
                
                let oldUrls = "";
                let urlsRegExp = new RegExp(\'vUrls="([^"]*)\');
                // 使用正则获取链接
                if( urlsRegExp.test(tinyMCEContent) ){
                    oldUrls = urlsRegExp.exec(tinyMCEContent)[1]
                } else {
                    urlsRegExp = new RegExp(\'vUrls=&quot;([^&]*)\');
                    oldUrls = urlsRegExp.test(tinyMCEContent) ? urlsRegExp.exec(tinyMCEContent)[1] : "";
                }
                // 填入弹窗
                domUrls = document.getElementById("video-urls");
                domUrls.value = oldUrls.replace(new RegExp(/(,)/g),\'\n\');
            }
            getEditedVideoUrl();  // 立即执行

            // 校正 URL
            function videoUrlCheck(){
                let strUrls, arrUrls, newArrUrls;
                domUrls = document.getElementById("video-urls");
                strUrls = domUrls.value;
                strUrls = strUrls.replace("\r","");
                arrUrls = strUrls.split("\n");

                let num = 0;
                let newStrUrls="";
                for (i=0; i<arrUrls.length; i++) {
                    if(arrUrls[i].length>0){
                        num++;
                        newArrUrls = arrUrls[i].split("$");
                        if (newArrUrls.length - 1 == 0) {
                            arrUrls[i] = "第" + (num<10 ? "0" : "") + num + "集$" + arrUrls[i]
                        }
                        newStrUrls = newStrUrls + arrUrls[i] + "\r\n";
                    }
                }
                domUrls.value = newStrUrls.trim();
            }

            // 插入短代码
            function insertVideoPlayerShortcode() {
                let vUrls = document.getElementById("video-urls").value;
                let jxID = document.getElementById("video-sources").value;
                if(vUrls.trim() == "") return;    // 未输入url 直接返回

                vUrls = vUrls.replace(/\r|\n/g, ",");
                let shortcode = "[VideoPlayer jxID=\""+jxID+"\" vUrls=\""+vUrls+"\"][/VideoPlayer]";
                
                window.tinyMCE.activeEditor.insertContent(shortcode);
                tinyMCEPopup.editor.execCommand("mceRepaint");
                tinyMCEPopup.close();
                return;
            }
        </script>';
        echo $script;
        
    }

    // 添加 QTags 按钮
    public function _add_qtags_button_videoplayer() {
        ?>
        <script type="text/javascript">
            if ( typeof QTags != 'undefined' ) {
                QTags.addButton( 'videoplayer', '分集视频', '[VideoPlayer jxID="解析来源数组的 ID，默认：default(不解析)" vUrls="视频链接地址，以英文逗号分割"]','[/VideoPlayer]\n' );
            }
        </script>
        <?php 
    }
    
    /**
     * 前端显示短代码内容
     */
    // 短代码 callback # 前端显示返回
    public function _shortcode_video_player($atts, $content=null){
        
        // 默认值：shortcode_atts: https://developer.wordpress.org/reference/functions/shortcode_atts/
        $atts = shortcode_atts(array('jxid'=>'iframe', 'vurls'=>''), $atts);

        // 文章编辑页 退出
        global $pagenow;
		if($pagenow == 'post.php') return false;
        // 视频链接为空退出
        if(!$atts['vurls']) return '视频ID/URL不能为空';
        
        // 随机数，避免多个视频列表冲突
        $randID = rand(1000, 99999);

        // 短代码参数 # jxID
        $jxID = $atts['jxid'];    // 解析来源 ID，默认：default(不解析)

        // 解析参数
        $jxAPI = "";    // 默认不解析(访问视频真实地址)
        $jxType = "iframe";    // 默认使用 iframe
        $jxName = "IFrame";
        $jxSources = $this->jxSources;
        if( is_array($jxSources) ){
			foreach($jxSources as $jxSource){
				if($jxID == $jxSource["id"]){
					$jxAPI  = $jxSource["api"];
					$jxType = $jxSource["type"];
					$jxName = $jxSource["name"];
				}
			}
		}
        
        // 短代码参数 # vurls
		$vurls = $atts['vurls'];    // 需要解析的 URLs

        // 解析视频链接
        $arrUrls = explode(',', $vurls);    // urls 字符串解析为数组，英文","分割的
        $player_list = "";   // 存储视频列表
        $arrVideoList = array();    // 存储解析后的视频数组
        for ($i=0; $i<count($arrUrls); $i++){
            // 显示集数
            $arrUrl = explode('$', $arrUrls[$i]);
            
            if ( !isset($arrUrl[1]) ) {
                $arrUrl[1]= $arrUrl[0];
                $arrUrl[0]='第'.(intval($i)<10 ? '0' : '').($i+1).'集';
            }
            $arrUrl[1] = $jxAPI.$arrUrl[1];
            $player_list .= '
            <li class="'.($i == 0 ? 'active' : '').'">
                <a href="javascript:void(0);" data-url="'.$arrUrl[1].'" onclick="Player_'.$randID.'.Go('.$i.');">第'.($i+1).'集</a>
            </li>';
            //var_dump($arrUrl[1]);
            // 包含解析地址的完整视频列表
            $arrVideoList[] = array('id'=>($i+1), 'title'=>$arrUrl[0], 'url'=>html_entity_decode($arrUrl[1]));
        }

        // 判断解析方式(使用 iframe 框架还是 video 标签显示)
        $player_content = "";
        if( $jxType == 'iframe' ){
            // IFrame 框架 HTML
            $player_content = "<iframe id='video-player_".$randID."' src='".$arrVideoList[0]['url']."' width='100%' height='".$this->height."'></iframe>";
        } elseif ( $jxType == 'dplayer' ) {
            // DPlayer 框架 HTML
            $player_content = "<div id='video-player_".$randID."'></div>";
        }

        // 播放器代码
        // HTML
        $player_html = '
        <div id="video-player-container_'.$randID.'" class="video-player-container">
            <!-- 参数存储 -->
            <input type="hidden" id="video-player-input_'.$randID.'" jxType="'.$jxType.'" curVideoUrl="'.$arrVideoList[0]['url'].'"/>
            <!-- 播放器 -->
            <div class="player-wrapper">
                <!-- 播放器 # 头部信息 -->
                <div class="header">
                
                </div>

                <!-- 播放器 # 主体内容 -->
                <div class="player" id="video-player-body_'.$randID.'">'.$player_content.'</div>
            </div>
            <!-- 播放列表 -->
            <div class="list-wrapper">
                <div class="source"><span>'.$jxName.'</span></div>
                <ul id="video-player-list_'.$randID.'">'.$player_list.'</ul>
            </div>
        </div>';

        // CSS
        $player_css = '
        <style type="text/css">
            .video-player-container{
                cursor: default;
                position: relative;
                text-indent: 0;
            }
            /* 播放器 */
            .video-player-container .player-wrapper{
                width: 100%;
                height: auto;
            }
            /* 视频列表 */
            .video-player-container .list-wrapper{
                width: 100%;
                height: auto;
                margin-top: 1rem;
                overflow:hidden;
            }
            .video-player-container .list-wrapper .source{
                width: 100%;
                height: 32px;
                line-height: 20px;
                padding: 3px 7px;
                border-bottom: 2px solid #24a0f0;
            }
            .video-player-container .list-wrapper .source span{
                height: 100%;
            }
            .video-player-container .list-wrapper ul{
                display: flex;
                flex-wrap: wrap;
            }
            .video-player-container .list-wrapper li{
                display: inline-block;
                flex-basic: 80px;
                width: 80px;
                height: 32px;
                line-height: 30px;
                margin-bottom: 15px;
                border: 1px solid #eee;
                border-radius: 3px;
                text-align: center;
                font-size: 0.8rem;
                color: #333;
                background: #fff;
            }
            .video-player-container .list-wrapper li:hover{
                cursor: pointer;
                color: #fff;
                background: #555;
            }
            .video-player-container .list-wrapper li.active{
                cursor: pointer;
                color: #fff;
                background: #555;
            }
            .video-player-container .list-wrapper li a{
                text-decoration: none;
                display: block;
                width: 100%;
                height: 100%;
                color: inherit;
            }
        </style>';

        // JavaScript
        $dplayer_js = "";
        if ( $jxType == 'dplayer' ) {
            // DPlayer 框架 JS
            $dplayer_js = '
            <link href="https://cdn.bootcdn.net/ajax/libs/dplayer/1.24.0/DPlayer.min.css" rel="stylesheet">
            <script src="https://cdn.bootcdn.net/ajax/libs/dplayer/1.24.0/DPlayer.min.js"></script>
            
            <script type="text/javascript">
                const dplayer_'.$randID.' = new DPlayer({
                    container: document.getElementById("video-player_'.$randID.'"),
                    video: {
                        url: "'.$arrVideoList[0]['url'].'",
                        pic: "'.$this->poster.'",
                    },
                });
            </script>';
        }

        $player_js = $dplayer_js.'
        <script type="text/javascript">
            // 定义类
            // 注意错误：Uncaught SyntaxError: Identifier "VideoPlayer" has already been declared
            var VideoPlayer = class {
                constructor($rid, $type, $videos) {
                    this.$rid    = $rid;
                    this.$type   = $type;
                    this.$videos = $videos;
                }
                // 切换集数
                Go ($index) {

                    const $domContent = document.getElementById("video-player_"+this.$rid);
                    const $videoUrl   = this.$videos[$index].url;
                    const event = window.event || event;
                    
                    // 切换按钮激活状态
                    event.target.parentNode.parentNode.childNodes.forEach(function(ele){
                        if(ele.nodeName  != "#text"){
                            ele.classList.remove("active");
                        }
                    });
                    event.target.parentNode.classList.add("active");
                   
                    if(this.$type == "iframe"){
                        $domContent.setAttribute("src", $videoUrl);
                    } else if ( this.$type == "dplayer" ) {
                            eval("dplayer_"+this.$rid).switchVideo(
                                {
                                    url: $videoUrl,
                                },
                            )
                            eval("dplayer_"+this.$rid).play()
                    } else {
                        alert("解析类型错误，解析失败！")
                    }
                    }
            };
            
           var Player_'.$randID.' = new VideoPlayer('.$randID.', "'.$jxType.'", '.json_encode($arrVideoList).');

            
        </script>';
        
        $result = $player_html.$player_css.$player_js;
		return $result;
    }

 }


