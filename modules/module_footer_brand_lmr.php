<?php 
/** 
 * 三栏推广模块
 */
?>

<footer class="module footer-brand-lmr">
    <div class="container">
        <!-- 左侧 -->
        <div class="left">
            <?php
            // 获取配置
            $logo_src  = QGG_options('footer_brand_lmr_logo') ?: '';
            $brand_txt = QGG_options('footer_brand_lmr_text') ?: '';

            echo '
            <img src="'.$logo_src.'" alt="">
            <p>'.$brand_txt.'</p>';
            ?>
        </div>
        <!-- 中间 -->
        <div class="middle">
            <?php
            for ($i=1; $i <= 3; $i++) {
                // 获取配置
                $qr_img  = QGG_options('footer_brand_lmr_qr_img_'.$i) ?: '';
                $qr_id   = QGG_options('footer_brand_lmr_qr_id_'.$i) ?: '';
                $qr_desc = QGG_options('footer_brand_lmr_qr_desc_'.$i) ?: '';

                echo '
                <div class="qrcode">
                    <img src="'.$qr_img.'" alt="">
                    <span>'.$qr_id.'</span>
                    <span>'.$qr_desc.'</span>
                </div>';
            }
            ?>
        </div>
        <!-- 右侧 -->
        <div class="right">
            <?php
                // 获取配置
                $title = QGG_options('footer_brand_lmr_title') ?: '';
                echo '<h4>'.$title.'</h4>';
            ?>
            <div class="links">
            <?php
            for ($j=1; $j <= 9; $j++) {
                // 获取配置
                $link_name = QGG_options('footer_brand_lmr_link_name_'.$j) ?: '';
                $link_href = QGG_options('footer_brand_lmr_link_href_'.$j) ?: '';

                echo '<a href="'.$link_href.'" target="_black">'.$link_name.'</a>';
            }
            ?>
            </div>
        </div>
    </div>
</footer>
