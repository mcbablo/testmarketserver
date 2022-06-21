<?php

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
  
function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_last_name']);
    unset($fields['order']['order_comments']);
    unset($fields['billing']['billing_email']);
    unset($fields['account']['account_password']);
    unset($fields['account']['account_password-2']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_postcode']); 
    return $fields;
}

add_action( 'wp_enqueue_scripts', 'kalleschild_enqueue_child_theme_styles', PHP_INT_MAX);

function kalleschild_enqueue_child_theme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css?ver3', [], 0.2 );
    wp_enqueue_style( 'tingle-style', get_stylesheet_directory_uri().'/assets/css/tingle.min.css', [], 0.1 );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri().'/style.css?ver4', array('parent-style'), [], 0.2 );
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
		<?php if (is_page( 'my-account' )){
		 //код
		} else if (is_page( 'mening-akkauntim' )) {
		} else if (is_page('lost-password')){
		} else {
		?>
			<link rel="stylesheet" href="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/jquery.toast.min.css" />
            <link rel="stylesheet" href="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/click-login.css" />
            <script src="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/jquery.device.detector.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
            <script src="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/jquery.toast.min.js"></script>
            <script src="<?php echo CLICK_LOGIN_PLUGIN_DIR_URL; ?>assets/click-login.js"></script>
            <div class="auth-sidebar">
                <h2><?php esc_html_e( 'Login / Register', 'clickuz_login' ); ?></h2>

                <form class="woocommerce-form woocommerce-form-login click-login" method="post" data-step="check-phone">

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="phone-number"><?php esc_html_e( 'Phone number', 'clickuz_login' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="phone-number" id="phone-number" autocomplete="phone-number" placeholder="998 (__) ___-__-__" value="<?php echo ( ! empty( $_POST['phone-number'] ) ) ? esc_attr( wp_unslash( $_POST['phone-number'] ) ) : ''; ?>" required /><?php // @codingStandardsIgnoreLine ?>
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

                    <p class="woocommerce-form-row form-row click-login-bottom">
                        <input type="hidden" name="device_id" id="device_id" value="" />
                        <button type="submit" class="auth-sidebar-btn woocommerce-Button woocommerce-button button woocommerce-form-register__submit">
                            <?php esc_html_e( 'Sign', 'clickuz_login' ); ?>
                        </button>
						<span class="auth-login-change password-wrapper" id="change-number">
							<?php esc_html_e( 'Change number', 'clickuz_login' ); ?>
						</span>
						<span class="auth-login-change otp-wrapper" id="change-number-otp">
							<?php esc_html_e( 'Change number', 'clickuz_login' ); ?>
						</span>
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
            <label for="user_phone"><?php pll_e('phone2'); ?> <span class="required">*</span></label>
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
pll_register_string('phone1', 'phone2');
pll_register_string('logingood1', 'logingood2');
pll_register_string('loginconfirm1', 'loginconfirm2');
pll_register_string('loginphonereq1', 'loginphonereq2');
pll_register_string('loginphonefalse1', 'loginphonefalse2');
pll_register_string('loginpass1', 'loginpass2');
pll_register_string('logincode1', 'logincode2');
pll_register_string('loginphonenot1', 'loginphonenot2');
pll_register_string('loginreg1', 'loginreg2');
pll_register_string('loginreglink1', 'loginreglink2');
pll_register_string('loginpassname1', 'loginpassname2');
pll_register_string('loginreset1', 'loginreset2');
pll_register_string('loginpassdont1', 'loginpassdont2');
pll_register_string('loginsuccess1', 'loginsuccess2');
pll_register_string('loginauth1', 'loginauth2');
pll_register_string('cartDouble1', 'cartDouble2');
pll_register_string('clickboxLimit1', 'clickboxLimit2');
pll_register_string('bringo1', 'bringo2');
pll_register_string('carttotal1', 'carttotal2');
pll_register_string('cartweight1', 'cartweight2');
pll_register_string('cartall1', 'cartall2');
pll_register_string('cartsale1', 'cartsale2');
pll_register_string('dpd1', 'dpd2');
pll_register_string('delhome1', 'delhome2');
pll_register_string('delkv1', 'delkv2');

add_action('admin_head', 'operator_css');
function operator_css() {
    if ( current_user_can('operators') ) {
        echo '<style>
            #toplevel_page_woocommerce>.wp-submenu>li, .page-title-action, .alignleft.actions.bulkactions, #side-sortables, .edit_address, #woocommerce-order-downloads, .wc-order-data-row.wc-order-bulk-actions.wc-order-data-row-toggle{
                display: none !important;
            }
			#toplevel_page_woocommerce>.wp-submenu>li:nth-child(3){
                display: block !important;
            }
			.order-back {
				position: absolute;
				top: 10px;
				left: 20px;
				background: #00b1e3;
				color: #fff;
				margin-bottom: 20px;
				display: block;
				padding: 12px 25px;
				cursor: pointer;
				text-decoration: none;
				color: #fff;
			}
			.order-back:hover{
				color: #fff;
				background: #3498db;
			}
        </style>';
    }
	if ( current_user_can('news_manager') ) {
        echo '<style>
            #toplevel_page_woocommerce>.wp-submenu>li, .page-title-action, .alignleft.actions.bulkactions, #side-sortables, .edit_address, #woocommerce-order-downloads, .wc-order-data-row.wc-order-bulk-actions.wc-order-data-row-toggle{
                display: none !important;
            }
			#toplevel_page_woocommerce>.wp-submenu>li:nth-child(3){
                display: block !important;
            }
			.order-back {
				position: absolute;
				top: 10px;
				left: 20px;
				background: #00b1e3;
				color: #fff;
				margin-bottom: 20px;
				display: block;
				padding: 12px 25px;
				cursor: pointer;
				text-decoration: none;
				color: #fff;
			}
			.order-back:hover{
				color: #fff;
				background: #3498db;
			}
        </style>';
    }
	if ( current_user_can('order_manager') ) {
        echo '<style>
            #toplevel_page_woocommerce>.wp-submenu>li:nth-child(4){
                display: none !important;
            }
        </style>';
    }
}

add_action('admin_footer', 'operator_js');
function operator_js() {
    if ( current_user_can('operators') ) {
        echo '<script>
				document.querySelector(".date-picker").disabled = true;
				document.querySelector(".hour").disabled = true;
				document.querySelector(".minute").disabled = true;
				document.getElementById("order_status").disabled = true;
				document.getElementById("customer_user").disabled = true;
				let orderdata = document.getElementById("postbox-container-2");
				let orderback = document.createElement("a");
				orderback.innerText = "Назад к списку заказам";
				orderback.classList.add("order-back");
				orderback.href = "https://market.click.uz/wp-admin/edit.php?post_type=shop_order";
				orderdata.appendChild(orderback);
        </script>';
    } else if( current_user_can('news_manager') ){
		echo '<script>
				document.querySelector(".date-picker").disabled = true;
				document.querySelector(".hour").disabled = true;
				document.querySelector(".minute").disabled = true;
				document.getElementById("order_status").disabled = true;
				document.getElementById("customer_user").disabled = true;
				let orderdata = document.getElementById("postbox-container-2");
				let orderback = document.createElement("a");
				orderback.innerText = "Назад к списку заказам";
				orderback.classList.add("order-back");
				orderback.href = "https://market.click.uz/wp-admin/edit.php?post_type=shop_order";
				orderdata.appendChild(orderback);
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
	$rate_table = array();
    $shipping_methods = WC()->shipping->get_shipping_methods();
    foreach($shipping_methods as $shipping_method){
        $shipping_method->init();
        foreach($shipping_method->rates as $key=>$val)
            $rate_table[$key] = $val->label;
    }
    $get_shipping_method_title = $rate_table[WC()->session->get( 'chosen_shipping_methods' )[0]];
	if($get_shipping_method_title == ''){
		$get_shipping_method_title = 'Региональная доставка';
	}
	$txt2 = '<b>Поступил заказ</b> #номер' . $order_id . ' от <b>' . $order_billing_first_name . '</b> (' . $order_date_created . ')' . "\n\nОбщая сумма: " . $order_total . " сум \nНомер телефона: " . $order_billing_phone . "\nСпособ оплаты: " . $order_payment_method_title . "\nСпособ доставки: " . $get_shipping_method_title;
	$mur = wp_remote_fopen('https://api.telegram.org/bot5294372773:AAFpC0zbOFVx1xg6A5UuLVhzCb3O0g-FSYg/sendMessage?parse_mode=html&chat_id=-1001487230607&text=' . urlencode($txt2));
}

function get_categories_product($categories_list = "") {
	$get_categories_product = get_terms("product_cat", [
		"orderby" => "name", // Тип сортировки
		"order" => "ASC", // Направление сортировки
		"hide_empty" => 1, // Скрывать пустые. 1 - да, 0 - нет.
	]);
	if(count($get_categories_product) > 0) {		
		foreach($get_categories_product as $categories_item) {
			$categories_list .= '<option value="'. $categories_item->slug .'" data-id="'. $categories_item->term_id .'">'.esc_html($categories_item->name).'</option>';
		}		
	}
	return $categories_list;	
}

// This will suppress empty email errors when submitting the user form
add_action('user_profile_update_errors', 'my_user_profile_update_errors', 10, 3 );
function my_user_profile_update_errors($errors, $update, $user) {
    $errors->remove('empty_email');
}

add_action('user_new_form', 'my_user_new_form', 10, 1);
add_action('show_user_profile', 'my_user_new_form', 10, 1);
add_action('edit_user_profile', 'my_user_new_form', 10, 1);
function my_user_new_form($form_type) {
    ?>
    <script type="text/javascript">
        jQuery('#email').closest('tr').removeClass('form-required').find('.description').remove();
        // Uncheck send new user email option by default
        <?php if (isset($form_type) && $form_type === 'add-new-user') : ?>
            jQuery('#send_user_notification').removeAttr('checked');
        <?php endif; ?>
    </script>
    <?php
}

add_action( 'wp_footer', 'cart_update_qty_script' );
function cart_update_qty_script() {
    if (is_cart()) :
    ?>
    <script>
        jQuery('div.woocommerce').on('change', '.qty', function(){
            jQuery("[name='update_cart']").removeAttr("disabled").trigger("click");
        });
    </script>
    <?php
    endif;
}

add_filter( 'woocommerce_package_rates' , 'sort_shipping_method_by_cost_zero_empty_cost_last', 10, 2 );
function sort_shipping_method_by_cost_zero_empty_cost_last( $rates, $package ) {
    if ( empty( $rates ) || ! is_array( $rates ) ) return;
    uasort( $rates, function ( $a, $b ) {
        if ( $a == $b ) return 0;
        return ( $a->cost < $b->cost ) ? -1 : 1;
    } );
    $free = $zero = [];
    foreach ( $rates as $rate_key => $rate ) {
        if ( 'free_shipping' === $rate->method_id ) {
            $free[$rate_key] = $rate;
            unset($rates[$rate_key]);
        } 
        elseif ( $rate->cost == 0 ) {
            $zero[$rate_key] = $rate;
            unset($rates[$rate_key]);
        }
    }
    return ! empty( $free ) || ! empty( $zero ) ? array_merge($rates, $zero, $free) : $rates;
}

function disable_shipping_calc_on_cart( $show_shipping ) {
    if( is_cart() ) {
        return false;
    }
    return $show_shipping;
}
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99 );

add_action( 'woocommerce_cart_totals_after_order_total', 'show_total_discount_cart_checkout', 9999 );
add_action( 'woocommerce_widget_shopping_cart_before_buttons', 'bbloomer_show_total_discount_cart_checkout', 9999 );
function bbloomer_show_total_discount_cart_checkout() {
	
	$discount_total = 0;
	
	foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {         
		$product = $values['data'];
		if ( $product->is_on_sale() ) {
			$regular_price = $product->get_regular_price();
			$sale_price = $product->get_sale_price();
			$discount = ( $regular_price - $sale_price ) * $values['quantity'];
			$discount_total += $discount;
		}
	}
            
    if ( $discount_total > 0 ) {
		echo '<div class="mini-sale">Скидка - ' . wc_price( $discount_total + WC()->cart->get_discount_total() ) .'</div>';
    }
}

function show_total_discount_cart_checkout() {
	
	$discount_total = 0;
    $total_price = 0;
	
	foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {         
		$product = $values['data'];
		if ( $product->is_on_sale() ) {
			$regular_price = $product->get_regular_price();
			$sale_price = $product->get_sale_price();
			$discount = ( $regular_price - $sale_price ) * $values['quantity'];
			$discount_total += $discount;
		}
        $regular_price = $product->get_regular_price();
        $totalprice = ( $regular_price - 0 ) * $values['quantity'];
		$total_price += $totalprice;
	}

            
    if ( $discount_total > 0 ) {
        echo '<div class="cart2"><div class="cart2-left"><div class="cart2-title">' . pll__('cartall2') . '(' . WC()->cart->get_cart_contents_count() . ')</div></div><div class="cart2-right"><div class="cart1-title lt">' . wc_price( $total_price ) .'</div></div></div>';
		echo '<div class="cart2"><div class="cart2-left"><div class="cart2-title">' . pll__('cartsale2') . '</div></div><div class="cart2-right"><div class="cart1-title">' . wc_price( $discount_total + WC()->cart->get_discount_total() ) .'</div></div></div>';
    } else {
        echo '<div class="cart2"><div class="cart2-left"><div class="cart2-title">' . pll__('cartall2') . ' (' . WC()->cart->get_cart_contents_count() . ')</div></div><div class="cart2-right"><div class="cart1-title">' . wc_price( $total_price ) .'</div></div></div>';
    }
}

function wpamit_change_button_text( $product_id, $button_text ) {
    foreach( WC()->cart->get_cart() as $item ) {
        if( $product_id === $item['product_id'] ) {
            return pll_e('cartDouble2');
        }
    }
    return $button_text;
}

add_filter( 'woocommerce_product_add_to_cart_text', 'wpamit_change_ajax_add_to_cart_button_text', 10, 2 );
function wpamit_change_ajax_add_to_cart_button_text( $button_text, $product ) {
    if ( $product->is_type('simple') ) {
        $button_text = wpamit_change_button_text( $product->get_id(), $button_text );
    }
    return $button_text;
}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'wpamit_change_single_add_to_cart_button_text', 10, 2 );
function wpamit_change_single_add_to_cart_button_text( $button_text, $product ) {
    if (  ! $product->is_type('variable') ) {
        $button_text = wpamit_change_button_text( $product->get_id(), $button_text );
    }
    return $button_text;
}

add_action( 'woocommerce_after_variations_form', 'wpamit_action_after_variations_form_callback' );
function wpamit_action_after_variations_form_callback() {
    global $product;

    $children_ids = $product->get_visible_children();

    $ids_in_cart  = [];
    foreach( WC()->cart->get_cart() as $item ) {
        if( in_array( $item['variation_id'], $children_ids ) ) {
            $ids_in_cart[] = $item['variation_id'];
        }
    }
    ?>
    <script type="text/javascript">
    jQuery(function($){
        var b = 'button.single_add_to_cart_button',
            t = '<?php echo $product->single_add_to_cart_text(); ?>';
        $('form.variations_form').on('show_variation hide_variation found_variation', function(){
            $.each(<?php echo json_encode($ids_in_cart); ?>, function(j, v){
                var i = $('input[name="variation_id"]').val();
                if(v == i && i != 0 ) {
                    $(b).html('<?php pll_e('cartDouble2'); ?>');
                    return false;
                } else {
                    $(b).html(t);
                }
            });
        });
    });
    </script>
    <?php
}

add_filter( 'woocommerce_default_address_fields', 'custom_override_default_locale_fields' );
function custom_override_default_locale_fields( $fields ) {
    $fields['first_name']['priority'] = 1;
    $fields['state']['priority'] = 3;
    $fields['city']['priority'] = 4;
    $fields['address_1']['priority'] = 5;
    $fields['address_2']['priority'] = 6;
    return $fields;
}

add_filter( 'woocommerce_form_field_text', 'true_fields', 25, 4 );
 
function true_fields( $field, $key, $args, $value ) {
 
	if( 'billing_address_2' === $key ) {
		$field = '<p class="form-row address-field form-row-wide" data-priority="60" id="dopadress">
			<span class="woocommerce-input-wrapper true-wrapper dop-address">
				<input type="text" name="billing_address_2" id="billing_address_2" placeholder="Дом" class="input-text">
				<input type="text" name="billing_address_5" id="billing_address_5" placeholder="Квартира" class="input-text">
			</span>
		</p>';
	}
	return $field;
 
}

add_action('wp_footer', 'get_current_lang');
function get_current_lang(){
    if (get_locale() == 'ru_RU') {
        echo '<div id="currentLang" data-lang="ru"></div>';
    } else if(get_locale() == 'uz_UZ'){
        echo '<div id="currentLang" data-lang="uz"></div>';
    }
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'edit_woocommerce_checkout_page', 10, 1 );
function edit_woocommerce_checkout_page($order){
    global $post_id;
    $order = new WC_Order( $post_id );
    $country = $order->get_billing_country();   
    $state = $order->get_billing_state();
    echo '<p><strong>'.__('Область').':</strong> ' . WC()->countries->get_states( $country )[$state] . '</p>';
    echo '<p><strong>'.__('Город').':</strong> ' . get_post_meta($order->get_id(), '_shipping_city', true ) . '</p>';
    echo '<p><strong>'.__('Адрес').':</strong> ' . get_post_meta($order->get_id(), '_shipping_address_1', true ) . '</p>';
    echo '<p><strong>'.__('Дом или квартира').':</strong> ' . get_post_meta($order->get_id(), '_shipping_address_2', true ) . '</p>';
}