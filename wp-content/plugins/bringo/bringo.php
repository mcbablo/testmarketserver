<?php
 
/**
 * Plugin Name: Bringo
 * Plugin URI: https://market.click.uz/
 * Description: Bringo integration Woocomerce
 * Version: 1.0.0
 * Author: Asadbek Ibragimov
 * Author URI: http://click.uz/
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /lang
 * Text Domain: bringo
 * Domain Path: /i18n/languages/
 **/
 
if ( ! defined( 'WPINC' ) ) {
    die;
}

$bringoorderid;
define( 'WC_BRINGO_PLUGIN_URL', plugin_dir_url(__FILE__) );
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
    function bringo_shipping_method()
    {
        if (!class_exists('Bringo_Shipping_Method')) {
            class Bringo_Shipping_Method extends WC_Shipping_Method
            {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct($instance_id = 0)
                {
                    $this->id = 'bringo';
                    $this->instance_id = absint($instance_id);
                    $this->method_title = __('Bringo Shipping', 'bringo');
                    $this->method_description = __('Custom Shipping Method for Bringo', 'bringo');
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
                    $this->title = pll__( 'bringo2' );
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
                            'title' => __('Enable', 'bringo'),
                            'type' => 'checkbox',
                            'description' => __('Enable this shipping.', 'bringo'),
                            'default' => 'yes'
                        ),

                        'title' => array(
                            'title' => __('Title', 'bringo'),
                            'type' => 'text',
                            'description' => __('Title to be display on site', 'bringo'),
                            'default' => __('Bringo Shipping', 'bringo')
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
                        'cost' => 25000
                    );

                    $this->add_rate($rate);

                }
            }
        }
    }

    add_action('woocommerce_shipping_init', 'bringo_shipping_method');

    function add_bringo_shipping_method($methods)
    {
        $methods['bringo'] = 'Bringo_Shipping_Method';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_bringo_shipping_method');
    
	add_action( 'woocommerce_review_order_before_payment', function() {   
        echo '<input type="hidden" id="bringo-price">';
        echo '<div class="selectBox" id="selectBringo" style="display: none"><h5 id="bringo-edit">' . esc_html__( 'Выберите адрес доставки', 'clickbox'  ) . '</h5>' . '<button class="selectClickbox" type="button" id="bringo-btn">' . esc_html__( 'Выбрать', 'clickbox' ) . '</button></div>';
    });
}

add_action( 'woocommerce_review_order_before_payment', 'bringo_script', 10, 0 );
function bringo_script() { ?>
    <script type="text/javascript">
        jQuery( function($){
            var currentLang = $('#currentLang').data('lang');
            let text1 = currentLang == 'uz' ? 'Manzilni kiriting' : 'Введите адрес';
            let text2 = currentLang == 'uz' ? 'Topish' : 'Найти';
            let text3 = currentLang == 'uz' ? 'Manzil topilmadi' : 'Адрес не найден';
            let text4 = currentLang == 'uz' ? 'Nuqtani tanlash' : 'Выбрать точку';
            let text5 = currentLang == 'uz' ? 'Qidiruv tafsilotlarini o\'chirish' : 'Сбросить поиск';
            let text6 = currentLang == 'uz' ? 'Tasdiqlash' : 'Подтвердить';
            let text7 = currentLang == 'uz' ? 'Ortga qaytish' : 'Вернуться назад';
            let text8 = currentLang == 'uz' ? 'Yopish' : 'Закрыть';
            let text9 = currentLang == 'uz' ? 'Yetkazib berish faqat Toshkent shahri bo\'ylab!' : 'Доставка только по Ташкенту!';
            let text10 = currentLang == 'uz' ? 'Qo\'shimcha ma\'lumotlar' : 'Дополнительные данные';
            let text11 = currentLang == 'uz' ? 'Uy raqami' : 'Номер дома';
            let text12 = currentLang == 'uz' ? 'Xonadon raqami' : 'Номер квартиры';
            let text13 = currentLang == 'uz' ? 'Izoh' : 'Комментарий';
            let text14 = currentLang == 'uz' ? 'Davom ettirish' : 'Продолжить';
            let text15 = currentLang == 'uz' ? 'Manzil to\'liq emas, aniqlashtirish uchun ko\'cha nomi va uy raqamini kiriting' : 'Неточный адрес, требуется уточнение, введите название улицы и номер дома';
            let text16 = currentLang == 'uz' ? 'Uy raqamini belgilang' : 'Уточните номер дома';
            let text17 = currentLang == 'uz' ? 'Manzil to\'liq emas, aniqlashtirish uchun uy raqamini kiriting' : 'Неполный адрес, требуется уточнение, введите номер дома';
            let text18 = currentLang == 'uz' ? 'Manzilni aniqlashtiring' : 'Уточните адрес';
            let text19 = currentLang == 'uz' ? 'Manzil topilmadi' : 'Адрес не найден';
            localStorage.setItem('bringo-map', 'false');
            let text20 = currentLang == 'uz' ? 'Tanlash' : 'Выбрать';
            let text21 = currentLang == 'uz' ? 'Yetkazib berish manzilini tanlang' : 'Выберите адрес доставки';
            let text22 = currentLang == 'uz' ? 'Manzilni o\'zgartirish' : 'Поменять адрес';
            let text23 = currentLang == 'uz' ? 'Sizga ma\'qul bo\'lgan yetkazib berish vaqti' : 'Предпочтительная время доставки';
            function loadingBtn(id){
                $(id).addClass('the-loading');
                $(id).prop('disabled', true);
            }
            function loadingBtnFalse(id){
                $(id).removeClass('the-loading');
                $(id).prop('disabled', false);
            }
            var bringoModal = new tingle.modal({
                footer: false,
                cssClass: ['clickbox-modal', 'bringo-modal-map'],
                closeLabel: '',
                onOpen: function(){
                    $('#the4-header').css('z-index', '-1');
                    $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                },
                onClose: function() {
                    $('#the4-header').css('z-index', '1001');
                    $('#kalles-section-toolbar_mobile').css('z-index', '1002');
                },
            });
            var bringoModal2 = new tingle.modal({
                footer: false,
                cssClass: ['clickbox-modal', 'bringo-modal-map'],
                closeLabel: '',
                onOpen: function(){
                    $('#the4-header').css('z-index', '-1');
                    $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                },
                onClose: function() {
                    $('#the4-header').css('z-index', '1001');
                    $('#kalles-section-toolbar_mobile').css('z-index', '1002');
                },
            });
            var bringoModalAddress = new tingle.modal({
                footer: false,
                cssClass: ['clickbox-modal', 'bringo-modal-tashkent'],
                closeLabel: '',
                onOpen: function(){
                    $('#the4-header').css('z-index', '-1');
                    $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                },
                onClose: function() {
                    $('#the4-header').css('z-index', '1001');
                    $('#kalles-section-toolbar_mobile').css('z-index', '1002');
                },
            });
            var bringoModalConfirm = new tingle.modal({
                footer: false,
                cssClass: ['clickbox-modal', 'bringo-modal-tashkent'],
                closeLabel: '',
                onOpen: function(){
                    $('#the4-header').css('z-index', '-1');
                    $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                },
                onClose: function() {
                    $('#the4-header').css('z-index', '1001');
                    $('#kalles-section-toolbar_mobile').css('z-index', '1002');
                },
            });
            bringoModal.setContent('<div class="bringo-header"><input type="text" id="suggest" class="input" placeholder="' + text1 + '"><button type="submit" id="bringoBtn">' + text2 + '</button></div><div id="bringo-error"><p id="notice">' + text3 + '</p><button id="changeMap" style="display: none;">' + text4 + '</button></div><div id="map"></div><div class="bringo-footer"><button id="bringo-clear">' + text5 + '</button><button id="bringo-button-select">' + text6 + '</button></div>');
            bringoModal2.setContent('<div id="bringo-map"></div><div class="bringo-map-footer"><button id="back-map">' + text7 +'</button><button id="bringo-button-select2" data-active="0">' + text8 + '</button></div>');
            bringoModalAddress.setContent('<div id="bringo-text">' + text9 + '</div><button id="bringo-close-btn">' + text8 + '</button>');
            bringoModalConfirm.setContent('<div id="bringo-text">' + text10 + '</div><div class="bringo-conf-box"><input type="text" placeholder="' + text23 +'" id="bringo-time"></div><div class="bringo-conf-box"><input type="text" class="bringo-confirm-input" id="bringo-home" placeholder="' + text11 +'"><input type="text" class="bringo-confirm-input" id="bringo-appartment" placeholder="' + text12 + '"></div><div class="bringo-conf-box"><textarea placeholder="' + text13 + '" id="bringo-confirm-textarea"></textarea></div><div class="bringo-conf-box"><button id="bringo-conform-btn">' + text14 + '</button></div>');
            function initBringo() {
                var suggestView = new ymaps.SuggestView('suggest'),map,placemark;
                // При клике по кнопке запускаем верификацию введёных данных.
                $('#bringoBtn').bind('click', function (e) {
                    geocode();
                });
                function geocode() {
                    // Забираем запрос из поля ввода.
                    var request = $('#suggest').val();
                    // Геокодируем введённые данные.
                    ymaps.geocode(request).then(function (res) {
                        var obj = res.geoObjects.get(0),
                            error, hint;

                        if (obj) {
                            // Об оценке точности ответа геокодера можно прочитать тут: https://tech.yandex.ru/maps/doc/geocoder/desc/reference/precision-docpage/
                            switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                                case 'exact':
                                    break;
                                case 'number':
                                case 'near':
                                case 'range':
                                    error = text15;
                                    hint = text16;
                                    break;
                                case 'street':
                                    error = text17;
                                    hint = text16;
                                    break;
                                case 'other':
                                default:
                                    error = text15;
                                    hint = text16;
                            }
                        } else {
                            error = text19;
                            hint = text18;
                        }
                        if (error) {
                            showError(error);
                            showMessage(hint);
                        } else {
                            showResult(obj);
                        }
                    }, function (e) {
                        console.log(e)
                    })

                }
                function showResult(obj) {
                    $('#map').css('height', '400px');
                    $('#map').css('margin', '20px 0');
                    $('#suggest').removeClass('input_error');
                    $('#bringo-error').css('display', 'none');
                    var mapContainer = $('#map'),
                        bounds = obj.properties.get('boundedBy'),
                        mapState = ymaps.util.bounds.getCenterAndZoom(
                            bounds,
                            [mapContainer.width(), mapContainer.height()]
                        ),
                        address = obj.getAddressLine(),
                        shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
                    mapState.controls = [];
                    createMap(mapState, shortAddress);
                    showMessage(address);
                    $('.bringo-footer').css('display', 'flex');
                    var cord = obj.geometry._coordinates
                    $('#bringo-button-select').attr('data-active', 1);
                    $('#bringo-button-select').attr('data-lat', cord[0]);
                    $('#bringo-button-select').attr('data-lng', cord[1]);
                    $('#bringo-button-select').attr('data-address', address);
                }
                function showError(message) {
                    localStorage.setItem('bringo-map', 'false');
                    $('#map').css('height', '0');
                    $('#map').css('margin', '0');
                    $('#suggest').addClass('input_error');
                    $('#bringo-error').css('display', 'flex');
                    $('#changeMap').css('display', 'block');
                    $('#notice').html(message);
                    $('.bringo-footer').css('display', 'none');
                    $('#bringo-button-select').attr('data-active', 0);
                    $('#bringo-button-select').attr('data-lat', '');
                    $('#bringo-button-select').attr('data-lng', '');
                    $('#billing_address_1').val('');
                    // Удаляем карту.
                    if (map) {
                        map.destroy();
                        map = null;
                    }
                }
                function createMap(state, caption) {
                    var checkMap = localStorage.getItem('bringo-map');
                    if (!map && checkMap === 'false') {
                        map = new ymaps.Map('map', state, {
                            suppressMapOpenBlock: true
                        });
                        placemark = new ymaps.Placemark(
                            map.getCenter(), {
                                preset: 'islands#redDotIconWithCaption'
                            });
                        map.geoObjects.add(placemark);
                        localStorage.setItem('bringo-map', 'true');
                    } else {
                        map.setCenter(state.center, state.zoom);
                        placemark.geometry.setCoordinates(state.center);
                        placemark.properties.set({iconCaption: caption, balloonContent: caption});
                    }
                }
                function showMessage(message) {
                    $('#message').text(message);
                }
                $('#bringo-clear').click(function(){
                    localStorage.setItem('bringo-map', 'false');
                    $('#map').css('height', '0');
                    $('#map').css('margin', '0');
                    $('.bringo-footer').css('display', 'none');
                    $('#bringo-button-select').attr('data-active', 0);
                    $('#bringo-button-select').attr('data-lat', '');
                    $('#bringo-button-select').attr('data-lng', '');
                    $('#billing_address_1').val('');
                    $('#suggest').val('');
                    if (map) {
                        map.destroy();
                        map = null;
                    }
                });
            }
            function initMap() {
                $('#bringo-map').html('');
                var myPlacemark,
                    myMap = new ymaps.Map("bringo-map", {
                    center: [41.31688073, 69.24690049],
                    zoom: 12,
                    controls: ['geolocationControl']
                    }, {suppressMapOpenBlock: true}
				);
                myMap.events.add('click', function (e) {
                    var coords = e.get('coords');
                    if (myPlacemark) {
                        myPlacemark.geometry.setCoordinates(coords);
                    }
                    else {
                        myPlacemark = createPlacemark(coords);
                        myMap.geoObjects.add(myPlacemark);
                        myPlacemark.events.add('dragend', function () {
                            getAddress(myPlacemark.geometry.getCoordinates());
                        });
                    }
                    getAddress(coords);
                    $('#bringo-button-select2').text(text6);
                    $('#bringo-button-select2').addClass('active');
                    $('#bringo-button-select2').attr('data-active', 1);
                    $('#bringo-button-select2').attr('data-lat', coords[0].toPrecision(8));
                    $('#bringo-button-select2').attr('data-lng', coords[1].toPrecision(8));
                });
                function createPlacemark(coords) {
                    return new ymaps.Placemark(coords, {
                        iconCaption: 'поиск...',
                    }, {
                        preset: 'islands#violetDotIconWithCaption',
                        draggable: false
                    });
                }
                function getAddress(coords) {
                    myPlacemark.properties.set('iconCaption', 'поиск...');
                    ymaps.geocode(coords).then(function (res) {
                        var firstGeoObject = res.geoObjects.get(0);
                        var addressGeo = firstGeoObject.getAddressLine();
                        myPlacemark.properties
                            .set({
                                iconCaption: firstGeoObject.getAddressLine()
                            });
                        $('#bringo-button-select2').attr('data-address', addressGeo)
                    });
                }
            }
            let bringoBtn = document.getElementById('bringo-btn');
            if(bringoBtn){
                bringoBtn.addEventListener('click', function(){
                    $('#bringo-map').html('');
                    bringoModal.open();
                    $('#the4-header').css('z-index', '-1');
                    $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                    $('#bringo-map').show();
                    ymaps.ready(initBringo);
                    focusSuggest();
                });
            }
            $(document).on('click', '#bringo-button-select', function() {
                bringoModal.close();
                $('#bringo-btn').text(text20);
                $('#bringo-edit').text(text21);
                var activeBtn = $(this).attr('data-active');
                var data = {
                    bringo_merchant_id: 10268,
                    merchant: {
                        lat: "41.32666",
                        lng: "69.31330"
                    },
                    client: {
                        lat: $(this).attr('data-lat'),
                        lng: $(this).attr('data-lng')
                    },
                    items: [
                        {
                            name: $('#clickbox_productname').val(),
                            weight: $('#product-weight').val(),
                            height: $('#product-height').val(),
                            width: $('#product-width').val(),
                            length: $('#product-length').val()
                        },
                    ]
                };
                if(activeBtn == 1){
                    $.ajax({
                        type: 'POST',
                        url: "https://api.bringo.uz/api/v1/calculateDeliveryPrice",
                        data: JSON.stringify(data),
                        dataType: "json",
                        success: function(result) {
                            if(result.success == true){
                                $('#bringo-btn').text(text22);
                                $('#bringo-edit').text($('#bringo-button-select').attr('data-address'));
                                $('#bringo_user_address').val($('#bringo-button-select').attr('data-address'));
                                $('#billing_address_1').val($('#bringo-button-select').attr('data-address'));
                                $('#bringo_user_lat').val($('#bringo-button-select').attr('data-lat'));
                                $('#bringo_user_lng').val($('#bringo-button-select').attr('data-lng'));
                                bringoModalConfirm.open();
                            } else if(result.success == false) {
                                $('#bringo-btn').text(text20);
                                $('#bringo-edit').text(text21);
                                $('#bringo_user_address').val();
                                $('#billing_address_1').val('');
                                $('#bringo-button-select').attr('data-active', 0);
                                bringoModalAddress.open();
                            }
                        }
                    });
                } else {
                    $('#billing_address_1').val('');
                }
            });
            $(document).on('click', '#bringo-button-select2', function() {
                bringoModal2.close();
                $('#bringo-btn').text(text20);
                $('#bringo-edit').text(text21);
                var activeBtn = $(this).attr('data-active');
                var data = {
                    bringo_merchant_id: 10268,
                    merchant: {
                        lat: "41.32666",
                        lng: "69.31330"
                    },
                    client: {
                        lat: $(this).attr('data-lat'),
                        lng: $(this).attr('data-lng')
                    },
                    items: [
                        {
                            name: $('#clickbox_productname').val(),
                            weight: $('#product-weight').val(),
                            height: $('#product-height').val(),
                            width: $('#product-width').val(),
                            length: $('#product-length').val()
                        },
                    ]
                };
                if(activeBtn == 1){
                    $.ajax({
                        type: 'POST',
                        url: "https://api.bringo.uz/api/v1/calculateDeliveryPrice",
                        data: JSON.stringify(data),
                        dataType: "json",
                        success: function(result) {
                            if(result.success == true){
                                $('#bringo-btn').text(text22);
                                $('#bringo-edit').text($('#bringo-button-select2').attr('data-address'));
                                $('#bringo_user_address').val($('#bringo-button-select2').attr('data-address'));
                                $('#billing_address_1').val($('#bringo-button-select2').attr('data-address'));
                                $('#bringo_user_lat').val($('#bringo-button-select').attr('data-lat'));
                                $('#bringo_user_lng').val($('#bringo-button-select').attr('data-lng'));
                                bringoModalConfirm.open();
                            } else if(result.success == false) {
                                $('#bringo-btn').text(text20);
                                $('#bringo-edit').text(text21);
                                $('#bringo_user_address').val();
                                $('#billing_address_1').val('');
                                $('#bringo-button-select2').attr('data-active', 0);
                                bringoModalAddress.open();
                            }
                        }
                    });
                } else {
                    $('#billing_address_1').val('');
                }
            });
            $('#bringo-close-btn').click(function(){
                bringoModalAddress.close();
            });
            $('#bringo-conform-btn').click(function(){
                bringoModalConfirm.close();
                var comment1 = $('#bringo-home').val();
                var comment2 = $('#bringo-appartment').val();
                var comment3 = $('#bringo-confirm-textarea').val();
                var comment4 = $('#bringo-time').val();
                var comment = 'Номер дома: ' + comment1 + ', номер квартиры: ' + comment2 + ', комментарий: ' + comment3 + ', предпочтительная время доставки: ' + comment4;
                $('#bringo-button-select').attr('data-comment', comment);
                $('#bringo-button-select2').attr('data-comment', comment);
                $('#bringo_user_comment').val(comment);
            });
            $('#changeMap').click(function(){
                $('#bringo-map').html('');
                bringoModal.close();
                bringoModal2.open();
                $('#the4-header').css('z-index', '-1');
                $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                $('#bringo-map').show();
                ymaps.ready(initMap);
            });
            $('#back-map').click(function(){
                bringoModal2.close();
                bringoModal.open();
            });
            function focusSuggest(){
                setTimeout(() => {
                    $('#suggest').focus();
                }, 100);
            }
        });
    </script>
   <?php
}

function bringo_register_status( $order_statuses ){
    $order_statuses['wc-bringo-send'] = array(                                 
        'label' => _x( 'Готов к отправке Bringo', 'Order status', 'woocommerce' ),
        'public' => false,                                 
        'exclude_from_search' => false,                                 
        'show_in_admin_all_list' => true,                                 
        'show_in_admin_status_list' => true,                                 
        'label_count' => _n_noop( 'Готов к отправке Bringo <span class="count">(%s)</span>', 'Готов к отправке Bringo <span class="count">(%s)</span>', 'woocommerce' ),                              
    );      
    return $order_statuses;
}
add_filter( 'woocommerce_register_shop_order_post_statuses', 'bringo_register_status' );

function bringo_show_status( $order_statuses ) {      
    $order_statuses['wc-bringo-send'] = _x( 'Готов к отправке Bringo', 'Order status', 'woocommerce' );       
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'bringo_show_status' );

function bringo_getshow_status( $bulk_actions ) {
    $bulk_actions['mark_bringo-send'] = 'Изменить статус на Готов к отправке Bringo';
    return $bulk_actions;
}
add_filter( 'bulk_actions-edit-shop_order', 'bringo_getshow_status' );

function bringo_checkout_add( $checkout) {
    woocommerce_form_field( 'bringo_user_lng', array(
        'type'          => 'hidden',
        'class'         => array('bringo_user_lng'),
        ), $checkout->get_value( 'bringo_user_lng' ));
    
    woocommerce_form_field( 'bringo_user_lat', array(
        'type'          => 'hidden',
        'class'         => array('bringo_user_lat'),
        ), $checkout->get_value( 'bringo_user_lat' ));
    
    woocommerce_form_field( 'bringo_user_comment', array(
        'type'          => 'hidden',
        'class'         => array('bringo_user_comment'),
        ), $checkout->get_value( 'bringo_user_comment' ));
}
add_action( 'woocommerce_after_order_notes', 'bringo_checkout_add' );

function bringo_checkout_update( $order_id ) {
    if ( ! empty( $_POST['bringo_user_lng'] ) ) {
        update_post_meta( $order_id, 'bringo_client_lng', sanitize_text_field( $_POST['bringo_user_lng'] ) );
    }
    if ( ! empty( $_POST['bringo_user_lat'] ) ) {
        update_post_meta( $order_id, 'bringo_client_lat', sanitize_text_field( $_POST['bringo_user_lat'] ) );
    }
    if ( ! empty( $_POST['bringo_user_comment'] ) ) {
        update_post_meta( $order_id, 'bringo_user_comment', sanitize_text_field( $_POST['bringo_user_comment'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'bringo_checkout_update' );

add_action( 'woocommerce_after_shipping_rate', 'bringo_custom_fields', 20, 2 );
function bringo_custom_fields( $method, $index ) {
    if( ! is_checkout()) return;

    $clickbox_method_shipping = 'bringo';

    if( $method->id != $clickbox_method_shipping ) return;

    $chosen_method_id = WC()->session->chosen_shipping_methods[ $index ];

    if($chosen_method_id == $clickbox_method_shipping ):

        echo '<div class="clickbox-fields">';

        woocommerce_form_field( 'bringo_user_address' , array(
            'type'          => 'hidden',
            'class'         => array(),
            'required'      => true,
        ), WC()->checkout->get_value( 'bringo_user_address' ));

        echo '</div>';
    endif;
}

add_action('woocommerce_checkout_process', 'bringo_checkout_process');
function bringo_checkout_process() {
    if( isset( $_POST['bringo_user_address'] ) && empty( $_POST['bringo_user_address'] ) )
        wc_add_notice( esc_html__( 'Пожалуйста выберите адрес доставки', 'clickbox' ), "error" );
}

add_action( 'woocommerce_checkout_update_order_meta', 'bringo_update_order_meta', 30, 1 );
function bringo_update_order_meta( $order_id ) {
    if( isset( $_POST['bringo_user_address'] ))
        update_post_meta( $order_id, 'bringo_client_address', sanitize_text_field( $_POST['bringo_user_address'] ) );
}

function bringo_order_sendpay( $order_id, $order ) {
    $order = new WC_Order( $order_id );
    $order_data = $order->get_data();
    $get_phone = $order_data['billing']['phone'];
    $user_phone = preg_replace("/[^,.0-9]/", '', $get_phone);
    $shipping_method = @array_shift($order->get_shipping_methods());
    $shipping_method_name = $shipping_method['method_id'];
    $shipping_method_price = $shipping_method['total'];
    $bringo_client_lng = $order->get_meta('bringo_client_lng');
    $bringo_client_lat = $order->get_meta('bringo_client_lat');
    $bringo_client_address = $order->get_meta('bringo_client_address');
    $bringo_user_comment = $order->get_meta('bringo_user_comment');
    $products;
    foreach($order->get_items() as $item_id => $item): 
        $product = $item->get_product();
        $products = array(
            "name" => $product->get_name(),
            "weight" => $product->get_weight(),
            "height" => $product->get_height(),
            "width" => $product->get_width(),
            "length" => $product->get_length()
        );
    endforeach;
    try {
        $url = "https://api.bringo.uz/api/v1/merchant/createOrder";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $dataSend = array(
            "bringo_merchant_id" => 10268,
            "payment_id" => 21,
            "merchant" => array(
                "lng" => "69.31330",
                "lat" => "41.32666",
                "merchant_phone_number" => "998712310881",
                "merchant_address" =>  "Toshkent",
                "workdays" => array(
                    "mon" => "09:00-18:00",
                    "tue" => "09:00-18:00",
                    "wed" => "09:00-18:00",
                    "thu" => "09:00-18:00",
                    "fri" => "09:00-18:00"
                ),
            ),
            "client" => array(
                "lng" => $bringo_client_lng,
                "lat" => $bringo_client_lat,
                "client_phone_number" => $user_phone,
                "user_address" => $bringo_client_address
            ),
            "items" => array(
                $products
            ),
            "total_price" => $order->get_total(),
            "comment" => $bringo_user_comment
        );
        $data_string = json_encode($dataSend);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        $bringoRes = json_decode($resp);
        update_post_meta($order_id, 'bringo_order_id', esc_attr($bringoRes->data->id));
        curl_close($curl);
    } catch (\Exception $exception) {
    }
}
add_action( 'woocommerce_order_status_bringo-send', 'bringo_order_sendpay', 20, 2 );


function bringo_get_status($order){
    $bringoorderid = $order->get_meta('bringo_order_id');
    if (!$bringoorderid) {
        $status_bringo = 'Нет заказа в BRINGO';
    } else {
        echo '<strong>ID заказа BRINGO: </strong>' . $bringoorderid . '<br />';
        $url = file_get_contents("https://api.bringo.uz/api/merchant/getOrder?order_id=" . $bringoorderid . "&merchant_id=10268");
        $data = json_decode($url);
        echo '<strong>Статус заказа BRINGO: </strong>' . $data->data->status_name;
    }
    
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'bringo_get_status', 10, 1 );