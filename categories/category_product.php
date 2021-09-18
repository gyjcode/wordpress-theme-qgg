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
                        <a class="title" href="'.get_permalink().'" '. _post_target_blank() .'><h2>'.get_the_title().'</h2></a>
                    </div>
                    <div class="details-wrapper">';

                        $line_through =_get_product_meta("bargain_price") ? "line-through" : "none";
                        if( _get_product_meta("original_price") ){
                            echo '<span class="original-price" style="text-decoration: '.$line_through.';">'._get_product_meta("original_price", _get_price_pre().' ').'</span>';
                            if( _get_product_meta("bargain_price")){
                                echo '<span class="bargain-price">'._get_product_meta("bargain_price", _get_price_pre().' ').'</span>';
                            }
                        }else{
                            echo '<span>该商品暂无定价！</span>';
                        }
                        
                    echo '
                    </div>
                </article>';
            endwhile;
        echo '</div>';
        wp_reset_query();
        _module_loader('module_pagination');
        ?>
    </div>
</section>