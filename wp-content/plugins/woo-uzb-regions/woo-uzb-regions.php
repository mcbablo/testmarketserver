<?php
/**
 * Plugin Name:       WooCommerce Uzbekistan Regions
 * Plugin URI:        https://clickmarket.uz/
 * Description:       Плагин под WooCommerce.  Подключает регионы для Узбекистана в настройках доставки и на странице оформления заказа
 * Version:           1.0
 * Author:            Asadbek Ibragimov
 * Author URI:        https://asadbek-ibragimov.uz/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:
 * Domain Path:
 *
 * WC requires at least: 3.3.0
 * WC tested up to: 3.3.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'woocommerce_states', 'awrr_states_uzbekistan' );
function awrr_states_uzbekistan( $states ) {
	$states['UZ'] = array(
		'01' => 'Город Ташкент',
		'24' => 'Сырдарьинская',
		'3' => 'Андижанская',
		'6' => 'Бухарская',
		'8' => 'Джизакская',
		'10' => 'Кашкадарьинская',
		'12' => 'Навоийская',
		'14' => 'Наманганская',
		'35' => 'Каракалпакстан',
		'18' => 'Самаркандская',
		'27' => 'Ташкентская',
		'22' => 'Сурхандарьинская',
		'33' => 'Хорезмская',
		'30' => 'Ферганская',
	);
	return $states;
}

add_filter( 'wc_city_select_cities', 'awrr_regions_cities' );
function awrr_regions_cities( $cities ) {
	$json = file_get_contents(plugin_dir_path( __DIR__ ).'/dpd/cities.json');
	$obj = json_decode($json);
	$results = $obj;
	$citiesAll = array();
	foreach($results as $result){
		$regioncode = $result->regionCode;
		$citycode = $result->cityCode;
		$cityname = $result->cityName;
		$citiesAll[$regioncode][$citycode] = $cityname;
	}
	$cities['UZ'] = array(
		'01' => array(
			'01-01' => 'Весь город'
		),
		'24' => $citiesAll['24'],
		'3' => $citiesAll['3'],
		'6' => $citiesAll['6'],
		'8' => $citiesAll['8'],
		'10' => $citiesAll['10'],
		'12' => $citiesAll['12'],
		'14' => $citiesAll['14'],
		'35' => $citiesAll['35'],
		'18' => $citiesAll['18'],
		'27' => $citiesAll['27'],
		'22' => $citiesAll['22'],
		'33' => $citiesAll['33'],
		'30' => $citiesAll['30'],
	);
	return $cities;
}