jQuery(function ($) {
    var d = $.fn.deviceDetector;
    var isLoading = false;    
    $('.click-login-btn').removeClass('the4-loading');
    $('.auth-sidebar-btn').removeClass('the4-loading');
    $('.click-login-btn').prop('disabled', false);
    $('.auth-sidebar-btn').prop('disabled', false);
	$('.the4-login-register').click(function(){
		$('.click-login').find('#phone-number').focus();
	});
    var check_phone = function ($form) {
        var $phone = $form.find('#phone-number');
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');

        if (phone.toString().trim() === '') {
            $.toast({
                heading: 'Ошибка',
                text: 'Введите номер телефона',
                showHideTransition: 'slide',
                icon: 'error'
            });
            $phone.focus();
            return false;
        }

        var regex = new RegExp("^998([93]{1})([01345789]{1})([0-9]{7})$");

        if (!regex.test(phone.toString().trim())) {
            $.toast({
                heading: 'Ошибка',
                text: 'Номер телефона неверный',
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $phone.focus();
            return false;
        }

        if (phone.length === 12) {
            isLoading = true;
            $('.click-login-btn').addClass('the4-loading');
            $('.auth-sidebar-btn').addClass('the4-loading');
            $('.click-login-btn').prop('disabled', true);
            $('.auth-sidebar-btn').prop('disabled', true);
            $.post(
                '/wp-admin/admin-ajax.php',
                {
                    'action': 'check_phone',
                    'params': {
                        'phone_number': phone,
                        'device_info': d.getBrowserName(),
                        'device_name': d.getOsName() + ' ' + d.getOsVersionString(),
                        'device_type': d.isMobile() ? 4 : 4,
                        'imei': '',
                        'app_version': d.getBrowserVersion()
                    }
                },
                function (response) {
                    isLoading = false;
                    $('.click-login-btn').removeClass('the4-loading');
                    $('.auth-sidebar-btn').removeClass('the4-loading');
                    $('.click-login-btn').prop('disabled', false);
                    $('.auth-sidebar-btn').prop('disabled', false);

                    $form.addClass(response['status']);

                    if (response['status'] == 'registered') {
                        $form.attr('data-step', 'check-password');
                        $.toast({
                            heading: 'Пароль',
                            text: 'Введите пароль',
                            showHideTransition: 'slide',
                            bgColor: '#7f8c8d',
                            textColor: '#fff',
                            loaderBg: '#3C4242',
                            icon: 'info'
                        });
                        $form.find('#password').focus();
                    } else {
                        if (!response['error']) {
                            $form.attr('data-step', 'check-otp');
                            $.toast({
                                heading: 'Код',
                                text: 'Введите код подтверждения',
                                showHideTransition: 'slide',
                                bgColor: '#7f8c8d',
                                textColor: '#fff',
                                loaderBg: '#3C4242',
                                icon: 'info'
                            });
                            $form.find('#confirmation-code').focus();
                            $form.find('#device_id').val(response['result']['result']['device_id']);
                            $form.find('#phone-number').attr('readonly', 'readonly');
                            $form.find('[type=submit]').text('Подтвердить');
                        } else {
                            $.toast({
                                heading: 'Ошибка',
                                text: response.error.message,
                                showHideTransition: 'slide',
                                bgColor: '#E74C3C',
                                textColor: '#fff',
                                loaderBg: '#9A3328',
                                icon: 'error'
                            });
                        }
                    }
                }, 'json');
        }

    };

    var check_reset_phone = function ($form) {
        var $phone = $form.find('#phone-number');
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');

        if (phone.toString().trim() === '') {
            $.toast({
                heading: 'Ошибка',
                text: 'Введите номер телефона',
                showHideTransition: 'slide',
                icon: 'error'
            });
            $phone.focus();
            return false;
        }

        var regex = new RegExp("^998([93]{1})([01345789]{1})([0-9]{7})$");

        if (!regex.test(phone.toString().trim())) {
            $.toast({
                heading: 'Ошибка',
                text: 'Номер телефона неверный',
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $phone.focus();
            return false;
        }

        if (phone.length === 12) {
            isLoading = true;
            $('.click-login-btn').addClass('the4-loading');
            $('.click-login-btn').prop('disabled', true);
            $.post(
                '/wp-admin/admin-ajax.php',
                {
                    'action': 'check_reset_phone',
                    'params': {
                        'phone_number': phone,
                        'device_info': d.getBrowserName(),
                        'device_name': d.getOsName() + ' ' + d.getOsVersionString(),
                        'device_type': d.isMobile() ? 4 : 4,
                        'imei': '',
                        'app_version': d.getBrowserVersion()
                    }
                },
                function (response) {
                    isLoading = false;
                    $('.click-login-btn').removeClass('the4-loading');
                    $('.auth-sidebar-btn').removeClass('the4-loading');
                    $('.click-login-btn').prop('disabled', false);
                    $('.auth-sidebar-btn').prop('disabled', false);

                    $form.addClass(response['status']);

                    if (response['status'] == 'registered') {
                        $form.attr('data-step', 'check-reset-otp');
                        $.toast({
                            heading: 'Код',
                            text: 'Введите код подтверждения',
                            showHideTransition: 'slide',
                            bgColor: '#7f8c8d',
                            textColor: '#fff',
                            loaderBg: '#3C4242',
                            icon: 'info'
                        });
                        $form.find('#confirmation-code').focus();
                        $form.find('#device_id').val(response['result']['result']['device_id']);
                        $form.find('#phone-number').attr('readonly', 'readonly');
                        $form.find('[type=submit]').text('Подтвердить');
                    } else {
                        if (!response['error']) {
                            $.toast({
                                heading: 'Ошибка',
                                text: 'Вы еще не зарегистрированы',
                                showHideTransition: 'slide',
                                bgColor: '#E74C3C',
                                textColor: '#fff',
                                loaderBg: '#9A3328',
                                icon: 'error'
                            });
                        } else {
                            $.toast({
                                heading: 'Ошибка',
                                text: response.error.message,
                                showHideTransition: 'slide',
                                bgColor: '#E74C3C',
                                textColor: '#fff',
                                loaderBg: '#9A3328',
                                icon: 'error'
                            });
                        }
                    }
                }, 'json');
        }

    };

    var check_otp = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var sms_code = $form.find('#confirmation-code').val();
        var device_id = $form.find('#device_id').val();

        if (sms_code.toString().trim() == '') {
            $.toast({
                heading: 'Ошибка',
                text: 'Введите код подтверждения',
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $form.find('#confirmation-code').focus();
            return false;
        }
        isLoading = true;
        $('.click-login-btn').addClass('the4-loading');
        $('.auth-sidebar-btn').addClass('the4-loading');
        $('.click-login-btn').prop('disabled', true);
        $('.auth-sidebar-btn').prop('disabled', true);
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                'action': 'check_otp',
                'sms_code': sms_code,
                'params': {
                    'phone_number': phone,
                    'device_id': device_id
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                if (!response['error']) {
                    $form.attr('data-step', 'register');
                    $.toast({
                        heading: 'Регистрация',
                        text: 'Зарегистрируйте пароль и имя для вашего аккаунта',
                        showHideTransition: 'slide',
                        bgColor: '#7f8c8d',
                        textColor: '#fff',
                        loaderBg: '#3C4242',
                        icon: 'info'
                    });
                    $form.find('#password').focus();
                    $form.find('[type=submit]').text('Зарегистрироваться');
                } else {
                    $.toast({
                        heading: 'Ошибка',
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                }
            },
            'json');
    };

    var check_reset_otp = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var sms_code = $form.find('#confirmation-code').val();
        var device_id = $form.find('#device_id').val();
        if (sms_code.toString().trim() == '') {
            $.toast({
                heading: 'Ошибка',
                text: 'Введите код подтверждения',
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $form.find('#confirmation-code').focus();
            return false;
        }
        isLoading = true;
        $('.click-login-btn').addClass('the4-loading');
        $('.click-login-btn').prop('disabled', true);
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                'action': 'check_otp',
                'sms_code': sms_code,
                'params': {
                    'phone_number': phone,
                    'device_id': device_id
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                if (!response['error']) {
                    $form.attr('data-step', 'reset');
                    $.toast({
                        heading: 'Восстановление',
                        text: 'Установите пароль',
                        showHideTransition: 'slide',
                        bgColor: '#7f8c8d',
                        textColor: '#fff',
                        loaderBg: '#3C4242',
                        icon: 'info'
                    });
                    $form.find('#password').focus();
                    $form.find('[type=submit]').text('Восстановить');
                } else {
                    $.toast({
                        heading: 'Ошибка',
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                }
            },
            'json');
    };

    var check_password = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var password = $form.find('#password').val();
        if (password.toString().trim() == '') {
            $.toast({
                heading: 'Ошибка',
                text: 'Введите пароль',
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $form.find('#password').focus();
            return false;
        }

        isLoading = true;
        $('.click-login-btn').addClass('the4-loading');
        $('.auth-sidebar-btn').addClass('the4-loading');
        $('.click-login-btn').prop('disabled', true);
        $('.auth-sidebar-btn').prop('disabled', true);
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                'action': 'click_login_auth',
                'params': {
                    'phone_number': phone,
                    'password': password
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                if (!response['error']) {
                    window.location.reload();
                } else {
                    $.toast({
                        heading: 'Ошибка',
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error',
                        hideAfter: false
                    });
                }
            },
            'json');

    };

    var register = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var password = $form.find('#password').val();
        var confirmation = $form.find('#password_confirmation').val();
        var display_name = $form.find('#display_name').val();
        if (password !== confirmation) {
            $.toast({
                heading: 'Ошибка',
                text: 'Пароли не соответствуют',
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $form.find('#password').focus();
            return;
        }
        isLoading = true;
        $('.click-login-btn').addClass('the4-loading');
        $('.auth-sidebar-btn').addClass('the4-loading');
        $('.click-login-btn').prop('disabled', true);
        $('.auth-sidebar-btn').prop('disabled', true);
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                'action': 'click_login_register',
                'params': {
                    'phone_number': phone,
                    'password': password,
                    'display_name': display_name
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                if (!response['error']) {
                    window.location.reload();
                } else {
                    $.toast({
                        heading: 'Ошибка',
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                }
            },
            'json');
    };

    var reset = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var password = $form.find('#password').val();
        var confirmation = $form.find('#password_confirmation').val();
        var display_name = $form.find('#display_name').val();
        if (password !== confirmation) {
            $.toast({
                heading: 'Ошибка',
                text: 'Пароли не соответствуют',
                showHideTransition: 'slide',
                bgColor: '#E74C3C',
                textColor: '#fff',
                loaderBg: '#9A3328',
                icon: 'error'
            });
            $form.find('#password').focus();
            return;
        }
        isLoading = true;
        $('.click-login-btn').addClass('the4-loading');
        $('.auth-sidebar-btn').addClass('the4-loading');
        $('.click-login-btn').prop('disabled', true);
        $('.auth-sidebar-btn').prop('disabled', true);
        $.post(
            '/wp-admin/admin-ajax.php',
            {
                'action': 'click_login_reset',
                'params': {
                    'phone_number': phone,
                    'password': password,
                    'display_name': display_name
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                if (!response['error']) {
                    window.location.replace("https://market.click.uz/");
                } else {
                    $.toast({
                        heading: 'Ошибка',
                        text: response.error.message,
                        showHideTransition: 'slide',
                        bgColor: '#E74C3C',
                        textColor: '#fff',
                        loaderBg: '#9A3328',
                        icon: 'error'
                    });
                }
            },
            'json');
    };

    $(function () {
        $('.click-login #phone-number').inputmask('\\9\\9\\8 (99) 999-99-99');
        $('.click-reset #phone-number').inputmask('\\9\\9\\8 (99) 999-99-99');
		$('#change-number').click(function(){
            $('form.click-login').attr('data-step', 'check-phone');
        });
		$('#change-number-otp').click(function(){
            $('form.click-login').attr('data-step', 'check-phone');
        });
        $('form.click-login').on('submit', function () {   

            var $form = $(this);

            var step = $form.attr('data-step');

            switch (step) {
                case 'check-phone':
                    check_phone($form);
                    break;
                case 'check-otp':
                    check_otp($form);
                    break;
                case 'check-password':
                    check_password($form);
                    break;
                case 'register':
                    register($form);
                    break;
            }

            return false;
        });

        $('form.click-reset').on('submit', function () {   

            var $form = $(this);

            var stepReset = $form.attr('data-step');

            switch (stepReset) {
                case 'check-reset-phone':
                    check_reset_phone($form);
                    break;
                case 'check-reset-otp':
                    check_reset_otp($form);
                    break;
                case 'reset':
                    reset($form);
                    break;
            }

            return false;
        });

    });

});