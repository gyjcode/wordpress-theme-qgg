<?php 

// 加载微信公众号验证码
if( !is_super_admin() && QGG_options('right_click_menu_on') ){
    include get_template_directory() . '/enhance/enhance_right_click.php';
}

// 手机端调试
if( QGG_Options('enhance_vconsole_on') ){
    include get_template_directory() . '/enhance/enhance_vconsole.php';
}

// 禁止F12进入控制台
if( QGG_Options('enhance_f12_forbidden_on') ){
include get_template_directory() . '/enhance/enhance_f12_forbidden.php';
}
