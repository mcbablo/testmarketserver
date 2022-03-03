<?php

namespace WooMS;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * SiteHealth
 */
class SiteHealth
{

    public static $settings_page_url = 'admin.php?page=mss-settings';
    public static $wooms_check_login_password;
    public static $wooms_check_woocommerce_version_for_wooms;


    public static function init()
    {
        add_filter('site_status_tests', [__CLASS__, 'new_health_tests']);

        add_action('wp_ajax_health-check-wooms-check_login_password', [__CLASS__, 'wooms_check_login_password']);

    }

    /**
     * adding hooks for site health
     *
     * @param [type] $tests
     * @return void
     */
    public static function new_health_tests($tests)
    {

        $tests['direct']['wooms_check_woocommerce_version_for_wooms'] = [
            'test'  => [__CLASS__, 'wooms_check_woocommerce_version_for_wooms'],
        ];


        $tests['async']['wooms_check_credentials'] = [
            'test'  => 'wooms_check_login_password',
        ];

        return $tests;
    }

    /**
     * Checking version WooCommerce
     *
     * @return void
     */
    public static function wooms_check_woocommerce_version_for_wooms()
    {

        $wc_version = WC()->version;
        $result = [
            'label' => 'Проверка версии WooCommerce для работы плагина WooMS & WooMS XT',
            'status'      => 'good',
            'badge'       => [
                'label' => 'Уведомление WooMS',
                'color' => 'blue',
            ],
            'description' => sprintf('Все хорошо! Спасибо что выбрали наш плагин %s', '🙂'),
            'test' => 'wooms_check_woocommerce_version_for_wooms' // this is only for class in html block
        ];

        if (version_compare($wc_version, '3.6.0', '<=')) {
            $result['status'] = 'critical';
            $result['badge']['color'] = 'red';
            $result['actions'] = sprintf(
                '<p><a href="%s">%s</a></p>',
                admin_url('plugins.php'),
                sprintf("Обновить WooCommerce")
            );
            $result['description'] = sprintf('Ваша версия WooCommerce плагина %s. Обновите пожалуйста WooCommerce чтобы WooMS & WooMS XT работали ', $wc_version);
        }

        return $result;
    }


    /**
     * checking credentials
     *
     * @return void
     */
    public static function wooms_check_login_password()
    {
        check_ajax_referer('health-check-site-status');

        if (!current_user_can('view_site_health_checks')) {
            wp_send_json_error();
        }

        $url = 'https://online.moysklad.ru/api/remap/1.2/security/token';
        $data_api = wooms_request($url, [], 'POST');

        $result = [
            'label' => "Проверка логина и пароля МойСклад",
            'status'      => 'good',
            'badge'       => [
                'label' => 'Уведомление WooMS',
                'color' => 'blue',
            ],
            'description' => sprintf("Все хорошо! Спасибо что используете наш плагин %s", '🙂'),
            'test' => 'wooms_check_credentials' // this is only for class in html block
        ];

        if (!array_key_exists('errors', $data_api)) {
            wp_send_json_success($result);
        }

        if (array_key_exists('errors', $data_api)) {
            $result['status'] = 'critical';
            $result['badge']['color'] = 'red';
            $result['description'] = sprintf("Что то пошло не так при подключении к МойСклад", '🤔');
        }

        /**
         * 1056 is mean that login or the password is not correct
         */
        if ($data_api["errors"][0]['code'] === 1056) {
            $result['description'] = sprintf("Неверный логин или пароль от МойСклад %s", '🤔');
            $result['actions'] = sprintf(
                '<p><a href="%s">%s</a></p>',
                self::$settings_page_url,
                sprintf("Поменять доступы")
            );
        }

        set_transient('wooms_check_login_password', true, 60 * 30);

        wp_send_json_success($result);
    }

}

SiteHealth::init();
