<?php

/**
 * Plugin Name: CLICKBox
 * Description: CLICKBox des
 * Version: 1.0.0
 * Author: Asadbek Ibragimov
 * Author URI: http://click.uz
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: clickbox
 * Domain Path: /i18n/languages/
**/

if ( ! defined( 'WPINC' ) ) {
    die;
}

function log_me($message) {
	if (WP_DEBUG === true) {
		if (is_array($message) || is_object($message)) {
			error_log(print_r($message, true));
		} else {
			error_log($message);
		}
	}
}

// Путь к плагину
define( 'WC_CLICKBOX_PLUGIN_URL', plugin_dir_url(__FILE__) );

// Проверка что Woocommerce активирован
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    //Регистрация метода доставки
    function clickbox_shipping_method() {
        if ( ! class_exists( 'CLICKBox_Shipping_Method' ) ) {
            class CLICKBox_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id                 = 'clickbox'; 
                    $this->method_title       = __( 'CLICKBox Shipping', 'clickbox' );  
                    $this->method_description = __( 'Custom Shipping Method for CLICKBox', 'clickbox' ); 
					load_plugin_textdomain( 'clickbox', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages/' );

                    $this->availability = 'including';
                    
                    $this->countries = array('UZ');

                    $this->init();

                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'CLICKBox Shipping', 'clickbox' );
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Load the settings API
                    $this->init_form_fields(); 
                    $this->init_settings(); 

                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }

                /**
                 * Define settings field for this shipping
                 * @return void 
                 */
                function init_form_fields() { 

                    $this->form_fields = array(

                        'enabled' => array(
                          'title' => __( 'Включить', 'clickbox' ),
                          'type' => 'checkbox',
                          'description' => __( 'Включить this shipping.', 'clickbox' ),
                          'default' => 'yes'
                        ),

                        'title' => array(
                        'title' => __( 'Заголовок', 'clickbox' ),
                          'type' => 'text',
                          'description' => __( 'Название которое отображается', 'clickbox' ),
                          'default' => __( 'CLICKBox', 'clickbox' )
                          )
                     );

                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package = array() ) {
                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => 15000
                    );
                    $this->add_rate( $rate );
                   
                }
            }
        }
    }
    add_action( 'woocommerce_shipping_init', 'clickbox_shipping_method' );

    //Добавление метода доставки
    function add_clickbox_shipping_method( $methods ) {
        $methods['clickbox'] = 'CLICKBox_Shipping_Method';
        return $methods;
    }
    add_filter( 'woocommerce_shipping_methods', 'add_clickbox_shipping_method' );

    // Подключение стилей и скриптов
    function clickbox_scripts_and_styles() {
        remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
        if (is_checkout() ) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('inputmask_js', WC_CLICKBOX_PLUGIN_URL . 'assets/js/jquery.inputmask.bundle.js', [], 0.1, true);
            wp_enqueue_script('googlemaps_js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAFTWLzPcF1hRcc4X5q0fqG_w-FAgCZlrk&libraries=geometry', [], 0.1, true);
            wp_enqueue_script('yandexmap_js', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU', [], 0.2, true);
            wp_enqueue_script('clickbox_js', WC_CLICKBOX_PLUGIN_URL . 'assets/js/app.js?ver2', [], 0.2, true);
            wp_enqueue_style('clickbox_css', WC_CLICKBOX_PLUGIN_URL . 'assets/css/style.css?ver2', [], 0.2);
        }
    }
    add_action( 'wp_enqueue_scripts', 'clickbox_scripts_and_styles' );

    // Валидация по габаритам
    function clickbox_validate_order( $posted )   {
        $packages = WC()->shipping->get_packages();
        foreach ( $packages as $i => $package ) {
            $CLICKBox_Shipping_Method = new CLICKBox_Shipping_Method();
            $length = 0;
            $width = 0;
            $height = 0;
            $itemBox = null;
            global $post;
            $args = array( 'posts_per_page' => -1 , 'post_type' => 'boxs');
            $myposts = get_posts( $args );
            foreach($package['contents'] as $item_id => $values) { 
                $_product = $values['data'];
                if($_product->get_length()){
                    $allLength = $_product->get_length();
                }
                if($_product->get_height()){
                    $allHeight = $_product->get_height();
                }
                if($_product->get_width()){
                    $width = $width + $_product->get_width() * $values['quantity'];
                }
                if($_product->get_length()){
                    $length = max($allLength, $length);
                }
                if($_product->get_length()){
                    $height = max($allHeight, $height);
                }
            }
            $availableBox = null;
            foreach ( $myposts as $key => $post ) : 
                setup_postdata( $post );
                if($length <= get_field('length') && $width <= get_field('width') && $height <= get_field('height')){
                    $availableBox = get_field('pochtomat');
                }
            endforeach;
            if($availableBox >= 1) {
                echo '<script>document.getElementById("clickbox-box").value = ' .  $availableBox . ';</script>';
            } else {
                echo '<script>document.getElementById("clickbox-box").value = 0;</script>';
                $message = sprintf( __( '<strong>Ошибка.</strong> Вы выбрали недопустимое количество товаров для доставки через почтоматы, просим вас уменьшить количество товаров и оформить их отдельным заказом', 'clickbox' ), $CLICKBox_Shipping_Method->title );
                $messageType = "error";
                if( ! wc_has_notice( $message, $messageType ) ) {
                    wc_add_notice( $message, $messageType );
                }
            }
        }
    }
    add_action( 'woocommerce_review_order_before_cart_contents', 'clickbox_validate_order' , 10 );

    // При входе фильтрация по городу и ролю
//     function blm_action_after_shipping_rate($method, $index ) {
//         if( is_cart() ) {
//             return;
//         }
//         $shipping_state = WC()->customer->get_shipping_state();
//         $user_role = WC()->customer->get_role();
//         if($shipping_state === '01' && $user_role !== 'staffer'){
//             add_filter('woocommerce_order_button_html', 'remove_order_button_html' );
//         }
//     }
//     add_action( 'woocommerce_after_shipping_rate', 'blm_action_after_shipping_rate', 20, 2 );

    // При смене фильтрация по городу и ролю
// 	function shipping_rates_for_specific_states( $rates, $package ) {
// 		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
// 			return;
// 		$excluded_states = array(
// 			'01'
// 		);
// 		$destination_state = $package['destination']['state'];
// 		if( ! in_array( $destination_state, $excluded_states ) ) {
//             unset( $rates['clickbox'] );
// 		}
// 		return $rates;
// 	}
//     add_filter( 'woocommerce_package_rates', 'shipping_rates_for_specific_states', 10, 2 );

    // Удаление кнопки
    // add_filter('woocommerce_order_button_html', 'remove_order_button_html' );
    // function remove_order_button_html( $button ) {
    //     $targeted_shipping_class = 0;
    //     $found = false;
    //     foreach( WC()->cart->get_cart() as $cart_item ) {
    //         if( $cart_item['data']->get_shipping_class_id() == $targeted_shipping_class ) {
    //             $found = true; 
    //             break;
    //         }
    //     }
    //     if( $found )
    //         $button = '';
    //     return $button;
    // }

    // Вывод выбора почтоматов
    add_action( 'woocommerce_review_order_before_payment', function() {        
        echo '<div class="selectBox" id="selectClickbox"><h5 id="clickbox-edit">' . esc_html__( 'Выберите пункт выдачи заказов', 'clickbox' ) . '</h5>' . '<button class="selectClickbox" type="button" id="clickbox-btn">' . esc_html__( 'Выбрать', 'clickbox' ) . '</button></div>';
    });

    // Вывод кнопки
    add_action( 'woocommerce_review_order_after_payment', function() {        
        echo '<button type="button" id="create-order">' . esc_html__( 'Оплатить', 'clickbox' ) . ' </button>';
    });

    function deleteSelect() { ?>
        <script type="text/javascript">
            jQuery(function($) {
                var selector = '#selectClickbox';            
                $( 'form.checkout' ).on( 'change', 'input[name^="shipping_method"]', function() {
                    var c_s_m = $( this ).val();                    
                    if ( c_s_m.indexOf( 'local_pickup' ) >= 0 ) {
                        $( selector ).hide();   
                    } else {
                        $( selector ).show();               
                    }
                });
            });
        </script>
        <?php
    }
    add_action( 'woocommerce_review_order_before_payment', 'deleteSelect', 10, 0 );

    function deleteButton() { ?>
        <script type="text/javascript">
            jQuery(function($) {
                var selector = '#create-order';            
                $( 'form.checkout' ).on( 'change', 'input[name^="shipping_method"]', function() {
                    var c_s_m = $( this ).val();                    
                    if ( c_s_m.indexOf( 'local_pickup' ) >= 0 ) {
                        $( selector ).hide();   
                    } else {
                        $( selector ).show();               
                    }
                });
            });
        </script>
        <?php
    }
    add_action( 'woocommerce_review_order_after_payment', 'deleteButton', 10, 0 );

    add_filter( 'woocommerce_package_rates' , 'businessbloomer_sort_shipping_methods', 10, 2 );
    function businessbloomer_sort_shipping_methods( $rates, $package ) {
        if ( empty( $rates ) ) return;
        if ( ! is_array( $rates ) ) return;
        uasort( $rates, function ( $a, $b ) { 
            if ( $a == $b ) return 0;
            return ( $a->cost > $b->cost ) ? -1 : 1; 
        } );
        return $rates;
    }

    add_filter('woocommerce_order_button_html', 'disable_place_order_button_html' );
    function disable_place_order_button_html( $button ) {
        $chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
        $chosen_shipping_method  = reset($chosen_shipping_methods);
        $chosen_shipping_method  = explode(':', $chosen_shipping_method );
        if( $chosen_shipping_method[0] == 'clickbox' ) {
            $button = '';
        }
        return $button;
    }

    add_action( 'woocommerce_before_cart', 'auto_select_free_shipping_by_default' );
    add_action( 'woocommerce_before_checkout_form', 'auto_select_free_shipping_by_default' );
    function auto_select_free_shipping_by_default() {
        if ( isset(WC()->session) && ! WC()->session->has_session() )
            WC()->session->set_customer_session_cookie( true );

        if ( strpos( WC()->session->get('chosen_shipping_methods')[0], 'clickbox' ) !== false )
            return;

        foreach( WC()->session->get('shipping_for_package_0')['rates'] as $key => $rate ){
            if( $rate->method_id === 'clickbox' ){
                // Set "Free shipping" method
                WC()->session->set( 'chosen_shipping_methods', array($rate->id) );
                return;
            }
        }
    }

    // Добавление полей в оформление заказа
    function clickbox_checkout_add( $checkout) {
        woocommerce_form_field( 'clickbox_address', array(
            'type'          => 'hidden',
            'class'         => array('clickbox_address'),
            ), $checkout->get_value( 'clickbox_address' ));

        woocommerce_form_field( 'clickbox-box', array(
            'type'          => 'hidden',
            'class'         => array('clickbox-box'),
            ), $checkout->get_value( 'clickbox-box' ));

        woocommerce_form_field( 'clickbox_rateid', array(
            'type'          => 'hidden',
            'class'         => array('clickbox_rateid'),
            ), $checkout->get_value( 'clickbox_rateid' ));

        woocommerce_form_field( 'clickbox_orderid', array(
            'type'          => 'hidden',
            'class'         => array('clickbox_orderid'),
            ), $checkout->get_value( 'clickbox_orderid' ));
        
    }
    add_action( 'woocommerce_after_order_notes', 'clickbox_checkout_add' );

    // Отправка ID заказа
    function clickbox_checkout_update( $order_id ) {
        if ( ! empty( $_POST['clickbox_address'] ) ) {
            update_post_meta( $order_id, 'clickbox_order_id', sanitize_text_field( $_POST['clickbox_orderid'] ) );
        }
        if ( ! empty( $_POST['clickbox-box'] ) ) {
            update_post_meta( $order_id, 'clickbox_box_size', sanitize_text_field( $_POST['clickbox-box'] ) );
        }
    }
    add_action( 'woocommerce_checkout_update_order_meta', 'clickbox_checkout_update' );

    /// Регистрация нового статуса
    function clickbox_register_status( $order_statuses ){
        $order_statuses['wc-clickbox-send'] = array(                                 
            'label' => _x( 'Передано в CLICKBox', 'Order status', 'woocommerce' ),
            'public' => false,                                 
            'exclude_from_search' => false,                                 
            'show_in_admin_all_list' => true,                                 
            'show_in_admin_status_list' => true,                                 
            'label_count' => _n_noop( 'Передано в CLICKBox <span class="count">(%s)</span>', 'Передано в CLICKBox <span class="count">(%s)</span>', 'woocommerce' ),                              
        );      
        return $order_statuses;
    }
    add_filter( 'woocommerce_register_shop_order_post_statuses', 'clickbox_register_status' );

    // Показ нового статуса
    function clickbox_show_status( $order_statuses ) {      
        $order_statuses['wc-clickbox-send'] = _x( 'Передано в CLICKBox', 'Order status', 'woocommerce' );       
        return $order_statuses;
    }
    add_filter( 'wc_order_statuses', 'clickbox_show_status' );

    // Изменение нового статуса
    function clickbox_getshow_status( $bulk_actions ) {
        $bulk_actions['mark_clickbox-send'] = 'Изменить статус на Передано в CLICKBox';
        return $bulk_actions;
    }
    add_filter( 'bulk_actions-edit-shop_order', 'clickbox_getshow_status' );

    // Вывод статуса с CLICKBox
    function clickbox_get_status($order){
        $clickboxorderid = $order->get_meta('clickbox_order_id');
        $context = stream_context_create(array(
                'http' => array(
                    'header'  => "Authorization: Basic MTo1ZGU0ZmI3MjcyMGFl")
            )
        );
        $status_click_box = '';
        $status_click_box_size = '';
        $data = '';
        if (!$order->get_meta('clickbox_order_id')) {
            $status_click_box = "Заказа нет в кликбокс";
        } else {
            try {
                $url = file_get_contents("https://app.clickbox.uz/api/merchant/bookings/" . $order->get_meta('clickbox_order_id'), true, $context);
                $data = json_decode($url);
                $status_array = [
                    'waiting_payment' => 'Ожидание оплаты',
                    'waiting_package' => 'Ожидание посылки',
                    'queue' => 'В очереди',
                    'waiting_driver' => 'Ожидние водителя',
                    'delivered' => 'Посылка в почтомате',
                    'delivering' => 'Посылка в пути',
                    'done' => 'Выполнено',
                    'canceled' => 'Отменено',
                    'expired' => 'Время истекло',
                    'failed' => 'Срыв заказа'
                ];
                foreach ($status_array as $key => $stat) {
                    if ($key == $data->data->status) {
                        $status_click_box = $stat;
                    }
                }
            }catch (Exception $e) {
                $status_click_box = 'Заказ в CLICK BOX не найден';
            }
        }
        echo '<strong>ID заказа CLICKBOX: </strong>' . $order->get_meta('clickbox_order_id');
        echo ' - <a href="https://app.clickbox.uz/admin/orders/' . $order->get_meta('clickbox_order_id') . '" target="_blank">Посмотреть </a>' . '<br />';
        echo '<strong>Статус заказа в CLICKBOX: </strong>' . $status_click_box . '<br />';
        if (!$order->get_meta('clickbox_box_size')) {
            $status_click_box_size = "Коробка не передана";
        } else {
            if($clickboxorderid = 1){
                $status_click_box_size = "S";
            } else if($clickboxorderid = 2){
                $status_click_box_size = "M";
            } else if($clickboxorderid = 3){
                $status_click_box_size = "L";
            } else if($clickboxorderid = 4){
                $status_click_box_size = "XL";
            }
        }
        echo '<strong>Подходящяя упаковка: </strong>' . $status_click_box_size;
    }
    add_action( 'woocommerce_admin_order_data_after_billing_address', 'clickbox_get_status', 10, 1 );

    // Отправка в API после изменения статуса
    function clickbox_order_sendpay( $order_id, $order ) {
        try {
            if ($order->get_meta('clickbox_order_id')) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.clickbox.uz/api/merchant/bookings/'.$order->get_meta('clickbox_order_id').'/pay',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                        'Authorization: Basic MTo1ZGU0ZmI3MjcyMGFl'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }
        } catch (\Exception $exception) {
        }
           
    }
    add_action( 'woocommerce_order_status_clickbox-send', 'clickbox_order_sendpay', 20, 2 );  
	
	function clickbox_order_sendcancel( $order_id, $order ) {
        try {
            if ($order->get_meta('clickbox_order_id')) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.clickbox.uz/api/merchant/bookings/'.$order->get_meta('clickbox_order_id').'/cancel',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                        'Authorization: Basic MTo1ZGU0ZmI3MjcyMGFl'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }
        } catch (\Exception $exception) {
            
        }  
    }
    add_action( 'woocommerce_order_status_cancelled', 'clickbox_order_sendcancel', 20, 2 ); 
    
    function display_product_name() {
        foreach ( WC()->cart->get_cart() as $cart_item ) {
            $product = $cart_item['data'];
            if(!empty($product)){
                echo '<input id="clickbox_productname" type="hidden" value="' . $product->get_name() . '">';
            }
        }
    }
    add_action('woocommerce_before_checkout_form', 'display_product_name');

    function register_box_post_types(){
        register_post_type( 'boxs', [
            'labels' => [
                'name'               => 'Коробки', // основное название для типа записи
                'singular_name'      => 'Коробка', // название для одной записи этого типа
                'add_new'            => 'Добавить коробку', // для добавления новой записи
                'add_new_item'       => 'Добавление коробки', // заголовка у вновь создаваемой записи в админ-панели.
                'edit_item'          => 'Редактирование коробки', // для редактирования типа записи
                'new_item'           => 'Новая коробка', // текст новой записи
                'view_item'          => 'Смотреть коробку', // для просмотра записи этого типа.
                'search_items'       => 'Искать коробку', // для поиска по этим типам записи
                'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
                'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
                'menu_name'          => 'Коробки', // название меню
            ],
            'description'         => '',
            'public'              => true,
        ] );
    }
    add_action( 'init', 'register_box_post_types' );
}