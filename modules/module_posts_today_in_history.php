<?php
/**
 * 历史上的今天
 */

function module_posts_today_in_history(){

    // 配置项
    $title = QGG_options('today_in_history_title') ?: "历史上的今天";
    $limit = QGG_options('today_in_history_num') ?: 5; 
    
    // 查询数据库获取文章
    global $wpdb;
    $post_year  = get_the_time('Y');
    $post_month = get_the_time('m');
    $post_day   = get_the_time('j');
    
    // 数据库查询
    $sql = "SELECT ID, YEAR(post_date_gmt) as post_year, post_title, comment_count
            FROM $wpdb->posts
            WHERE post_password = '' AND post_type = 'post' AND post_status = 'publish' AND YEAR(post_date_gmt) != '$post_year' AND MONTH(post_date_gmt) = '$post_month' AND DAY(post_date_gmt) = '$post_day'
            ORDER BY post_date_gmt
            DESC LIMIT $limit";
            
    $query_result = $wpdb->get_results($sql);
    
    $history_post = "";
    if( $query_result ){
        foreach( $query_result as $post ){
            $post_link     = get_permalink( $post->ID );
            $post_year     = $post->post_year;
            $post_title    = $post->post_title;
            $comment_count = $post->comment_count;
            
            $history_post .= '
            <li>
                <lable>'.$post_year.'</lable>：
                <a href="'.$post_link.'">'.$post_title.'</a>
                <span>('.$comment_count.')</span>
            </li>';
        }
    }
    
    $arr_month = array( 1=>"一月",2=>"二月",3=>"三月",4=>"四月",5=>"五月",6=>"六月",7=>"七月",8=>"八月",9=>"九月",10=>"十月",11=>"十一",12=>"十二" );
    foreach($arr_month as $key => $value){
        if($post_month  == $key){
            $month_l = $value;
        }
    }
    
    if ( $history_post ){
        $result = '
        <section class="module today-in-history">
            <fieldset class="site-style-border-radius">
                <legend>
                    <div class="date">
                        <span class="month">'.$month_l.'</span>
                        <span class="day">'.$post_day.'</span>
                    </div>
                    <h3>'.$title.'</h3>
                </legend>
                <ul>'.$history_post.'</ul>
            </fieldset>
        </section>';
    }else{
        $result = "";
    }

    echo $result;
}
?>