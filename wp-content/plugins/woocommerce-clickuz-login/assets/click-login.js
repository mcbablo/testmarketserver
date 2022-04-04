jQuery(function ($) {
    var d = $.fn.deviceDetector;
    var isLoading = false;    
    $('.click-login-btn').removeClass('the4-loading');
    $('.auth-sidebar-btn').removeClass('the4-loading');
    $('.click-login-btn').prop('disabled', false);
    $('.auth-sidebar-btn').prop('disabled', false);
    $('.click-login').find('#phone-number').focus();
    $('.phone-reset').focus();
    var authModal = new tingle.modal({
        footer: false,
        closeMethods: [],
        closeLabel: false,
        cssClass: ['auth-modal']
    });
    authModal.setContent('<div class="auth-modal-box"><div id="auth-modal-text"></div></div><button id="auth-modal-done">' + translate.good + '</button>');
    $('#auth-modal-done').click(function(){
        authModal.close();
    });
    var check_phone = function ($form) {
        var $phone = $form.find('#phone-number');
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');

        if (phone.toString().trim() === '') {
            $('#auth-modal-text').html('<h4 class="error">' + translate.req + '</h4>');
            authModal.open();
            $('#auth-modal-done').click(function(){
                $phone.focus();
            });
            return false;
        }

        var regex = new RegExp("^998([93]{1})([01345789]{1})([0-9]{7})$");

        if (!regex.test(phone.toString().trim())) {
            $('#auth-modal-text').html('<h4 class="error">' + translate.phonefalse + '</h4>');
            authModal.open();
            $('#auth-modal-done').click(function(){
                $phone.focus();
            });
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
                        $('#auth-modal-text').html('<h4 class="done">' + translate.pass + '</h4>');
                        authModal.open();
                        $('#auth-modal-done').click(function(){
                            $('#password').focus();
                        });
                    } else {
                        if (!response['error']) {
                            $('#auth-modal-text').html('<h4 class="done">' + translate.phonecode + '</h4>');
                            authModal.open();
                            $('#auth-modal-done').click(function(){
                                $('#confirmation-code').focus();
                            });
                            $form.attr('data-step', 'check-otp');
                            $form.find('#device_id').val(response['result']['device_id']);
                            $form.find('#phone-number').attr('readonly', 'readonly');
                            $form.find('[type=submit]').text(translate.confirm);
                        } else {
                            $('#auth-modal-text').html('<h4 class="error">' + response.error.message + '</h4>');
                            authModal.open();
                        }
                    }
                }, 'json');
        }

    };

    var check_phone_reset = function ($form) {
        var $phone = $form.find('#phone-number');
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');

        if (phone.toString().trim() === '') {
            $('#auth-modal-text').html('<h4 class="error">' + translate.req + '</h4>');
            authModal.open();
            $('#auth-modal-done').click(function(){
                $phone.focus();
            });
            return false;
        }

        var regex = new RegExp("^998([93]{1})([01345789]{1})([0-9]{7})$");

        if (!regex.test(phone.toString().trim())) {
            $('#auth-modal-text').html('<h4 class="error">' + translate.phonefalse + '</h4>');
            authModal.open();
            $('#auth-modal-done').click(function(){
                $phone.focus();
            });
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
                    'action': 'check_phone_reset',
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
                        $('#auth-modal-text').html('<h4 class="done">' + translate.phonecode + '</h4>');
                        authModal.open();
                        $('#auth-modal-done').click(function(){
                            $('#confirmation-code').focus();
                        });
                        $form.attr('data-step', 'check-otp-reset');
                        $form.find('#device_id').val(response['result']['device_id']);
                        $form.find('#phone-number').attr('readonly', 'readonly');
                        $form.find('[type=submit]').text(translate.confirm);
                    } else {
                        if (!response['error']) {
                            authModal.setContent('<div class="auth-modal-box"><div id="auth-modal-text">' + translate.phonenot + '</div></div><a href="' + translate.reqlink +'" class="auth-modal-btn">' + translate.reg +'</a>');
                            authModal.open();
                        } else {
                            $('#auth-modal-text').html('<h4 class="error">' + response.error.message + '</h4>');
                            authModal.open();
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
            $('#auth-modal-text').html('<h4 class="done">' + translate.phonecode + '</h4>');
            authModal.open();
            $('#auth-modal-done').click(function(){
                $('#confirmation-code').focus();
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
                $('#auth-modal-text').html('<h4 class="done">Введите пароль и имя</h4>');
                authModal.open();
                $('#auth-modal-done').click(function(){
                    $('#password').focus();
                });
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                if (!response['error']) {
                    $form.attr('data-step', 'register');
                    $form.find('[type=submit]').text('Зарегистрироваться');
                } else {
                    authModal.setContent('<div class="auth-modal-box"><div id="auth-modal-text"><h4 class="error">' + response.error.message + '</h4></div></div><button id="clear1" class="auth-modal-btn">' + translate.good + '</button>');
                    authModal.open();
                    $('#clear1').click(function(){
                        authModal.close();
                        $('#confirmation-code').val('');
                        $('#confirmation-code').focus();
                    });
                }
            },
            'json');


    };

    var check_otp_reset = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var sms_code = $form.find('#confirmation-code').val();
        var device_id = $form.find('#device_id').val();

        if (sms_code.toString().trim() == '') {
            $('#auth-modal-text').html('<h4 class="done">' + translate.phonecode + '</h4>');
            authModal.open();
            $('#auth-modal-done').click(function(){
                $('#confirmation-code').focus();
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
                'action': 'check_otp_reset',
                'sms_code': sms_code,
                'params': {
                    'phone_number': phone,
                    'device_id': device_id
                }
            },
            function (response) {
                $('#auth-modal-text').html('<h4 class="done">' + translate.pass + '</h4>');
                authModal.open();
                $('#auth-modal-done').click(function(){
                    $('#password').focus();
                });
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                if (!response['error']) {
                    $form.attr('data-step', 'reset');
                    $form.find('[type=submit]').text(translate.passreset);
                } else {
                    authModal.setContent('<div class="auth-modal-box"><div id="auth-modal-text"><h4 class="error">' + response.error.message + '</h4></div></div><button id="clear1" class="auth-modal-btn">' + translate.good +'</button>');
                    authModal.open();
                    $('#clear1').click(function(){
                        authModal.close();
                        $('#confirmation-code').val('');
                        $('#confirmation-code').focus();
                    });
                }
            },
            'json');


    };

    var check_password = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var password = $form.find('#password').val();
        if (password.toString().trim() == '') {
            $('#auth-modal-text').html('<h4 class="done">' + translate.pass +'</h4>');
            authModal.open();
            $('#auth-modal-done').click(function(){
                $('#password').focus();
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
                    authModal.setContent('<div class="auth-modal-box"><div id="auth-modal-text"><h4 class="error">' + response.error.message + '</h4></div></div><button id="clear1" class="auth-modal-btn">' + translate.good +'</button>');
                    authModal.open();
                    $('#clear1').click(function(){
                        authModal.close();
                        $('#password').val('');
                        $('#password').focus();
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
            $('#auth-modal-text').html('<h4 class="error">' + translate.passdont + '</h4>');
            authModal.open();
            $('#auth-modal-done').click(function(){
                $('#password').focus();
            });
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
                    authModal.setContent('<div class="auth-modal-box"><div id="auth-modal-text"><h4 class="error">' + response.error.message + '</h4></div></div><button id="clear1" class="auth-modal-btn">' + translate.good + '</button>');
                    authModal.open();
                    $('#clear1').click(function(){
                        authModal.close();
                        $('#password').val('');
                        $('#password_confirmation').val('');
                        $('#password').focus();
                    });
                }
            },
            'json');
    };

    var reset = function ($form) {
        var phone = '998' + $form.find('#phone-number').inputmask('unmaskedvalue');
        var password = $form.find('#password').val();
        var confirmation = $form.find('#password_confirmation').val();

        if (password !== confirmation) {
            $('#auth-modal-text').html('<h4 class="error">' + translate.passdont + '</h4>');
            authModal.open();
            $('#auth-modal-done').click(function(){
                $('#password').focus();
            });
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
                }
            },
            function (response) {
                isLoading = false;
                $('.click-login-btn').removeClass('the4-loading');
                $('.auth-sidebar-btn').removeClass('the4-loading');
                $('.click-login-btn').prop('disabled', false);
                $('.auth-sidebar-btn').prop('disabled', false);
                if (!response['error']) {
                    authModal.setContent('<div class="auth-modal-box"><div id="auth-modal-text"><h4 class="done">' + translate.passsuccess + '</h4></div></div><a href="' + translate.reqlink + '" id="auth-modal-done">Авторизоваться</a>');
                    authModal.open();
                } else {
                    authModal.setContent('<div class="auth-modal-box"><div id="auth-modal-text"><h4 class="error">' + response.error.message + '</h4></div></div><button id="clear1" class="auth-modal-btn">' + translate.good + '</button>');
                    authModal.open();
                    $('#clear1').click(function(){
                        authModal.close();
                        $('#password').val('');
                        $('#password_confirmation').val('');
                        $('#password').focus();
                    });
                }
            },
            'json');
    };

    $(function () {
        $('.click-login #phone-number').inputmask('\\9\\9\\8 (99) 999-99-99');

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

        $('.click-reset #phone-number').inputmask('\\9\\9\\8 (99) 999-99-99');


        $('form.click-reset').on('submit', function () {   

            var $form = $(this);

            var stepR = $form.attr('data-step');

            switch (stepR) {
                case 'check-phone-reset':
                    check_phone_reset($form);
                    break;
                case 'check-otp-reset':
                    check_otp_reset($form);
                    break;
                case 'reset':
                    reset($form);
                    break;
            }

            return false;
        });

    });

});