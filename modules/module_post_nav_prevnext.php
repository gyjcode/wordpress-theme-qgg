<?php
/**
 * 文章版权信息
 */
$prevnext_on   = QGG_Options('post_prevnext_on') ?: false;
$prevnext_img  = QGG_Options('post_prevnext_img') ?: '';
?>
<?php 
if( $prevnext_on ){ 
    if( $prevnext_img ){
        $current_category = get_the_category();
        $prevID = get_previous_post($current_category,'') ? get_previous_post($current_category,'')->ID : null;
        $previmg = get_the_post_thumbnail( $prevID, '', '' );
        $nextID = get_next_post($current_category,'') ? get_next_post($current_category,'')->ID : null;
        $nextimg = get_the_post_thumbnail( $nextID, '', '' );
    }
    echo '
    <nav class="module post-nav-prevnext '.($prevnext_img ? "" : "no-img").'">
        <span class="prev">
            <div class="thumbnail">'.$previmg.'</div>
            <div class="text">'.get_previous_post_link('<i class="page">上一篇</i>%link').'</div>
        </span>
        <span class="next">
            <div class="thumbnail">'.$nextimg.'</div>
            <div class="text">'.get_next_post_link('<i class="page">下一篇</i>%link').'</div>
        </span>
    </nav>';
} ?>
