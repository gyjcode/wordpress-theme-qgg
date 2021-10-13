<?php
/**
  * 产品展示分类页面模板
  */

//  获取配置
$count_show   = QGG_Options('cat_product_count_show') ?: false;
$qrcode_on    = QGG_Options('cat_product_qrcode_on') ?: false;
$qrcode_title = QGG_Options('cat_product_qrcode_title') ?: '';
?>

<section class="container">
    <!-- 侧栏 -->
    <div class="module sidebar cat-product-sidebar">
        <div class="filters-wrapper site-style-border-radius">
            <h3><i class="fa fa-list"></i><?php echo get_category($cat_rid)->cat_name ?></h3>
            <ul>
                <?php 
                $args_lists = 'child_of='. $cat_rid .'&depth=0&hide_empty=0&title_li=&orderby=id&order=DESC&echo=0';
                if( $count_show ){
                    $args_lists .= '&show_count=1';
                }
                $cat_lists = wp_list_categories( $args_lists );

                echo $cat_lists;
                ?>
            </ul>
        </div>
        <?php if( $qrcode_on ){ ?>
            <div class="qrcode-wrapper site-style-border-radius">
                <?php echo  $qrcode_title ? '<h4>'.$qrcode_title.'</h4>' : ''; ?>
                <div class="cat-product-qrcode" data-url="<?php echo get_category_link($cat_id) ?>"></div>
            </div>
        <?php } ?>
    </div>
    <!-- 主体 -->
    <div class="content-wrapper">
        <?php 
        if ( !have_posts() ){
            get_template_part( '404' );
            return;
        }
        // 查询相关文章
        $args = array(
            'cat'                 => $cat_rid,
            'orderby'             => 'date',
            'showposts'           => 60,
            'order'               => 'desc',
            'ignore_sticky_posts' => 1
        );
        query_posts( $args );
        echo '<div class="module content cat-product-content site-style-border-radius">';
            while ( have_posts() ) : the_post();
                echo '
                <article class="product  site-style-border-radius">
                    <div class="thumb-wrapper">
                        <a class="thumb" href="'.get_permalink().'" '._post_target_blank().'>'._get_the_post_thumbnail().'</a>
                    </div>
                    <div class="details-wrapper">
                        <div class="price">';
                            // 价格参数 
                            $price           = _get_the_product_meta("product_price") ?: null;
                            $sale_price      = _get_the_product_meta("product_sale_price") ?: null;
                            $sale_date_from  = _get_the_product_meta("product_sale_price_date_from") ?: null;
                            $sale_date_to    = _get_the_product_meta("product_sale_price_date_to") ?: null;
                            $lost_days       = (strtotime(date("Y-m-d")) - strtotime($sale_date_from) )/(24*60*60);
                            $sale_price_show = $sale_price && $sale_date_from && $sale_date_to && ($lost_days <= 7);
                            $line_through    = $sale_price_show ? "line-through" : "none";
                            if( $price ){
                                echo '<span style="text-decoration: '.$line_through.';">&#165;'.number_format($price, 2).'</span>';
                                if( $sale_price_show ){
                                    echo '<span class="sale"><small>促销</small>&#165;'.number_format($sale_price, 2).'</span>';
                                }
                            }else{
                                echo '<span class="no-price">该商品暂无定价！</span>';
                            }
                            
                        echo '
                        </div>
                        <div class="title">
                            <a href="'.get_permalink().'" '. _post_target_blank() .'><h2>'.get_the_title().'</h2></a>
                        </div>
                    </div>
                </article>';
            endwhile;
        echo '</div>';
        wp_reset_query();
        _module_loader('module_pagination');
        ?>
    </div>
</section>