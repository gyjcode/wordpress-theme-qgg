<?php 
/**
 * @name 分页链接模板
 * @description 在引用位置处加载一个文章列表页码模块包含页码、上一页、下一页等
 */
?>

<?php
function module_paging_nav() {
	
	if ( is_singular() ){ return; }
	global $wp_query, $paged;
	$max_page = $wp_query->max_num_pages;
	if ( $max_page == 1 ){ return; }
	
	echo '<div class="paging-nav"><ul>';
		
		if ( empty( $paged ) ){ $paged = 1; }
		
		echo '<li class="prev-page">'; 
		echo previous_posts_link(__('上一页', 'QGG')); 
		echo '</li>';
		
		if ( $paged > 3  ){ the_paging_link( 1, '<li>第一页</li>' ); }
		
		if ( 3 < $paged){ 
			echo "<li><span>···</span></li>"; 
		}
		
		for( $i = $paged - 2; $i <= $paged + 2; $i++ ) { 
			if ( $i > 0 && $i <= $max_page ) {
				$i == $paged ? print "<li class=\"active\"><span class=\"site-skin-bgc\">{$i}</span></li>" : the_paging_link( $i );
			}
		}
		
		echo '<li class="next-page">'; 
		echo next_posts_link(__('下一页', 'QGG'));
		echo '</li>'; 
		echo '<li><span class="site-skin-bgc">共 '.$max_page.' 页</span></li>';
		
	echo '</ul></div>';
}

function the_paging_link( $i, $title = '' ) {
	if ( $title == '' ) $title = "第 {$i} 页";
	echo "<li><a href='", esc_url(mb_convert_encoding(get_pagenum_link($i), "UTF-8","GBK")), "'>{$i}</a></li>";
}
