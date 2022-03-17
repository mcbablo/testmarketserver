<?php

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
  
function custom_override_checkout_fields( $fields ) {
//   unset($fields['billing']['billing_first_name']);// имя
  unset($fields['billing']['billing_last_name']);// фамилия
  unset($fields['billing']['billing_company']); // компания
  unset($fields['billing']['billing_address_1']);//
  unset($fields['billing']['billing_address_2']);//
  unset($fields['billing']['billing_city']);
  unset($fields['billing']['billing_postcode']);
  // unset($fields['billing']['billing_country']);
  // unset($fields['billing']['billing_state']);
  //unset($fields['billing']['billing_phone']);
  unset($fields['order']['order_comments']);
  unset($fields['billing']['billing_email']);
  unset($fields['account']['account_username']);
  unset($fields['account']['account_password']);
  unset($fields['account']['account_password-2']);
  unset($fields['billing']['billing_company']);// компания
  unset($fields['billing']['billing_postcode']);// индекс 
  // unset($fields['shipping']['shipping_country']); ////удаляем! тут хранится значение страны доставки
    return $fields;
}

add_action( 'wp_enqueue_scripts', 'kalleschild_enqueue_child_theme_styles', PHP_INT_MAX);

function kalleschild_enqueue_child_theme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri().'/style.css', array('parent-style') );
}

function my_scripts_method_child() {
    wp_enqueue_script(
        'custom-script-child',
        get_stylesheet_directory_uri() . '/app.js',
        array( 'jquery' )
    );
}

add_action( 'wp_enqueue_scripts', 'my_scripts_method_child' );

add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);
function change_existing_currency_symbol( $currency_symbol, $currency ) {
    switch( $currency ) {
        case 'UZS':           
            if(ICL_LANGUAGE_CODE=='ru'){
                $currency_symbol = 'сум'; 
            } else if(ICL_LANGUAGE_CODE=='uz'){
                $currency_symbol = 'so\'m';  
            } else{
                $currency_symbol = 'uzs';  
            }
        break;            
    }
    return $currency_symbol;
}

add_filter( 'woocommerce_package_rates', 'hide_specific_shipping_method_based_on_user_role', 30, 2 );
function hide_specific_shipping_method_based_on_user_role( $rates, $package ) {
    $excluded_role = "staffer"; // User role to be excluded
    $shipping_id = 'local_pickup'; // Shipping rate to be removed
    $shipping_id2 = 'clickbox'; // Shipping rate to be removed
    foreach( $rates as $rate_key => $rate ){
        if( $rate->method_id === $shipping_id ){
          if(!current_user_can( $excluded_role )){
              unset($rates[$rate_key]);
              break;
          }
        } else if($rate->method_id === $shipping_id2){
            if(current_user_can( $excluded_role )){
                unset($rates[$rate_key]);
                break;
            }
        }
    }
    return $rates;
}

if ( ! function_exists( 't4_woo_login_register_form' ) ) {
	function t4_woo_login_register_form() {

		ob_start();
		?>
		<?php if( is_page( 'my-account' ) ){
		 //код
		} else {?>
			<link rel="stylesheet" href="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/click-login.css" />
            <script src="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/jquery.device.detector.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
            <script src="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/click-login.js"></script>
            <div class="auth-sidebar">
                <h2><?php esc_html_e( 'Login / Register', 'clickuz_login' ); ?></h2>

                <form class="woocommerce-form woocommerce-form-login click-login" method="post" data-step="check-phone">

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="phone-number"><?php esc_html_e( 'Phone number', 'clickuz_login' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="phone-number" id="phone-number" autocomplete="phone-number" placeholder="998 (__) ___-__-__" value="<?php echo ( ! empty( $_POST['phone-number'] ) ) ? esc_attr( wp_unslash( $_POST['phone-number'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
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
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide name-wrapper">
                        <label for="display_name"><?php esc_html_e( 'Имя и фамилия', 'kalles' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="display_name" id="display_name" autocomplete="off" value="<?php echo ( ! empty( $_POST['display_name'] ) ) ? esc_attr( wp_unslash( $_POST['display_name'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                    </p>

                    <p class="woocommerce-form-row form-row">
                        <input type="hidden" name="device_id" id="device_id" value="" />
                        <button type="submit" class="auth-sidebar-btn woocommerce-Button woocommerce-button button woocommerce-form-register__submit">
                            <?php esc_html_e( 'Send', 'clickuz_login' ); ?>
                        </button>
                    </p>
                </form>
            </div>
		<?php
		}
		echo ob_get_clean();
	}
}

add_action( 'woocommerce_edit_account_form', 'add_phone_to_edit_account_form' );
function add_phone_to_edit_account_form() {
    $user = wp_get_current_user();
    ?>
        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-last">
            <label for="user_phone">Номер телефона <span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="user_phone" id="user_phone" value="<?php echo esc_attr( $user->user_login ); ?>" readonly />
        </p>
    <?php
}

add_filter( 'woocommerce_checkout_fields' , 'kia_checkout_field_defaults', 20 );
function kia_checkout_field_defaults( $fields ) {
    $user = wp_get_current_user();
    $phone = $user ? $user->user_login : '';
    $username = $user ? $user->display_name : '';
    $fields['billing']['billing_phone']['default'] = $phone;
    $fields['billing']['billing_first_name']['default'] = $username;
    return $fields;
}

add_filter('woocommerce_save_account_details_required_fields', 'wc_save_account_details_required_fields' );
function wc_save_account_details_required_fields( $required_fields ){
    unset( $required_fields['account_first_name'] );
    unset( $required_fields['account_last_name'] );    
    unset( $required_fields['account_email'] );    
    return $required_fields;
}

add_filter('woocommerce_checkout_fields', 'njengah_edit_checkout_placeholders_labels');
function njengah_edit_checkout_placeholders_labels($fields){
	if (get_locale() == 'ru_RU') {
		$fields['billing']['billing_first_name']['placeholder'] = 'Введите имя и фамилию';
	    $fields['billing']['billing_first_name']['label'] = 'Имя и фамилия';
	} else if(get_locale() == 'uz_UZ'){
		$fields['billing']['billing_first_name']['placeholder'] = 'Ismingiz va familiyangizni kiriting';
	    $fields['billing']['billing_first_name']['label'] = 'Ismingiz va familiyangiz';
	}
    return $fields;
}

add_filter( 'woocommerce_new_customer_data', 'customer_username_based_on_firstname', 20, 1 );
function customer_username_based_on_firstname( $new_customer_data ){
    $wrong_user_names = array( 'info', 'contact' );
    if(isset($_POST['billing_first_name'])) $first_name = $_POST['billing_first_name'];
    if( ! empty($first_name) && ! in_array( $_POST['billing_first_name'], $wrong_user_names ) ){
        $new_customer_data['user_login'] = sanitize_user( $first_name );
    }
    return $new_customer_data;
}

add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ){
	unset( $menu_links['edit-address'] ); // Addresses
	//unset( $menu_links['dashboard'] ); // Remove Dashboard
	//unset( $menu_links['payment-methods'] ); // Remove Payment Methods
	//unset( $menu_links['orders'] ); // Remove Orders
	unset( $menu_links['downloads'] ); // Disable Downloads
	//unset( $menu_links['edit-account'] ); // Remove Account details tab
	//unset( $menu_links['customer-logout'] ); // Remove Logout link
	return $menu_links;
}

function my_custom_show_sale_price_at_cart( $old_display, $cart_item, $cart_item_key ) {

	/** @var WC_Product $product */
	$product = $cart_item['data'];

	if ( $product ) {
		return $product->get_price_html();
	}

	return $old_display;

}
add_filter( 'woocommerce_cart_item_price', 'my_custom_show_sale_price_at_cart', 10, 3 );

pll_register_string('relatedtitle1', 'relatedtitle2');
pll_register_string('recenttitle1', 'recenttitle2');
pll_register_string('tashkent1', 'tashkent2');
pll_register_string('selectPochtomat1', 'selectPochtomat2');
pll_register_string('copyright1', 'copyright2');
pll_register_string('support1', 'support2');