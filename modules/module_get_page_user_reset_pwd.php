<?php 
/**
 * @name 用户重置密码页面
 * @description 加载用户重置密码页面，用户重置密码页面需在后台新建页面并选择对应的页面模板
 */
?>

<?php
function module_get_page_user_reset_pwd(){
	
	$page_id = QGG_Options('user_reset_passward');
	
	if( !$page_id ){ 
		return false; 
	}elseif( get_permalink($page_id) ){ 
		return get_permalink($page_id); 
	}
	
	return false;
}