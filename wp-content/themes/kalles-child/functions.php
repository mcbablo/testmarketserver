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
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css', [], 0.2 );
    wp_enqueue_style( 'tingle-style', get_stylesheet_directory_uri().'/assets/css/tingle.min.css', [], 0.1 );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri().'/style.css', array('parent-style'), [], 0.2 );
}

function my_scripts_method_child() {
    wp_enqueue_script(
        'tingle-script',
        get_stylesheet_directory_uri() . '/assets/js/tingle.min.js',
        [], 0.1, true
    );
    wp_enqueue_script(
        'custom-script-child',
        get_stylesheet_directory_uri() . '/app.js',
        array( 'jquery' ),
		[], 0.2, true
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

if ( ! function_exists( 't4_woo_login_register_form' ) ) {
	function t4_woo_login_register_form() {

		ob_start();
		?>
		<?php if( is_page( 'my-account' ) ){
		 //код
		} else if( is_page( 'mening-akkauntim' ) ) {
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
                            <?php esc_html_e( 'Sign', 'clickuz_login' ); ?>
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
        <script>$('#user_phone').inputmask('\\9\\9\\8 (99) 999-99-99');</script>
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

add_action( 'admin_menu', 'remove_some_menus', 999 ); 
function remove_some_menus() {
    if ( current_user_can('operator') ) {
        global $submenu;
        remove_menu_page('woocommerce-marketing');
        remove_menu_page('woocommerce');
        remove_menu_page('profile.php');
        remove_menu_page('index.php');
    }
}

add_action('admin_head', 'operator_css');
function operator_css() {
    if ( current_user_can('operator') ) {
        echo '<style>
            #menu-posts-shop_order>.wp-submenu>li:last-child, .page-title-action, .woocommerce-message, #wp-admin-bar-new-content, .alignleft.actions.bulkactions, .check-column, #wc_actions, #shipping_address, #language_ru, #language_uz, .column-shipping_address, .column-wc_actions, .column-language_ru, .column-language_uz, .wc-order-item-meta, .wc-backbone-modal-main footer{
                display: none !important;
            }
            .wc-backbone-modal-main{
                padding-bottom: 0 !important;
            }
            .type-shop_order, .order_number, .order_date, .order_status, .billing_address, .order_total{
                cursor: default !important;
            }
        </style>';
    }
}

add_action('admin_footer', 'operator_js');
function operator_js() {
    if ( current_user_can('operator') ) {
        echo '<script>
                let viewes = document.querySelectorAll(".order-view");
                viewes.forEach(view => {
                    view.addEventListener("click", function handleClick(event){
                        event.preventDefault();
                    })
                });
                let orders = document.querySelectorAll(".type-shop_order");
                orders.forEach(order => {
                    order.onclick = function() {
                        order.querySelector(".order-view").href="#";
                        return false;
                    }
                });
        </script>';
    }
}

add_filter( 'woocommerce_package_rates', 'hide_specific_shipping_method_based_on_user_role', 30, 2 );
function hide_specific_shipping_method_based_on_user_role( $rates, $package ) {
    $excluded_role = "staffer"; // User role to be excluded
    $shipping_id = 'local_pickup'; // Shipping rate to be removed
    foreach( $rates as $rate_key => $rate ){
        if( $rate->method_id === $shipping_id ){
          if(!current_user_can( $excluded_role )){
              unset($rates[$rate_key]);
              break;
          }
        }
    }
    return $rates;
}

add_filter( 'action_scheduler_retention_period', function() { return DAY_IN_SECONDS * 1; } );

add_action( 'woocommerce_new_order', 'new_order_send_tg', 1, 1 );
function new_order_send_tg( $order_id ) {
	$order = wc_get_order( $order_id );
	$order_data = $order->get_data();
	$order_total = $order_data['total'];
	$order_date_created = $order_data['date_created']->date('Y-m-d H:i');
	$order_billing_first_name = $order_data['billing']['first_name'];
	$order_billing_phone = $order_data['billing']['phone'];
	$order_payment_method_title = $order_data['payment_method_title'];
	$txt2 = '<b>Поступил заказ</b> #номер' . $order_id . ' от <b>' . $order_billing_first_name . '</b> (' . $order_date_created . ')' . "\n\nОбщая сумма: " . $order_total . " сум \nНомер телефона: " . $order_billing_phone . "\nСпособ оплаты: " . $order_payment_method_title;
	$mur = wp_remote_fopen('https://api.telegram.org/bot5294372773:AAFpC0zbOFVx1xg6A5UuLVhzCb3O0g-FSYg/sendMessage?parse_mode=html&chat_id=-1001487230607&text=' . urlencode($txt2));
}