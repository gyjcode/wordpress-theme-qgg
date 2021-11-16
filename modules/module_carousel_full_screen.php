<?php 
/** 
 * 首页全屏轮播
 */
?>

<section id="carousel-full-screen" class="carousel-full-screen swiper-container">
    <div class="items swiper-wrapper">
        <?php
        for ($i=1; $i <= 3; $i++) {
            if( QGG_options('carousel_full_screen_on-'.$i) ){
                // 配置 # 显示方式
                $bgImg     = QGG_options('carousel_full_screen_bgimg-'.$i) ?: '';
                $floatImg  = QGG_options('carousel_full_screen_img-'.$i);
                $noImg     = $floatImg ? '' : 'no-img';
                $imgStyle  = QGG_options('carousel_full_screen_img_right-'.$i) ? 'order: 2;' : '';
                // 配置 # 文案描述
                $title     = QGG_options('carousel_full_screen_title-'.$i) ?: '';
                $desc      = QGG_options('carousel_full_screen_desc-'.$i) ?: '';
                $leftBtn   = QGG_options('carousel_full_screen_lbtn-'.$i) ?: '';
                $leftHref  = QGG_options('carousel_full_screen_lbtn_href-'.$i) ?: '#';
                $rightBtn  = QGG_options('carousel_full_screen_rbtn-'.$i) ?: '';
                $rightHref = QGG_options('carousel_full_screen_rbtn_href-'.$i) ?: '#';
                // 配置 # 悬浮图片
                $imgEffect   = QGG_options('carousel_full_screen_img_animate_effect-'.$i) ?: 'bounceInLeft';
                $imgDuration = QGG_options('carousel_full_screen_img_animate_duration-'.$i) ?: '0.5';
                $imgDelay    = QGG_options('carousel_full_screen_img_animate_delay-'.$i) ?: '0.3';
                $txtEffect   = QGG_options('carousel_full_screen_txt_animate_effect-'.$i) ?: 'bounceInLeft';
                $txtDuration = QGG_options('carousel_full_screen_txt_animate_duration-'.$i) ?: '0.5';
                $txtDelay    = QGG_options('carousel_full_screen_txt_animate_delay-'.$i) ?: '0.3';
                
                echo '
                <div class="item swiper-slide" data-history="slide'.$i.'" style="background-image: url( '.$bgImg.' )">
                    <div class="mask"></div>
                    <div class="container '.$noImg .'">
                        <!-- 浮动图片 -->
                        <div style="'.$imgStyle.'" class="item-poster ani"
                        swiper-animate-effect="'.$imgEffect.'"
                        swiper-animate-duration="'.$imgDuration.'s"
                        swiper-animate-delay="'.$imgDelay.'s">
                            <img src="'.$floatImg.'" alt="">
                        </div>
                        <!-- 文字描述 -->
                        <div class="item-desc ani"
                        swiper-animate-effect="'.$txtEffect.'"
                        swiper-animate-duration="'.$txtDuration.'s"
                        swiper-animate-delay="'.$txtDelay.'s">
                            <div class="desc-wrapper">';
                                echo $title ? '<h2>'.$title.'</h2>' : '';
                                echo $desc ? '<p>'.$desc.'</p>' : ''; 
                                echo '<div class="item-btns">';
                                    echo $leftBtn ? '<a target="_blank" href="'.$leftHref .'">'.$leftBtn.'</a>' : '';
                                    echo $rightBtn ? '<a target="_blank" href="'.$rightHref.'">'.$rightBtn.'</a>' : '';
                                echo '</div>';
                            echo '</div>';
                        echo '
                        </div>
                    </div> 
                </div>'; 
            }
        }
        ?>
    </div>
    <!-- 分页器 -->
    <div class="navs swiper-pagination "></div>

    <!-- 导航按钮 -->
    <div class="swiper-button-prev swiper-button-white">&laquo;</div>
    <div class="swiper-button-next swiper-button-white">&raquo;</div>
    
</section>
