<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

wp_register_script( 'clickloginjs',  CLICK_LOGIN_PLUGIN_DIR_URL . 'assets/click-login.js' );
$translation_array = array(
    'good' => pll__('logingood2'),
	'confirm' => pll__('loginconfirm2'),
	'req' => pll__('loginphonereq2'),
	'phonefalse' => pll__('loginphonefalse2'),		
	'pass' => pll__('loginpass2'),		
	'phonecode' => pll__('logincode2'),		
	'phonenot' => pll__('loginphonenot2'),		
	'reg' => pll__('loginreg2'),		
	'reqlink' => pll__('loginreglink2'),		
	'passname' => pll__('loginpassname2'),
	'passreset' => pll__('loginreset2'),
	'passdont' => pll__('loginpassdont2'),
	'passsuccess' => pll__('loginsuccess2'),
	'auth' => pll__('loginauth2'),
);
wp_localize_script( 'clickloginjs', 'translate', $translation_array );
wp_enqueue_script( 'clickloginjs' );

do_action( 'woocommerce_before_customer_login_form' ); ?>
<link rel="stylesheet" href="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/click-login.css" />
<script src="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/jquery.device.detector.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
<script src="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/click-login.js"></script>
<div>
    <h2><?php esc_html_e( 'Reset password', 'clickuz_login' ); ?></h2>

    <form class="woocommerce-form woocommerce-form-login click-reset" method="post" data-step="check-phone-reset">

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="phone-number"><?php esc_html_e( 'Phone number', 'clickuz_login' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text phone-reset" name="phone-number" id="phone-number" autocomplete="phone-number" placeholder="998 (__) ___-__-__" value="<?php echo ( ! empty( $_POST['phone-number'] ) ) ? esc_attr( wp_unslash( $_POST['phone-number'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide otp-wrapper">
            <label for="username"><?php esc_html_e( 'Confirmation code', 'clickuz_login' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="confirmation-code" id="confirmation-code" autocomplete="off" value="<?php echo ( ! empty( $_POST['confirmation-code'] ) ) ? esc_attr( wp_unslash( $_POST['confirmation-code'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide password-wrapper">
            <label for="reg_password"><?php esc_html_e( 'Password', 'clickuz_login' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="password" autocomplete="off" value="<?php echo ( ! empty( $_POST['password'] ) ) ? esc_attr( wp_unslash( $_POST['password'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
        </p>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide password-confirmation-wrapper">
            <label for="password_confirmation"><?php esc_html_e( 'Password confirmation', 'clickuz_login' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_confirmation" id="password_confirmation" autocomplete="off" value="<?php echo ( ! empty( $_POST['password_confirmation'] ) ) ? esc_attr( wp_unslash( $_POST['password_confirmation'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
        </p>

        <p class="woocommerce-form-row form-row">
            <input type="hidden" name="device_id" id="device_id" value="" />
            <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit click-login-btn">
                <?php esc_html_e( 'Reset', 'clickuz_login' ); ?>
            </button>
        </p>
    </form>
</div>