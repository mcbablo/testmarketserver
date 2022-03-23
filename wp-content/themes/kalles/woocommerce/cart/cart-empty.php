<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
$enable_custom_link  = cs_get_option('wc_mini_cart_setting-custom_return_link');
$custom_link       = cs_get_option( 'custom-return-link' );
$custom_link_url   = isset( $custom_link['url'] ) ? $custom_link['url'] : '#';
$custom_link_target   = isset( $custom_link['target'] ) ? $custom_link['target'] : '_blank';
/*
 * @hooked wc_empty_cart_message - 10
 */
do_action( 'woocommerce_cart_is_empty' );

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>

    <p class="return-to-shop">
        <?php if ($enable_custom_link) : ?>
            <a class="button wc-backward" href="<?php echo esc_attr($custom_link_url); ?>" target="<?php echo esc_attr($custom_link_target); ?> ?>">
                <?php esc_html_e( 'Return to Shop.', 'kalles' ) ?>
            </a>
        <?php else: ?>
            <a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                <?php
                /**
                 * Filter "Return To Shop" text.
                 *
                 * @since 4.6.0
                 * @param string $default_text Default text.
                 */
                echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return to Shop.', 'kalles' ) ) );
                ?>
            </a>
        <?php endif; ?>

    </p>
<?php endif; ?>
