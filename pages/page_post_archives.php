<?php 
/**
 * Template name: 文章归档
 * Description:   按照日期将网站文章进行归档整理
 */
get_header();
?>

<div class="container">
    <!-- 页面菜单 -->
    <?php _module_loader('module_page_menu', false) ?>
    <!-- 页面内容 -->
    <div class="content-wrap">
        <div class="content site-style-border-radius">
            <?php while (have_posts()) : the_post(); ?>
            <header class="page-header">
                <h1 class="page-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
            </header>
            <article class="page-content">
                <?php the_content(); ?>
            </article>
            <?php endwhile;  ?>

            <article class="archives">
                <?php
                $previous_year = $year = 0;
                $previous_month = $month = 0;
                $ul_open = false;
                $myposts = get_posts('numberposts=-1&orderby=post_date&order=DESC');
                
                foreach( $myposts as $post ) :
                    setup_postdata($post);
                 
                    $year  = mysql2date('Y', $post->post_date);
                    $month = mysql2date('n', $post->post_date);
                    $day   = mysql2date('j', $post->post_date);
                    
                    if($year != $previous_year || $month != $previous_month) :
                        
                        if( $ul_open == true ) : 
                            echo '</ul></div>';
                        endif;
                        
                        echo '<div class="item"><h3>'; echo the_time('F Y'); echo '</h3>';
                        echo '<ul class="archives-list">';
                        $ul_open = true;
                        
                    endif;
                        
                    $previous_year = $year; $previous_month = $month;
                ?>
                    <li>
                        <time><?php the_time('j'); ?>日</time>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a>
                        <span><?php comments_number('', '( 1评论 )', '( %评论 )'); ?></span>
                    </li>
                <?php endforeach; ?>
                </ul>
            </article>
            <?php comments_template('', true); ?>
        </div>
    </div>
</div>

<?php
get_footer();