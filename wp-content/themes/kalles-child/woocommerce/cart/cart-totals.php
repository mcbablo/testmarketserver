<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals <?php echo esc_attr( ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : '' ); ?>">

	<div class="cart1">
		<div class="cart1-left">
			<div class="cart1-title"><?php pll_e('carttotal2'); ?></div>
		</div>
		<div class="cart1-right">
			<div class="cart1-title"><?php echo WC()->cart->get_cart_subtotal(); ?></div>
		</div>
	</div>

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<div>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php endif; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

		<?php global $woocommerce; ?>
		<div class="cart2">
			<div class="cart2-left">
				<div class="cart2-title"><?php pll_e('cartweight2'); ?></div>
			</div>
			<div class="cart2-right">
				<div class="cart1-title"><?php $total_weight = $woocommerce->cart->cart_contents_weight;$total_weight .= ' '.get_option('woocommerce_weight_unit'); echo $total_weight; ?></div>
			</div>
		</div>

	</div>

	<div class="wc-proceed-to-checkout mt__20">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
