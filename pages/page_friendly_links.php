<?php 
/**
  * Template name: 友情链接
  * Description:   显示你网站某个链接分类下的所有链接，一般为友情链接，需要在主题后台设置中心选择链接分类
  */
get_header();
// 配置项
$friendly_link_cats = QGG_Options( 'page_friendly_link_cats' );
?>

<section class="container">
    <!-- 页面菜单 -->
    <?php _module_loader('module_page_menu', false) ?>
    <!-- 页面内容 -->
    <div class="content-wrap">
        <div class="module content site-style-border-radius">
            <!-- 页面内容 -->
            <?php while (have_posts()) : the_post(); ?>
            <header class="page-header">
                <h1 class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
            </header>
            <article class="page-content">
                <?php the_content(); ?>
            </article>
            <?php endwhile;  ?>
            <!-- 友情链接 -->
            <ul class="page-friendly-links">
                <?php
                // 获取选中的分类 ID 并以逗号分割
                $arrCats = array();
                if( is_array($friendly_link_cats) && $friendly_link_cats ){
                    // $key：分类的 ID，$value：是否选中
                    foreach ($friendly_link_cats as $key => $value) {
                        if( $value ) $arrCats[] = $key;
                    }
                }
                $strCats = implode(',', $arrCats);

                if( !empty($strCats) ){
                    wp_list_bookmarks(array(
                        'category'         => $strCats,
                        'category_orderby' => 'SLUG',
                        'category_order'   => 'ASC',
                        'orderby'          => 'RATING',
                        'order'            => 'DESC',
                        'show_images'      => 'TRUE',
                        'show_name'        => 'TRUE'
                    )); 
                }
                ?>
            </ul>
            <?php comments_template('', true); ?>    
        </div>
    </div>
</section>

<?php
get_footer();