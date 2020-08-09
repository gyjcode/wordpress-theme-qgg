<?php 
/** 
 * @name 三栏推广模块 （Description，QRCode，Hyperlink）
 * @description 在引用位置处加载一个三栏推广模块，可自主设置网站描述（包含网站 Logo ）、社交信息（包含二维码）、超链接等
 * @author 蝈蝈要安静——一个不学无术的伪程序员
 * @url https://blog.quietguoguo.com
 */
?>

<footer class="footer-brand-lmr-wrap">
	<div class="footer-brand-lmr-main container">
		<div>
			<div class="footer-brand-lmr-left">
			<?php
			echo '<a href="#">
					<img src="'.QGG_options('footer_brand_lmr_logo').'" alt="">
				</a>
				<p>'.QGG_options('footer_brand_lmr_text').'</p>'
			?>
			</div>
		</div>
		
		<div>
			<div class="footer-brand-lmr-middle">
			<?php
			for ($i=1; $i <= 3; $i++) {
				echo 
				'<div>
					<img src="'.QGG_options('footer_brand_lmr_qr_'.$i).'" alt="">
					<p>'.QGG_options('footer_brand_lmr_qr_id_'.$i).'</p>
					<p>'.QGG_options('footer_brand_lmr_qr_desc_'.$i).'</p>
				</div>';
			}
			?>
			</div>
		</div>
		
		<div>
			<div class="footer-brand-lmr-right">
				<?php
				echo '
				<h4>'.QGG_options('footer_brand_lmr_caption').'</h4>
				<div>'
				?>
				<?php
				for ($j=1; $j <= 12; $j++) {
					if (QGG_options('footer_brand_lmr_qr_title_'.$j)){
						echo '<a href="'.QGG_options('footer_brand_lmr_href_'.$j).'" target="_black"><i class="iconfont qgg-link"></i>'.QGG_options('footer_brand_lmr_qr_title_'.$j).'</a>';
					}
				}
				?>
				</div>
			</div>
		</div>
		
	</div>
</footer>
