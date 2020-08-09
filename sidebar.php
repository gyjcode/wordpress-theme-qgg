<?php //if( _hui('layout') == '1' ) return; ?>
<div class="sidebar">
<?php 
	$post_template =get_post_meta( get_the_ID(), '_post_template', true );
	if (function_exists('dynamic_sidebar')){
		dynamic_sidebar('header'); 
		
		if (is_home()){
			dynamic_sidebar('home'); 
		}else if (is_single()){
			if (strpos($post_template,'video') !== false){
				dynamic_sidebar('single_video'); 
			}elseif (strpos($post_template,'product') !== false){
				dynamic_sidebar('single_product'); 
			}else{
				dynamic_sidebar('single'); 
			}
		}elseif (is_category()){
			dynamic_sidebar('category'); 
		}else if (is_tag() ){
			dynamic_sidebar('tag'); 
		}else if (is_search()){
			dynamic_sidebar('search'); 
		}

		dynamic_sidebar('footer');
	} 
?>
</div>