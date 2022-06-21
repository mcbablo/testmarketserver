<?php
 
/**
 * Plugin Name: DPD
 * Plugin URI: https://market.click.uz/
 * Description: DPD integration Woocomerce
 * Version: 1.0.0
 * Author: Asadbek Ibragimov
 * Author URI: http://click.uz/
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /lang
 * Text Domain: DPD
 * Domain Path: /i18n/languages/
 **/
 
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'WC_DPD_PLUGIN_URL', plugin_dir_url(__FILE__) );
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
    function dpd_shipping_method()
    {
        if (!class_exists('Dpd_Shipping_Method')) {
            class Dpd_Shipping_Method extends WC_Shipping_Method
            {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct($instance_id = 0)
                {
                    $this->id = 'dpd';
                    $this->instance_id = absint($instance_id);
                    $this->method_title = __('Dpd Shipping', 'dpd');
                    $this->method_description = __('Custom Shipping Method for Dpd', 'dpd');
                    $this->supports = array(
                        'shipping-zones',
                        'settings'
                    );
                    // Availability & Countries
                    $this->availability = 'including';
                    $this->countries = array(
                        'UZ',
                    );

                    $this->init();

                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->title = pll__( 'dpd2' );
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void
                 */
                function init_form_fields()
                {

                    $this->form_fields = array(

                        'enabled' => array(
                            'title' => __('Enable', 'dpd'),
                            'type' => 'checkbox',
                            'description' => __('Enable this shipping.', 'dpd'),
                            'default' => 'yes'
                        ),

                        'title' => array(
                            'title' => __('Title', 'dpd'),
                            'type' => 'text',
                            'description' => __('Title to be display on site', 'dpd'),
                            'default' => __('Dpd Shipping', 'dpd')
                        ),

                    );

                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping($package = array())
                {

                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => 40000
                    );

                    $this->add_rate($rate);

                }
            }
        }
    }

    add_action('woocommerce_shipping_init', 'dpd_shipping_method');

    function add_dpd_shipping_method($methods)
    {
        $methods['dpd'] = 'Dpd_Shipping_Method';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_dpd_shipping_method');
}

function dpd_register_status( $order_statuses ){
    $order_statuses['wc-dpd-send'] = array(                                 
        'label' => _x( 'Готов к отправке Dpd', 'Order status', 'woocommerce' ),
        'public' => false,                                 
        'exclude_from_search' => false,                                 
        'show_in_admin_all_list' => true,                                 
        'show_in_admin_status_list' => true,                                 
        'label_count' => _n_noop( 'Готов к отправке Dpd <span class="count">(%s)</span>', 'Готов к отправке Dpd <span class="count">(%s)</span>', 'woocommerce' ),                              
    );      
    return $order_statuses;
}
add_filter( 'woocommerce_register_shop_order_post_statuses', 'dpd_register_status' );

function dpd_show_status( $order_statuses ) {      
    $order_statuses['wc-dpd-send'] = _x( 'Готов к отправке Dpd', 'Order status', 'woocommerce' );       
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'dpd_show_status' );

function dpd_getshow_status( $bulk_actions ) {
    $bulk_actions['mark_dpd-send'] = 'Изменить статус на Готов к отправке Dpd';
    return $bulk_actions;
}
add_filter( 'bulk_actions-edit-shop_order', 'dpd_getshow_status' );

// Cron
register_activation_hook( __FILE__, 'activation_geting_course_dollar' );
register_deactivation_hook( __FILE__, 'deactivation_geting_course_dollar' );
add_action( 'geting_course_dollar', 'get_real_course_dollar' );

if( ! wp_next_scheduled( 'geting_course_dollar' ) ) {
	wp_schedule_event( time(), 'twicedaily', 'geting_course_dollar');
}

function activation_geting_course_dollar() {
	wp_clear_scheduled_hook( 'geting_course_dollar' );
	wp_schedule_event( time(), 'twicedaily', 'geting_course_dollar');
}

function deactivation_geting_course_dollar() {
	wp_clear_scheduled_hook('geting_course_dollar');
}

function get_real_course_dollar(){
    $client = new SoapClient ("http://ws.dpd.ru/services/geography2?wsdl");
    $arData['auth'] = array(
        'clientNumber' => '1230000306',
        'clientKey' => '929156FD82E59C594A47C0EA54CF62E65948369F',
    );
    $arData['countryCode'] = 'UZ';
    $arRequest['request'] = $arData;
    $ret = $client->getCitiesCashPay($arRequest);
    function stdToArray($obj){
        $rc = (array)$obj;
        foreach($rc as $key=>$item){
            $rc[$key]= (array)$item;
            foreach($rc[$key] as $keys=>$items){
                $rc[$key][$keys]= (array)$items;
            }
        }
        $contents = json_encode($rc['return']);
        $upload_dir = plugin_dir_path( __DIR__ ).'/dpd/cities.json';
        file_put_contents($upload_dir, $contents );
        return $rc;
    }
    $mass = stdToArray($ret);
    foreach($mass AS $key => $key1){
        foreach($key1 AS $cityid => $city){
            if (in_array($cityid, $city)) {
                $id = $city['cityId']; 
                return $id;
            }
        }
    } 
}