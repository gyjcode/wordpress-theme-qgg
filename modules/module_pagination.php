<?php 
/**
 * @name 分页链接模板
 * @description 在引用位置处加载一个文章列表页码模块包含页码、上一页、下一页等
 */
?>

<?php
function module_pagination() {
    // 是否为页面或文章
    if ( is_singular() ){ return; }
    // 获取最大页码
    global $wp_query, $paged;
    $max_page = $wp_query -> max_num_pages;
    // 只有一页不显示
    if ( $max_page == 1 ){ return; }
    
    echo '
    <div class="module pagination">
        <ul>';
        if ( empty( $paged ) ){ $paged = 1; }
        // 上一页
        echo '<li class="prev-page">'.get_previous_posts_link("上一页").'</li>';
        
        if ( $paged > 3  ){ the_paging_link( 1, '<li>第一页</li>' ); }
        
        if ( 3 < $paged){ 
            echo "<li><span>···</span></li>"; 
        }
        
        for( $i = $paged - 2; $i <= $paged + 2; $i++ ) { 
            if ( $i > 0 && $i <= $max_page ) {
                $i == $paged ? print "<li class=\"active\"><span class=\"site-style-background-color\">{$i}</span></li>" : the_paging_link( $i );
            }
        }
        // 下一页
        echo '<li class="next-page">'.get_next_posts_link(__('下一页')).'</li>'; 
        // 共 n 页
        echo '<li class="active"><span class="site-style-background-color">共 '.$max_page.' 页</span></li>';
        
    echo '
        </ul>
    </div>';
}

function the_paging_link( $i, $title = '' ) {
    if ( $title == '' ) $title = "第 {$i} 页";
    echo "<li><a href='", esc_url(mb_convert_encoding(get_pagenum_link($i), "UTF-8","GBK")), "'>{$i}</a></li>";
}
