<?php 
/** 
 * @name 全屏滚动 Banner
 * @description 再引用位置处加载一个全屏滚动 Banner ，可自由设置背景图片及浮动图片、推广信息、超链接等
 */
?>

<section id = "banner" class="banner swiper-container">
	<div id= "banner-content" class="banner-content swiper-wrapper">
		<?php
		for ($i=1; $i <= 5; $i++) {
			if( QGG_options('full_screen_banner_open-'.$i) ){
				
				QGG_options('full_screen_banner_img_right-'.$i) ? $imgStyle  = 'float:right;' : $imgStyle  = '';
				QGG_options('full_screen_banner_img_right-'.$i) ? $descStyle  = 'float:left;' : $descStyle  = '';
				
				 echo '
				<div class="item swiper-slide" data-history="slide'.$i.'" style="background-image: url( '.QGG_options('full_screen_banner_bg-'.$i).' )">
					<div class="cover"></div>
					<div class="container">
						<div style="'.$imgStyle.'" class="item-poster ani" swiper-animate-effect="bounceInLeft" swiper-animate-duration="0.5s" swiper-animate-delay="0.3s">
							<img src="'.QGG_options('full_screen_banner_float-'.$i).'" alt="">
						</div>
						<div class="item-desc ani" swiper-animate-effect="bounceInRight" swiper-animate-duration="0.5s" swiper-animate-delay="0.3s" style="'.$descStyle.'">
							<div class="info-box">';
								if ( QGG_options('full_screen_banner_title-'.$i) ) { echo '<h2>'.QGG_options('full_screen_banner_title-'.$i).'</h2>'; }
								if ( QGG_options('full_screen_banner_desc-'.$i) ) { echo '<p>'.QGG_options('full_screen_banner_desc-'.$i).'</p>'; }
								echo '<div class="item-btn">';
								if ( QGG_options('full_screen_banner_lbtn-'.$i) ) { echo '<a class="">'.QGG_options('full_screen_banner_lbtn-'.$i).'</a>'; }
								if ( QGG_options('full_screen_banner_rbtn-'.$i) ) { echo '<a class="">'.QGG_options('full_screen_banner_rbtn-'.$i).'</a>'; }
								echo '</div>';
							echo '</div>';
						echo
						'</div>
					</div> 
				</div>'; 
			}
		}
		?>
	</div>
	<!-- 如果需要分页器 -->
	<div id="banner-nav" class="banner-nav">
		<div class="swiper-pagination "></div>
	</div>

	<!-- 如果需要导航按钮 -->
	<div class="swiper-button-prev swiper-button-white">&laquo;</div>
	<div class="swiper-button-next swiper-button-white">&raquo;</div>
	
</section>


