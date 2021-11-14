        
        <?php 
        // 全站底部三栏推广区
        if( QGG_options('footer_brand_lmr_on') ){
            _module_loader('module_footer_brand_lmr');            
        }
        ?>
        <footer id="footer">
            <div class="container">
                
                <!-- 友情链接 -->
                <?php if( QGG_Options('friendly_links_on') ){ ?>
                <div class="friendly-links-wrap">
                    <ul class="friendly-links">
                        <label>友情链接：</label>
                        <?php 
                        $links_cat = QGG_Options('friendly_links_cat');
                        wp_list_bookmarks(array(
                            'categorize'       => false,
                            'title_li'         => '',
                            'category'         => $links_cat,
                            'category_orderby' => 'SLUG',
                            'category_order'   => 'ASC',
                            'orderby'          => 'RATING',
                            'order'            => 'DESC',
                            'show_name'        => false,
                            'show_description' => false,
                            'show_images'      => false,
                        )); 
                        ?>
                    </ul>
                </div>
                <?php } ?>
                <!-- 自定义页脚 -->
                <?php 
                if( QGG_Options('site_footer_content') ){
                    echo '<div class="footer-custom">'.QGG_Options('footer_custom_content').'</div>';
                }
                ?>
                <!-- 杂项 -->
                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?>
                        <a href="<?php echo home_url() ?>" target="_blank"><?php echo get_bloginfo('name'); ?></a>
                        <!-- ICP 备案 -->
                        <?php
                        if (QGG_Options('site_beian_icp')) {
                            echo '<a href="https://beian.miit.gov.cn/" target="_blank">'.QGG_Options('site_beian_icp') ? QGG_Options('site_beian_icp') : get_option('zh_cn_l10n_icp_num').'</a>';
                        }
                        ?>
                        <!-- 公网安备 -->
                        <?php
                        if (QGG_Options('site_beian_gov')) {
                            echo '<a href="https://www.beian.gov.cn/" target="_blank">'.QGG_Options('site_beian_gov') .'</a>';
                        }
                        ?>
                        <!-- 网站地图 -->
                        <?php
                        $sitemap_link = _get_page_sitemap_html_link();
                        if (QGG_Options('sitemap_html_page')) {
                            echo '<a href="'.$sitemap_link.'" target="_blank">网站地图</a>';
                        }
                        ?>
                        <!-- 网站技术支持 -->
                        <?php
                        if (QGG_Options('site_tech_support')) {
                            echo '本站由<a href="https://zibuyu.life/"><i>子不语</i></a>提供技术支持';
                        }
                        ?>
                        <!-- 自定义链接 -->
                        <?php echo QGG_Options('footer_custom_link') ?>
                    </p>
                </div>
                <!-- 加载&运行时间 -->
                <?php if ( QGG_Options('site_building_time') || QGG_Options('loading_time_on') ){ ?>        
                <div class="site-info">
                    <p>
                        <i>>>></i>
                        <?php echo QGG_Options('site_building_time') ? '<span class="running-time">网站已平稳运行：<b id="site_runtime"></b></span>' : ''; ?>
                        <?php QGG_Options('page_loading_time_on') ? printf('<span class="loading-time">本次页面加载总用时<b> %1$s </b>秒，数据库查询<b> %2$s </b>次</span>', timer_stop(0,3), get_num_queries()) : ""; ?>
                        <i><<<</i>
                    </p>
                </div>
                <?php } ?>
                <!-- 统计代码 -->
                <?php echo QGG_Options('site_track_code') ?>
            </footer>

            <!-- 客服系统 -->
            <?php QGG_options('rollbar_kefu_on') ? _module_loader('module_rollbar_kefu') : '';?>
            <!-- 全局变量 -->
            <?php include get_template_directory() . '/assets/js/GSM.php';?>
            <!-- 功能增强 -->
            <?php include get_template_directory() . '/enhance/index.php';?>
            <?php wp_footer();?>
        
        </div>
    </body>
</html>