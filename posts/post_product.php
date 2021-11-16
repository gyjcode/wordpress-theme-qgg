<?php
/**
  * WP Post Template: 产品文章
  */
get_header();
?>
<div class="container  product-container">
    <!-- 基础信息 -->
    <section class="container module product-info-wrapper site-style-border-radius">
        <div class="gallery">
            <?php echo _get_the_post_product_gallery(); ?>
        </div>
        <div class="info">
            <div class="title">
                <a href="<?php the_permalink(); ?>"><h1><?php the_title(); ?></h1></a>
                <h2 class="sub-title"><?php echo _get_the_post_subtitle(); ?></h2>
            </div>
            <?php
            echo '<div class="metas">';
                // 价格参数 
                $price           = _get_the_product_meta("product_price") ?: null;
                $sale_price      = _get_the_product_meta("product_sale_price") ?: null;
                $sale_date_from  = _get_the_product_meta("product_sale_price_date_from") ?: null;
                $sale_date_to    = _get_the_product_meta("product_sale_price_date_to") ?: null;
                $lost_days       = (strtotime(date("Y-m-d")) - strtotime($sale_date_from) )/(24*60*60);
                $sale_price_show = $sale_price && $sale_date_from && $sale_date_to && ($lost_days <= 7);
                $line_through    = $sale_price_show ? "line-through" : "none";
                // 原价
                if( $price ){
                    echo '
                    <p class="meta price">
                        <label>商品售价</label>
                        <span style="text-decoration: '.$line_through.';">&#165;'.number_format($price, 2).'</span>
                    </p>';
                }
                // 售价
                if( $sale_price_show ){
                    echo '
                    <p class="meta price sale-price" data-date_from="'.$sale_date_from.'" data-date_to="'.$sale_date_to.'">
                        <label>限时特价</label>
                        <span>&#165;<strong>'.number_format($sale_price, 2).'</strong></span>
                        <i class="sale-info"></i>
                    </p>
                    <script type="text/javascript">
                    jQuery(document).ready(function(){
                        window.saleTimeLimit = function (){
                            window.setTimeout("saleTimeLimit()", 1000);
                            const date_from = $(".product-info-wrapper .sale-price").data("date_from");
                            const date_to   = $(".product-info-wrapper .sale-price").data("date_to");
                            
                            const time_from = new Date(date_from + " 00:00:00");
                            const time_to   = new Date(date_to + " 23:59:59");
                            const time_cur  = new Date();

                            let duration   = 0;
                            let msg_before = "";
                            if (time_cur < time_from){
                                duration = time_from - time_cur;
                                msg_before = "距开始";
                            } else if (time_cur < time_to){
                                duration = time_to - time_cur;
                                msg_before = "距结束";
                            } else {
                                duration = 0;
                                msg_before = "活动已过期";
                            }
                            // 计算时间
                            d = duration / (24*60*60*1000);
                            Day = Math.floor(d);  // 天
                            h = (d-Day)*24;
                            Hour = Math.floor(h); // 时
                            m = (h-Hour)*60;
                            Min = Math.floor(m);  // 分
                            s = (m-Min)*60
                            Sec = Math.floor(s);  // 秒

                            msg_content = (Day ? ("<big>" +Day + "天</big>") : "") + "<em>" + appendZero(Hour) + "</em><b>:</b><em>" + appendZero(Min) + "</em><b>:</b><em>" + appendZero(Sec) + "</em>";
                            if (duration <= 0) msg_content = "";

                            $(".product-info-wrapper .sale-info").html( msg_before + msg_content )
                        }
                        saleTimeLimit();

                        function appendZero(num){
                            if(num<10) return "0" +""+ num;
                            return num;
                        }
                    })
                    </script>';
                }
            echo '</div>';
            echo '<div class="desc">'._get_the_post_excerpt().'</div>';
            echo the_tags( '<div class="tags">', '', '</div>' );
            ?>
            <form action="#" class="buyer">
                <label for="buyer-amount">数量</label>
                <input type="number" id="buyer-amount" name="amount" value="1" min="1" max="999" class="amount"><b class="unit">件</b>
                <?php
                // 库存
                $sku = _get_the_product_meta("product_sku") ?: null;
                if( $sku ){
                    echo '<span class="meta sku">库存<strong>'.$sku.'</strong>件</span>';
                }
                ?>
            </form>
            <div class="btns">
                <a href="<?php echo _get_the_product_meta("product_link"); ?>" class="btn link">立即购买</a>
            </div>
        </div>
    </section>
    <!-- 主体 -->
    <div class="product-content-wrapper">
        <div class="content-wrapper">
            <section class="content site-style-border-radius">
                <ul class="tabs">
                    <li data-index="details" class="tab details active">商品详情</li>
                    <li data-index="comments" class="tab comments">累计评价</li>
                    <div class="mobile">
                        <i class="fa fa-qrcode"></i>手机端查看<i class="fa fa-angle-down"></i>
                        <div class="product-qrcode"></div>
                    </div>
                </ul>
                <div class="items">
                    <!-- 用户编辑内容 -->
                    <div data-index="details" class="item details active">
                        <!-- 广告代码 -->
                        <?php  _ads_loader($adsname='ads_post_product', $classname='module ads-post-product'); ?>

                        <!-- 文章内容 -->
                        <div class="atricle-content">
                            <?php the_content(); ?>
                        </div>

                        <!-- 点赞分享 -->
                        <?php _module_loader('module_post_share_like_reward'); ?>

                        <!-- 文章底部分页按钮 -->
                        <?php wp_link_pages( array(
                            'before'            => '<div class="page-links">',
                            'after'             => '</div>',
                            'link_before'       => '<span>',
                            'link_after'        => '</span>',
                            'next_or_number'    => 'number',
                            'nextpagelink'      => __( '下一页 &raquo', 'QGG' ),
                            'previouspagelink'  => __( '&laquo 上一页', 'QGG' ),
                            'pagelink'          => '%',
                            ) );
                        ?>

                        <!-- 作者信息 -->
                        <?php _module_loader('module_post_author_panel'); ?>

                        <!-- 版权信息 -->
                        <?php _module_loader('module_post_copyright'); ?>
                    </div>
                    <div data-index="comments" class="item comments">
                        <!-- 文章底部读者评论 -->
                        <?php comments_template('', true); ?>
                    </div>
                </div>
                <script type="text/javascript">
                jQuery(document).ready(function(){
                    $('.product-container').on("click", ".product-content-wrapper .tab", function(){
                        const index = $(this).data("index");
                        // 切换 Tab 显示隐藏
                        $('.product-container .tab').each(function(){
                            $(this).toggleClass("active");
                        });
                        // 切换 Item 显示隐藏
                        $('.product-container .item').each(function(){
                            if( index == $(this).data("index") ){
                                $(this).addClass("active");
                            } else {
                                $(this).removeClass("active");
                            }
                        });
                    })
                });
                </script>
            </section>
        </div>
        <!-- 侧栏 -->
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer();