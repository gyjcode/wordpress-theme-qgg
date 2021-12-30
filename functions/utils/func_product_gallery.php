<?php
/**
 * 文章 # 产品相册
 */

add_action( 'add_meta_boxes', '_register_meta_box_product_gallery' );
// 注册 # 产品相册
function _register_meta_box_product_gallery(){
    add_meta_box( 'product_gallery_meta_box', __( '产品相册', 'QGG' ), '_add_meta_box_product_gallery', 'post', 'side', 'low' );
}

// 输出 # 产品相册
function _add_meta_box_product_gallery($post_id){
    $post_id = $_GET['post'] ?? '';

    $gallery_ids         = get_post_meta($post_id, 'product_gallery', true) ?: array();
    $update_meta         = false;
    $updated_gallery_ids = array();

    // 遍历生成 li 标签
    $li_html = '';
    if ( !empty( $gallery_ids ) ) {
        
        // 遍历数组获取图像
        foreach ($gallery_ids as $image_id) {
            // 根据 ID 获取图像
            $image = wp_get_attachment_image_src( $image_id );

            // 图像不存在更新数据库
            if ( empty( $image ) ) {
                $update_meta = true;
                continue;    // 跳过本次循环
            }

            $li_html .= '
            <li class="image" data-image_id="'.esc_attr( $image_id ).'">
                <img src="'.$image[0].'" />
                <div class="actions">
                    <a href="#" class="delete" title="'.esc_attr( '删除图像', 'QGG' ).'">&times</a>
                </div>
            </li>';

            // 重建需要保存的产品相册
		    $updated_gallery_ids[] = $image_id;
        }

        // 重新更新产品相册
        if ( $update_meta ) {
            update_post_meta( $post_id, 'product_gallery', implode( ',', $updated_gallery_ids ) );
        }
    }
    
    $html = '
    <!-- 相册内容 -->
    <div id="product_gallery_container">
        <!-- 用来存储相册内容 -->
        <ul class="product-images">'.$li_html.'</ul>
        <!-- 用来存储上传数据 -->
        <input type="hidden" id="product_gallery_data" name="product_gallery" value="'.esc_attr( implode( ',', $gallery_ids ) ).'" />

    </div>
    <p class="add-product-images">
        <a href="#" class="show-frame"
        data-title  = "'.esc_attr( '添加到产品相册', 'QGG' ).'"
        data-update = "'.esc_attr( '添加到相册', 'QGG' ).'"
        data-delete = "'.esc_attr( '从相册中删除', 'QGG' ).'"
        data-text   = "'.esc_attr( '删除', 'QGG' ).'">'.esc_html( '添加产品相册图片', 'QGG' ).'</a>
    </p>';
    
    echo $html;
}

// 添加相关样式|脚本代码
add_action('admin_footer', function(){
    ?>
    <style type="text/css">
        #product_gallery_meta_box .inside {
            margin-top: 0;
        }
        #product_gallery_container ul{
            margin: 0 auto;
            overflow: hidden;
        }
        #product_gallery_container li{
            float: left;
            cursor: move;
            position: relative;
            width: 77px;
            height: 77px;
            border: 1px solid #d5d5d5;
            margin: 7px 7px 0 0;
            background: #f7f7f7;
            border-radius: 2px;
        }
        #product_gallery_container .placeholder{
            width: 77px;
            height: 77px;
            border: 1px solid #d5d5d5;
            margin: 7px 7px 0 0;
        }
        #product_gallery_container li:nth-child(3n){
            margin-right: 0;
        }
        #product_gallery_container li img{
            width: 100%;
            height: 100%;
        }
        #product_gallery_container .actions{
            display: none;
        }
        #product_gallery_container li.image:hover .actions{
            display: block;
        }
        #product_gallery_container .actions a.delete{
            text-decoration: none;
            position: absolute;
            top: 0;
            right: 0;
            width: 14px;
            height: 14px;
            line-height: 10px;
            border: 2px solid #999;
            border-radius: 50%;
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            color: #333;
            background: #eee;
        }
        #product_gallery_container .actions a.delete:hover{
            color: #fff;
            background: #c62828;
        }
    </style>
    <script type="text/javascript">
        jQuery( function( $ ) {
            // 产品画册上传
            var product_gallery_frame;
            var $product_gallery_data = $( '#product_gallery_data' );
            var $product_images_box   = $( '#product_gallery_container' ).find( 'ul.product-images' );
            
            // 有 upload_files 权限媒体库选择图片文件
            $('body').on('click', '.add-product-images a.show-frame', function(event) {
                // 阻止事件默认行为
                event.preventDefault();
                // 获取当前点击对象
                let $el = $( this );

                // 存在产品图片上传窗体，直接打开
                if( product_gallery_frame ) {
                    product_gallery_frame.open();
                    return;
                }
                
                // 创建产品图片上传窗体
                product_gallery_frame = wp.media.frames.product_gallery = wp.media({
                    // 设置窗体标题
                    title:  $el.data('title'),
                    button: {
                        text:  $el.data('update')
                    },
                    states: [
                        new wp.media.controller.Library({
                            title:  $el.data('title'),
                            filterable: 'all',
                            multiple: true
                        })
                    ]
                });

                // 图片被选中，点击按钮后执行回调函数
                product_gallery_frame.on( 'select', function() {
                    var selection = product_gallery_frame.state().get( 'selection' );   // 获取选中的图片对象
                    var gallery_ids = $product_gallery_data.val();    // 获取原相册 ids

                    // 遍历新选中的图像
                    selection.map( function( image ) {
                        image = image.toJSON();

                        if ( image.id ) {
                            // 组成新相册
                            gallery_ids = gallery_ids ? gallery_ids + ',' + image.id : image.id;
                            // 获取图片 url
                            var image_src = image.sizes && image.sizes.thumbnail ? image.sizes.thumbnail.url : image.url;
                            // 添加前端显示
                            $product_images_box.append(
                                '<li class="image" data-image_id="' + image.id + '">\
                                    <img src="' + image_src + '" />\
                                    <div class="actions">\
                                        <a href="#" class="delete" title="' +  $el.data('delete') + '">&times</a>\
                                    </div>\
                                </li>'
                            );
                        }
                    });

                    // 重新赋值需上传的数据
                    $product_gallery_data.val( gallery_ids );
                });

                // 打开媒体选择窗体
                product_gallery_frame.open();
                
            });
            
            // 产品图片拖拽排序
            $product_images_box.sortable({
                items: 'li.image',
                cursor: 'move',
                scrollSensitivity: 40,
                forcePlaceholderSize: true,
                forceHelperSize: false,
                helper: 'clone',
                opacity: 0.65,
                placeholder: '#product_gallery_container .placeholder',
                start: function( event, ui ) {
                    ui.item.css( 'background-color', '#f6f6f6' );
                },
                stop: function( event, ui ) {
                    ui.item.removeAttr( 'style' );
                },
                update: function() {
                    // 重新生成上传数据
                    var gallery_ids = '';
                    $( '#product_gallery_container' ).find( 'ul li.image' ).each( function() {
                        var image_id = $(this).attr( 'data-image_id' );
                        gallery_ids = gallery_ids + ',' + image_id;
                    });

                    $product_gallery_data.val( gallery_ids );
                }
            });

            // 移除产品相册图片
            $('body').on('click', '#product_gallery_container a.delete', function() {
                $(this).closest( 'li.image' ).remove();

                // 重新生成上传数据
                var gallery_ids = '';
                $( '#product_gallery_container' ).find( 'ul li.image' ).each( function() {
                    var image_id = $(this).attr( 'data-image_id' );
                    gallery_ids = gallery_ids + ',' + image_id;
                });
                console.log(gallery_ids)
                $product_gallery_data.val( gallery_ids );

                return false;
            });

        })
            
    </script>
    <?php
});

// 存储|更新 Meta Box 数据
add_action('save_post', '_sav_meta_boxe_product_gallery');
function _sav_meta_boxe_product_gallery( $post_id ){
    if(!isset($_POST['post_ID'])) return;
    $post_id = $_POST['post_ID'];
    
    if ( !current_user_can( 'edit_posts', $post_id ))
        return;
    
    $gallery_ids = isset( $_POST['product_gallery'] ) ? array_filter( explode( ',', sanitize_text_field( $_POST['product_gallery'] ) ) ) : array();
    $gallery_ids = wp_parse_id_list($gallery_ids);
    
    $gallery_ids_old = get_post_meta($post_id, 'product_gallery', true);
    if( $gallery_ids_old  == ""){
        add_post_meta($post_id, 'product_gallery', $gallery_ids, true);
    }elseif($gallery_ids != $gallery_ids_old ){
        update_post_meta($post_id, 'product_gallery', $gallery_ids);
    }elseif($gallery_ids == ""){
        delete_post_meta($post_id, 'product_gallery', $gallery_ids_old );
    }
}

// 获取当前文章产品图册
function _get_the_post_product_gallery($before = '', $after = '') {
    global $post;
    $post_ID = $post->ID;
    $gallery_ids = get_post_meta($post_ID, 'product_gallery', true) ?: array();

    // 遍历生成 li 标签
    $content_html = '';
    $tab_html = '';
    $i = 0;
    if ( !empty( $gallery_ids ) ) {
        // 遍历数组获取图像
        foreach ($gallery_ids as $image_id) {
            $i++;
            // 根据 ID 获取图像
            $image = wp_get_attachment_image_src( $image_id, 'full' );
            // 内容区图像
            $content_html .= '
            <div class="pic" data-image_id="'.esc_attr( $image_id ).'" data-index="'.$i.'">
                <img src="'.$image[0].'" />
            </div>';
            // Tab 选项卡图像
            $tab_html .= '
            <li class="tab" data-image_id="'.esc_attr( $image_id ).'" data-index="'.$i.'">
                <img src="'.$image[0].'" />
            </li>';
        }
    }

    // HTML
    $html =  $before .'
        <div class="product-gallery-container">
            <div class="content-wrapper">
                <div class="content">'.$content_html.'</div>
            </div>
            <div class="navigator" data-cur_index="1">
                <span class="icon prev"></span>
                <div class="tabs-wrapper">
                    <ul class="tabs">'.$tab_html.'</ul>
                </div>
                <span class="icon next"></span>
            </div>
        </div>
        '. $after;

    // CSS
    $css = '
    <style type="text/css">
        .product-gallery-container{
            position: relative;
            width: 380px;
            height: 300px;
            overflow: hidden;
        }
        .product-gallery-container .content-wrapper{
            position: relative;
            width: 100%;
            height: calc(100% - 50px);
            overflow: hidden;
        }
        .product-gallery-container .content{
            transition-duration: 1.5s;
            left: 0;
            width: 100%;
            height: 100%;
            margin: 0 auto;
            padding: 0;
            font-size: 0;    /* 解决 inline-block 间隙 */
            -webkit-text-size-adjust: none;
            white-space: nowrap;
        }
        .product-gallery-container .content .pic{
            display: inline-block;
            width: 100%;
            height: 100%;
        }
        .product-gallery-container .content img{
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
        .product-gallery-container .navigator{
            position: relative;
            width: 100%;
            height: 50px;
            overflow: hidden;
        }
        .product-gallery-container .navigator .icon{
            cursor: pointer;
            position: absolute;
            top: 17px;
            width: 20px;
            height: 20px;
            margin: 0 10px;
            border: solid #999;
            border-width: 0 5px 5px 0;
            padding: 3px;
        }
        .product-gallery-container .navigator .icon:hover{
            opacity: 0.8;
        }
        .product-gallery-container .navigator .prev{
            left: 0;
            transform: rotate(135deg);
            -webkit-transform: rotate(135deg);
        }
        .product-gallery-container .navigator .next{
            right: 0;
            transform: rotate(-45deg);
            -webkit-transform: rotate(-45deg);
        }
        .product-gallery-container .tabs-wrapper{
            position: relative;
            margin: 5px 35px;
            overflow: hidden;
        }
        .product-gallery-container .tabs{
            list-style: none;
            position: relative;
            transition-duration: 1.5s;
            left: 0;
            height: 100%;
            width: auto;
            margin: 0;
            padding: 0;
            font-size: 0;
            white-space: nowrap;
        }
        .product-gallery-container .tabs li{
            display: inline-block;
            width: 40px;
            height: 40px;
            margin: 0 auto;
            margin-right: 5px;
        }
        .product-gallery-container .tabs li:hover{
            border: 3px solid #d32f2f;
        }
        .product-gallery-container .tabs img{
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
    </style>';

    // JS
    $js = '
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
    jQuery(document).ready(function(){
        let content   = $(".product-gallery-container .content");
        let navigator = $(".product-gallery-container .navigator");
        let tabs      = $(".product-gallery-container .tabs");
        let tab       = $(".product-gallery-container .tab");

        let conWidth  = content.width();
        let tabWidth  = tab.width() + 5;
        let length    = content.children(".pic").length;
        let curIndex  = navigator.data("cur_index");

        // 点击 Tab 切换图片
        navigator.on("click", ".tab", function(){
            curIndex = $(this).data("index");
            navigator.attr( "data-cur_index", curIndex);

            content.css("left", -(curIndex-1)*conWidth);

            let tabLeft = -(curIndex-3)*tabWidth;
            if(curIndex <= 2) tabLeft = 0;
            tabs.css("left", tabLeft);
        })

        // 点击 Nex 切换图片
        navigator.on("click", ".next", function(){
            if ( curIndex >= length ) return;
            curIndex++;
            navigator.attr( "data-cur_index", curIndex);

            content.css("left", -(curIndex-1)*conWidth);

            let tabLeft = -(curIndex-3)*tabWidth;
            if(curIndex <= 2) tabLeft = 0;
            tabs.css("left", tabLeft);
        })

        // 点击 Prev 切换图片
        navigator.on("click", ".prev", function(){
            if ( curIndex <= 1 ) return;
            curIndex--;
            navigator.attr( "data-cur_index", curIndex);

            content.css("left", -(curIndex-1)*conWidth);

            let tabLeft = -(curIndex-3)*tabWidth;
            if(curIndex <= 2) tabLeft = 0;
            tabs.css("left", tabLeft);
        })
    })
    </script>';

    return $html.$css.$js;
}
