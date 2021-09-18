<?php
/**
 * Template Name: 站点地图
 * Description:   站点地图页面，HTML格式
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head profile="http://gmpg.org/xfn/11">
        <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
        <title>SiteMap - <?php bloginfo('name'); ?></title>
        <meta name="keywords" content="SiteMap,<?php bloginfo('name'); ?>" />
        <meta name="copyright" content="<?php bloginfo('name'); ?>" />
        <link rel="canonical" href="<?php echo get_permalink(); ?>" />
        <style type="text/css">
            body {
                margin: 0;
                font-family: Verdana;
                font-size: 12px;
                color: #000;
                background: #fff;
            }
            h3 {
                margin-top: 0;
            }
            ul {
                margin-bottom: 0;
            }
            li {
                margin-top: 8px;
            }
            .page {
                padding: 4px; 
                border-top: 1px #eee solid
            }
            .author {
                border-top: 1px #ddddee solid;
                padding: 5px; 
                background-:#eef; 
            }
            .nav, .content, .footer {
                clear: both;
                width: 90%; 
                margin: auto;
                margin-top: 10px;
                padding: 1rem;
                border: 1px solid #eee; 
            }
        </style>
    </head>
    <body vlink="#333333" link="#333333">
        <h2 style="text-align: center; margin-top: 20px"><?php bloginfo('name'); ?>'s SiteMap </h2>
        <div class="nav">
            <a href="<?php bloginfo('url'); ?>/"><strong><?php bloginfo('name'); ?></strong></a> &raquo; <a href="<?php echo get_permalink(); ?>">站点地图</a>
        </div>
        
        <div class="content">
            <h3>最新文章</h3>
            <ul>
                <?php
                $myposts = get_posts('numberposts=-1&orderby=post_date&order=DESC');
                foreach($myposts as $post) :
                ?>
                    <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank"><?php the_title(); ?></a></li>
                <?php
                endforeach;
                ?>
            </ul>
        </div>

        <div class="content categories">
            <h3 class="category">分类目录</h3>
            <ul><?php wp_list_categories('title_li='); ?></ul>
        </div>
        <div class="content posts">
            <h3 class="post">单页面</h3>
            <?php wp_page_menu( $args ); ?>
        </div>

        <div class="footer">
            查看博客首页: <strong><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></strong></div><br />
            <div style="text-align: center; font-size: 11px">
                最新更新:
                <?php
                    $sql = "SELECT MAX(post_modified) AS max_post_modified
                            FROM $wpdb->posts
                            WHERE (post_type = 'post' OR post_type = 'page') AND (post_status = 'publish' OR post_status = 'private')";
                    $query_result = $wpdb->get_results($sql);

                    $last_time = date('Y-m-d G:i:s', strtotime($query_result[0]->max_post_modified));
                    echo $last_time;
                ?>
            </div>
            <div style="text-align: center; font-size: 11px">
                Powered by <strong><a href="<?php bloginfo('url'); ?>" target="_blank"><?php bloginfo('name'); ?></a></strong>&nbsp;
                &copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url');?>/" style="cursor:help"><?php bloginfo('name');?></a> 版权所有.<br /><br />
            </div>
    </body>
</html>
