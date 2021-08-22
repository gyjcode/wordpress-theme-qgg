<?php
/**
 * 首页推荐盒子模块
 */
?>

<section class="module topic-card-box">
    <ul class="items">
        <?php
        for ($i=1; $i <= 4; $i++) {
            echo
            '<li class="item site-style-border-radius">
                <a class="topic-card-link" target= "_blank" href="'.QGG_options('topic_card_box_link-'.$i).'">
                    <div class="focus">
                        <div class="mask"></div>
                        <img src="'.QGG_options('topic_card_box_img-'.$i).'" alt="">
                        <h4>'.QGG_options('topic_card_box_title-'.$i).'</h4>
                    </div>
                    <p>
                        <b>'.QGG_options('topic_card_box_desc01-'.$i).'</b>
                        <i>'.QGG_options('topic_card_box_desc02-'.$i).'</i>
                    </p>
                </a>
            </li>';
        }
        ?>
    </ul>
</section>