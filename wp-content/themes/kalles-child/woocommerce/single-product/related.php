<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (cs_get_option('wc_product_cross_sell-enable')) {
	$title = cs_get_option('wc_product_cross_sell-title') ? cs_get_option('wc_product_cross_sell-title') : 'Related products';
	$subtitle = cs_get_option('wc_product_cross_sell-subtext');
	$title_type = cs_get_option('wc_product_cross_sell-title_design');
	$title_icon = cs_get_option('wc_product_cross_sell-style7_icon') ? cs_get_option('wc_product_cross_sell-style7_icon') : 'gem';
	$slidesToShow = cs_get_option('wc_product_cross_sell-style7_icon') ? cs_get_option('wc_product_cross_sell-product_no_slider') : 4;


	if ( $related_products ) : ?>
		<div class="related product-extra mt__60">
			<div class="wrap_title  des_<?php echo esc_attr( $title_type ); ?>">
				<h3 class="the4-section-title mg__0 tc pr flex fl_center al_center fs__24 <?php echo esc_attr( $title_type ); ?>">
					<span class="mr__10 ml__10"><?php pll_e('relatedtitle2'); ?></span>
				</h3>
				<span class="dn tt_divider">
					<span></span>
					<i class="dn clprfalse<?php echo esc_attr( $title_type ); ?> la la-<?php echo esc_attr( $title_icon ); ?>"></i>
					<span></span>
				</span>
				<span class="section-subtitle db tc sub-title"><?php echo esc_html( $subtitle ); ?></span>
			</div>

			<div class="the4-carousel" data-slick='{"slidesToShow": <?php echo esc_attr( $slidesToShow ); ?>,"slidesToScroll": 1,"responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": 3}},{"breakpoint": 480,"settings":{"slidesToShow": 2}}]<?php echo ( is_rtl() ? ',"rtl":true' : '' ); ?>}'>
				<?php
					foreach ( $related_products as $related_product ) :
					 	$post_object = get_post( $related_product->get_id() );
						$GLOBALS['post'] =& $post_object;
						setup_postdata( $post_object );

						wc_get_template( 'content-product.php' );

					endforeach;
				?>
			</div>
		</div>
	<?php endif;

	wp_reset_postdata();
}