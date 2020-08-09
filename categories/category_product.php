<?php
/**
  * @name           产品展示分类页面模板
  * @description    用于网站展示相应产品，目前包含图片、标题、原价、特价、产品目录等信息
  */
 $borderRadius = 'border-radius: '. QGG_options('site_style_border-radius').'px;';
 $root = get_category($cat_root_id);
 $args = array(
 	'cat'                 => $cat_root_id,
 	'orderby'             => 'date',
 	'showposts'           => 60,
 	'order'               => 'desc',
 	'ignore_sticky_posts' => 1
 );
?>

<section class="container cat-product">
	<div class="cat-product-content">
		<!-- 产品展示分类模板侧栏 -->
		<div class="cat-product-side" style="<?php echo $borderRadius; ?>">
			<div class="cat-product-filters">
				
				<h3><i class="iconfont qgg-balance"></i><?php echo $root->cat_name ?></h3>
				<ul>
				<?php 
					$args_lists = 'child_of='. $cat_root_id .'&depth=0&hide_empty=0&title_li=&orderby=id&order=DESC&echo=0';
					if( QGG_Options('cat_product_show_count') ){
						$args_lists .= '&show_count=1';
					}
					$cat_lists = wp_list_categories( $args_lists );
					echo $cat_lists;
				?>
				</ul>
			</div>
			<?php if( QGG_Options('cat_product_qrcode_open') ){ ?>
			<div class="cat-product-qrcode">
				<?php if( QGG_Options('cat_product_qrcode_title') ){ ?><h4><?php echo QGG_Options('cat_product_qrcode_title') ?></h4><?php } ?>
				<div class="cat-qrcode" data-url="<?php echo get_category_link($cat_id) ?>"></div>
			</div>
			<?php } ?>
		</div>
		<!-- 产品展示分类模板主体 -->
		<?php 
		if ( have_posts() ){
			
			query_posts( $args );
			
			echo '<div class="cat-product-main">';
				while ( have_posts() ) : the_post();
					echo '<article class="cat-product-item" style="'.$borderRadius.'">';
						echo '<a'. the_post_target_blank() .' class="thumbnail" href="'.get_permalink().'">'._get_post_thumbnail().'</a>';
						echo '<a'. the_post_target_blank() .' href="'.get_permalink().'"><h2>'.get_the_title().'</h2></a>';
						echo '<footer>';
							$line_through =_get_product_meta("bargain_price") ? "line-through" : "none";
							if( _get_product_meta("original_price") ){
								echo '<span class="original-price" style="text-decoration: '.$line_through.';">'._get_product_meta("original_price", _get_price_pre().' ').'</span>';
								if( _get_product_meta("bargain_price")){
									echo '<span class="bargain-price">'._get_product_meta("bargain_price", _get_price_pre().' ').'</span>';
								}
							}else{
								echo '<span>该商品暂无定价！</span>';
							}
							
						echo '</footer>';
					echo '</article>';
				endwhile; 
				wp_reset_query();
			echo '</div>';

		}else{
			get_template_part( '404' );
		}
		?>
	</div>
</section>