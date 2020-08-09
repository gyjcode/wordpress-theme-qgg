<?php
//历史上的今天，代码来自柳城博主的 WP-Today 插件
function today_in_history(){
	
	$title = QGG_options('today_in_history_title') ? QGG_options('today_in_history_title') : "历史上的今天";    // $title = "历史上的今天";
	$limit = QGG_options('today_in_history_num') ? QGG_options('today_in_history_num') : 5;      // $limit = 5;

	global $wpdb;
	$post_year  = get_the_time('Y');
	$post_month = get_the_time('m');
	$post_day   = get_the_time('j');

	$sql = "select ID, year(post_date_gmt) as h_year, post_title, comment_count FROM 
			$wpdb->posts WHERE post_password = '' AND post_type = 'post' AND post_status = 'publish'
			AND year(post_date_gmt)!='$post_year' AND month(post_date_gmt)='$post_month' AND day(post_date_gmt)='$post_day'
			order by post_date_gmt DESC limit $limit";
			
	$histtory_post = $wpdb->get_results($sql);
	if( $histtory_post ){
		foreach( $histtory_post as $post ){
			$h_year       = $post->h_year;
			$h_post_title = $post->post_title;
			$h_permalink  = get_permalink( $post->ID );
			$h_comments   = $post->comment_count;
			
			$h_post      .= "<li><lable>$h_year</lable>：<a href='".$h_permalink."' title='Permanent Link to ".$h_post_title."'>$h_post_title <span>($h_comments)</span></a></li>";
		}
	}
	
	$arr_month = array( 1=>"一月",2=>"二月",3=>"三月",4=>"四月",5=>"五月",6=>"六月",7=>"七月",8=>"八月",9=>"九月",10=>"十月",11=>"十一",12=>"十二" );
	foreach($arr_month as $key => $value){
		if($post_month  == $key){
			$month_l = $value;
		}
	}
	
	if ( $h_post ){
		$result = '
		<section class="today-in-history">
			<fieldset>
				<legend>
					<div class="today-date">
						<span class="month">'.$month_l.'</span>
						<span class="day">'.$post_day.'</span>
					</div>
					<h3>'.$title.'</h3>
				</legend>
				<ul>'.$h_post.'</ul>
			</fieldset>
		</section>';
	}else{
		$result = "";
	}

	echo $result;
}
today_in_history();
?>