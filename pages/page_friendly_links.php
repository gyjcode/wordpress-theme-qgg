<?php 
/**
  * Template name: 友情链接
  * Description:   显示你网站某个链接分类下的所有链接，一般为友情链接，需要在主题后台设置中心选择链接分类
  */
get_header();
?>

<section class="container">
    <!-- 页面菜单 -->
    <?php _module_loader('module_page_menu', false) ?>
    <!-- 页面内容 -->
    <div class="content-wrap">
        <div class="content site-style-border-radius">
            <?php while (have_posts()) : the_post(); ?>
            <header class="page-header">
                <h1 class="page-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
            </header>
            <article class="page-content">
                <?php the_content(); ?>
            </article>
            <?php endwhile;  ?>
            
            <ul class="page-friendly-links">
                <?php
                $links_cat = QGG_options( 'page_friendly_links' );
                $links = array();
                
                if( is_array($links_cat) && $links_cat ){
                    foreach ($links_cat as $key => $value) {
                        if( $value ) $links[] = $key;
                    }
                }
                $links = implode(',', $links);
                if( !empty($links) ){
                    wp_list_bookmarks(array(
                        'category'         => $links,
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