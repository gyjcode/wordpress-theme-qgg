<?php
/**
 * @ name 用户右键菜单功能增强
 * @ description 在引用位置处加载一个指定分类的友情链接
 */
?>

<?php
if( QGG_Options('enhance_vconsole_open') ){
	if( current_user_can('manage_options') ){
	?>
		<script src="https://cdn.bootcss.com/vConsole/3.3.0/vconsole.min.js"></script>
		<script>new VConsole()</script>
	<?php
	}
}
?>