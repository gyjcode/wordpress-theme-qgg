<?php 
/**
 * @name 用户中心页面
 * @description 加载用户中心页面，用户中心需在后台创建页面并选择对应的页面模板
 */
?>
<?php
function module_get_page_user_center(){
	
	$page_id = QGG_Options('user_center_page');

	if( !$page_id ){
		return false;
	}

	if( get_permalink($page_id) ){
		return get_permalink($page_id);
	}

	return false;
}