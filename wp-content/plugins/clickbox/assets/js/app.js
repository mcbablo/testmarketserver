(function($) {
    $(window).on('load', function(){
        var api_host = 'https://app.clickbox.uz/api';
        var url_cells = '/merchant/handbooks/cell-types';
        var url_pochtamats = '/merchant/handbooks/postomats';
        var url_cells_pochtomat = '/merchant/handbooks/postomats?cells[0][cell_type_id]=';
        var url_rates_cell = '/merchant/booking/delivery-to-cell/rates';
        var url_send_cell = '/merchant/booking/delivery-to-cell';
        var myMap, myPlacemark;
        var postomatSelected;
        $('input[name^="shipping_method"][value="clickbox"]').prop('checked', true);
        var currentLang = $('#currentLang').data('lang');
        $('#billing_phone').inputmask('\\9\\9\\8 (99) 999-99-99');
        var cell_input = Number($('#clickbox-box').val());
        var cells = [];
        var lists = [];
        var receivername = $('#billing_first_name').val();
        function getProductNames() {
            var lists = [];
            var inputs = document.querySelectorAll('[id="clickbox_productname"]');
            for(var i = 0; i < inputs.length; i++) {
                var value = inputs[i].value;
                lists.push(value);
            }
            let productsName = lists.join(', ');
            return productsName;
        }
        function loadingBtn(id){
            $(id).addClass('the-loading');
            $(id).prop('disabled', true);
        }
        function loadingBtnFalse(id){
            $(id).removeClass('the-loading');
            $(id).prop('disabled', false);
        }
        var clickboxModal = new tingle.modal({
            footer: false,
            cssClass: ['clickbox-modal'],
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

        var clickboxModalPlace = new tingle.modal({
            footer: false,
            closeLabel: '',
            closeMethods: [],
			onOpen: function(){
                $('#the4-header').css('z-index', '-1');
				$('#kalles-section-toolbar_mobile').css('z-index', '-1');
			},
            onClose: function() {
                $('#the4-header').css('z-index', '1001');
				$('#kalles-section-toolbar_mobile').css('z-index', '1002');
            },
            cssClass: ['clickbox-modal', 'clickbox-modal-place'],
        });

        var bringoModal = new tingle.modal({
            footer: false,
            cssClass: ['clickbox-modal'],
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
    
        clickboxModal.setContent('<div class="pochtomat-box"><div id="pochtomat-list"></div><div id="pochtamat-map"></div></div>');
        bringoModal.setContent('<div id="bringo-map"></div>');
        clickboxModalPlace.setContent('<div id="pochtamat-place"></div>');
        let goBackText = currentLang == 'uz' ? 'Ortga qaytish' : 'Вернуться назад';
        let goSubmitText = currentLang == 'uz' ? 'Tasdiqlash' : 'Подтвердить';
        let leaveText = currentLang == 'uz' ? 'Qoldirish' : 'Оставить';
        let editText = currentLang == 'uz' ? 'O\'zgartirish' : 'Изменить';
        let submitText = currentLang == 'uz' ? 'Tanlash' : 'Выбрать';
        let cancelText = currentLang == 'uz' ? 'Bekor qilish' : 'Отменить';
        let addressText = currentLang == 'uz' ? 'Manzil' : 'Адрес';
        let getThereText = currentLang == 'uz' ? 'Qanday borsa bo\'ladi' : 'Как добраться';
        let referenceText = currentLang == 'uz' ? 'Mo\'ljal' : 'Ориентир';
        let whyFind = currentLang == 'uz' ? 'Qanday topish mumkin' : 'Как найти';
        let workTime = currentLang == 'uz' ? 'Ish vaqti' : 'Режим работы';
        let error1 = currentLang == 'uz' ? 'Afsuski, bo\'sh pochtomat yo\'q. Mahsulotlarni savatchadan kamaytiring (agar ko\'p bo\'lsa)' : 'К сожалению свободных почтоматов не осталось. Попробуйте удалить товары с корзины (если их много)';
        let error2 = currentLang == 'uz' ? 'HTTP xato. Iltimos, qo\'llab-quvvatlash xizmatiga qo\'ng\'iroq qiling' : 'Ошибка HTTP. Пожалуйста, позвоните к службу поддержки';
        let choosePcht = currentLang == 'uz' ? 'Pochtomat tanlang' : 'Выберите почтомат';
        function init(pochtamats = null) {
            $('#pochtamat-map').html('');
            $('#pochtamat-place').html('');
            var myMap = new ymaps.Map("pochtamat-map", {
                center: [41.31688073, 69.24690049],
                zoom: 11,
            });
			var menu = $('<div class="pochtomats-list"></ul>');
            if (pochtamats) {
                pochtamats.map((pochtamat, index) => {
					var menuItem = $('<div class="pochtomats-item"><div class="pochtomats-address">' + pochtamat.address + '</div><div class="pochtomats-name">' + pochtamat.name + '</div><div class="pochtomats-des">' + pochtamat.description + '</div></div>');
        				menuItem.appendTo(menu);
						menu.appendTo($('#pochtomat-list'));
                    var marker = new ymaps.Placemark(
                        [pochtamat.loc_latitude, pochtamat.loc_longitude], {
                            balloonContentBody: '<span>'+pochtamat.name+'</span>',
                        }, {
                            preset: 'islands#blueDotIcon',
                            iconColor: '#0095b6'
                        });
                    marker.events.add('balloonopen', function addPochtomat(e) {
                        let pcht1 = '<h4 class="pchtName">'+pochtamat.name+'</h4><p class="pchtDes"><span>' + addressText + ': </span>'+pochtamat.address+'</p>';
                        let imagePochtomat = (pochtamat.images[0]) ? '<div class="pchtImage"><img src="'+pochtamat.images[0]+'" class="pchtImg" width="100%"></div>' : '<div class="pchtImage"><img src="https://www.spot.uz/media/img/2021/11/B6LGmS16375611296395_b.jpg" class="pchtImg" width="100%"></div>'
                        let pcht2 = '<div class="pchtBox">'+imagePochtomat+'<div class="pchtText"><p class="pchtMarsh"><span>' + getThereText + ': </span>'+pochtamat.instruction+'</p><p class="pchtMarsh"><span>' + referenceText + ': </span>'+pochtamat.reference_point+'</p><p class="pchtMarsh"><span>' + whyFind + ': </span>'+pochtamat.location+'</p><p class="pchtMarsh"><span>' + workTime + ': </span>'+pochtamat.working_hours+'</p></div></div>';
                        let pcht3 = pochtamat.id == $('#clickbox_address').val() ? '<div class="pchtBtns"><button class="pcht-back">' + goBackText + '</button><button type="button" class="btn-pochtamat btn-danger" data-address="'+pochtamat.address+'" data-lng="'+pochtamat.loc_longitude+'" data-lat="'+pochtamat.loc_latitude+'" data-id="'+pochtamat.id+'" data-state="1" id="pcht-edit">' + leaveText + '</button></div>' : '<div class="pchtBtns"><button class="pcht-back">' + goBackText + '</button><button type="button" class="btn-pochtamat btn-success" data-address="'+pochtamat.address+'" data-lng="'+pochtamat.loc_longitude+'" data-lat="'+pochtamat.loc_latitude+'" data-id="'+pochtamat.id+'" data-state="1" id="pcht-select">' + goSubmitText + '</button></div>';
                        clickboxModal.close();
						clickboxModalPlace.open();
                        $('.clickbox-modal-place').attr('data-step', '01');
                        $('#pochtamat-place').html(pcht1+pcht2+pcht3);
						$('#pochtomat-list').html('<span></span>');
                    });
                    myMap.geoObjects.add(marker);
					menuItem.find('.pochtomats-address').parent().bind('click', function(){
						let pcht1 = '<h4 class="pchtName">'+pochtamat.name+'</h4><p class="pchtDes"><span>' + addressText + ': </span>'+pochtamat.address+'</p>';
                        let imagePochtomat = (pochtamat.images[0]) ? '<div class="pchtImage"><img src="'+pochtamat.images[0]+'" class="pchtImg" width="100%"></div>' : '<div class="pchtImage"><img src="https://www.spot.uz/media/img/2021/11/B6LGmS16375611296395_b.jpg" class="pchtImg" width="100%"></div>'
                        let pcht2 = '<div class="pchtBox">'+imagePochtomat+'<div class="pchtText"><p class="pchtMarsh"><span>' + getThereText + ': </span>'+pochtamat.instruction+'</p><p class="pchtMarsh"><span>' + referenceText + ': </span>'+pochtamat.reference_point+'</p><p class="pchtMarsh"><span>' + whyFind + ': </span>'+pochtamat.location+'</p><p class="pchtMarsh"><span>' + workTime + ': </span>'+pochtamat.working_hours+'</p></div></div>';
                        let pcht3 = pochtamat.id == $('#clickbox_address').val() ? '<div class="pchtBtns"><button class="pcht-back">' + goBackText + '</button><button type="button" class="btn-pochtamat btn-danger" data-address="'+pochtamat.address+'" data-lng="'+pochtamat.loc_longitude+'" data-lat="'+pochtamat.loc_latitude+'" data-id="'+pochtamat.id+'" data-state="1" id="pcht-edit">' + leaveText + '</button></div>' : '<div class="pchtBtns"><button class="pcht-back">' + goBackText + '</button><button type="button" class="btn-pochtamat btn-success" data-address="'+pochtamat.address+'" data-lng="'+pochtamat.loc_longitude+'" data-lat="'+pochtamat.loc_latitude+'" data-id="'+pochtamat.id+'" data-state="1" id="pcht-select">' + goSubmitText + '</button></div>';
                        clickboxModal.close();
						clickboxModalPlace.open();
                        $('.clickbox-modal-place').attr('data-step', '01');
                        $('#pochtamat-place').html(pcht1+pcht2+pcht3);
						$('#pochtomat-list').html('<span></span>');
					});
                });
            }
        }
        let clickboxBtn = document.getElementById('clickbox-btn');
        if(clickboxBtn){
            clickboxBtn.addEventListener('click', function(){
                if(cell_input > 0){
                    loadingBtn('#clickbox-btn');
                    clickboxModal.open();
                    fetch(
                        api_host+url_pochtamats,
                        {
                            method: 'GET',
                            headers: {
                                'Authorization': 'Basic MTo1ZGU0ZmI3MjcyMGFl',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'Accept-Language': currentLang
                            }
                        }
                    )
                    .then(async (response) => {
                        if (response.ok) {
                            let json = await response.json();
                            if (json.data.length > 0) {
                                loadingBtnFalse('#clickbox-btn');
                                $('#the4-header').css('z-index', '-1');
                                $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                                $('#pochtamat-map').show();
                                ymaps.ready(init(json.data));
                            } else {
                                loadingBtnFalse('#clickbox-btn');
                                $('#the4-header').css('z-index', '-1');
                                $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                                $('#pochtamat-map').html('<h4>' + error1 + '</h4>');
                            }
                        } else {
                            loadingBtnFalse('#clickbox-btn');
                            $('#the4-header').css('z-index', '-1');
                            $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                            $('#pochtamat-map').html('<h4>' + error2 + '</h4>');
                        }
                    })
                    .catch((error) => {
                        loadingBtnFalse('#clickbox-btn');
                        $('#the4-header').css('z-index', '-1');
                        $('#kalles-section-toolbar_mobile').css('z-index', '-1');
                        $('#pochtamat-map').html('<h4>' + error2 + '</h4>');
                    });
                }
            });
        }
        $(document).on('click', '.btn-pochtamat', function() {
            $('#clickbox-btn').text(submitText);
            $('#clickbox-edit').text(choosePcht);
            $('#billing_address_1').val('');
            clickboxModalPlace.close();
            if ($(this).data('state') == 1) {
                fetch(
                    api_host+url_cells + '?postomat_id=' + $(this).data('id'),
                    {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Basic MTo1ZGU0ZmI3MjcyMGFl',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Accept-Language': currentLang
                        }
                    }
                )
                .then(async (response) => {
                    if (response.ok) {
                        let json = await response.json();
                        lists = json.data;
                        let trueSizes = lists.filter(function(item){
                            return item.is_free == true;
                        });
                        if(trueSizes.length == 0){
                            alert('Свободных ячеек не осталось');
                        }
                        if(cell_input == 2 && trueSizes.length == 4){
                            cells = [
                                {
                                    name: getProductNames(),
                                    cell_type_id: trueSizes[1].id,
                                    shipment_type_id: '3',
                                    dimension_x: trueSizes[1].size_x,
                                    dimension_y: trueSizes[1].size_y,
                                    dimension_z: trueSizes[1].size_z,
                                    weight: trueSizes[1].weight
                                }
                            ]
                        } else if(cell_input == 3 && trueSizes.length == 4){
                            cells = [
                                {
                                    name: getProductNames(),
                                    cell_type_id: trueSizes[2].id,
                                    shipment_type_id: '3',
                                    dimension_x: trueSizes[2].size_x,
                                    dimension_y: trueSizes[2].size_y,
                                    dimension_z: trueSizes[2].size_z,
                                    weight: trueSizes[2].weight
                                }
                            ]
                        } else if(cell_input == 3 && trueSizes.length == 3){
                            cells = [
                                {
                                    name: getProductNames(),
                                    cell_type_id: trueSizes[1].id,
                                    shipment_type_id: '3',
                                    dimension_x: trueSizes[1].size_x,
                                    dimension_y: trueSizes[1].size_y,
                                    dimension_z: trueSizes[1].size_z,
                                    weight: trueSizes[1].weight
                                }
                            ]
                        } else if(cell_input == 4 && trueSizes.length == 4){
                            cells = [
                                {
                                    name: getProductNames(),
                                    cell_type_id: trueSizes[3].id,
                                    shipment_type_id: '3',
                                    dimension_x: trueSizes[3].size_x,
                                    dimension_y: trueSizes[3].size_y,
                                    dimension_z: trueSizes[3].size_z,
                                    weight: trueSizes[3].weight
                                }
                            ]
                        } else if(cell_input == 4 && trueSizes.length == 3){
                            cells = [
                                {
                                    name: getProductNames(),
                                    cell_type_id: trueSizes[2].id,
                                    shipment_type_id: '3',
                                    dimension_x: trueSizes[2].size_x,
                                    dimension_y: trueSizes[2].size_y,
                                    dimension_z: trueSizes[2].size_z,
                                    weight: trueSizes[2].weight
                                }
                            ]
                        } else if(cell_input == 4 && trueSizes.length == 2){
                            cells = [
                                {
                                    name: getProductNames(),
                                    cell_type_id: trueSizes[1].id,
                                    shipment_type_id: '3',
                                    dimension_x: trueSizes[1].size_x,
                                    dimension_y: trueSizes[1].size_y,
                                    dimension_z: trueSizes[1].size_z,
                                    weight: trueSizes[1].weight
                                }
                            ]
                        } else{
                            cells = [
                                {
                                    name: getProductNames(),
                                    cell_type_id: trueSizes[0].id,
                                    shipment_type_id: '3',
                                    dimension_x: trueSizes[0].size_x,
                                    dimension_y: trueSizes[0].size_y,
                                    dimension_z: trueSizes[0].size_z,
                                    weight: trueSizes[0].weight
                                }
                            ]
                        }
                    } else {
                        alert("Ошибка HTTP: " + response.status);
                    }
                })
                .catch();
                $('#clickbox_address').val($(this).data('id'));
                $('.btn-pochtamat').addClass('btn-success').removeClass('btn-danger').data('state', 0).text(submitText);
                $(this).addClass('btn-danger').removeClass('btn-success').data('state', 1).text(cancelText);
                $('#clickbox-edit').text($(this).data('address'));
                $('#billing_address_1').val($(this).data('address'));
                $('#clickbox-btn').text(editText);
            } else {
                $(this).data('state', 0).text(submitText);
                $('#clickbox_address').val('');
            }
            return false;
        });
        $(document).on('click', '.pcht-back', function() {
            clickboxModalPlace.close();
            if(cell_input > 0){
                loadingBtn('#clickbox-btn');
                clickboxModal.open();
                fetch(
                    api_host+url_pochtamats,
                    {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Basic MTo1ZGU0ZmI3MjcyMGFl',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Accept-Language': currentLang
                        }
                    }
                )
                .then(async (response) => {
                    if (response.ok) {
                        let json = await response.json();
                        if (json.data.length > 0) {
                            loadingBtnFalse('#clickbox-btn');
                            $('#the4-header').css('z-index', '-1');
							$('#kalles-section-toolbar_mobile').css('z-index', '-1');
                            $('#pochtamat-map').show();
                            ymaps.ready(init(json.data));
                        } else {
                            loadingBtnFalse('#clickbox-btn');
                            $('#the4-header').css('z-index', '-1');
							$('#kalles-section-toolbar_mobile').css('z-index', '-1');
                            $('#pochtamat-map').html('<h4>' + error1 + '</h4>');
                        }
                    } else {
                        loadingBtnFalse('#clickbox-btn');
                        $('#the4-header').css('z-index', '-1');
						$('#kalles-section-toolbar_mobile').css('z-index', '-1');
                        $('#pochtamat-map').html('<h4>' + error2 + '</h4>');
                    }
                })
                .catch((error) => {
                    loadingBtnFalse('#clickbox-btn');
                    $('#the4-header').css('z-index', '-1');
					$('#kalles-section-toolbar_mobile').css('z-index', '-1');
                    $('#pochtamat-map').html('<h4>' + error2 + '</h4>');
                });
            }
        });
        $('#create-order').click(function(){
            loadingBtn('#create-order');
    
            var $phone = $('#billing_phone');
            var telPhone = '998' + $('#billing_phone').inputmask('unmaskedvalue');
    
            if (telPhone.toString().trim() === '') {
                alert('Заполните ваш номер телефона');
                $phone.focus();
                loadingBtnFalse('#create-order');
                return false;
            }
    
            var data = {
                phone: telPhone,
                postomat_to_id: $('#clickbox_address').val(),
                rate_id: 2,
                address: 'Саларская наб., 35А',
                lng: '69.313307',
                lat: '41.326666',
                distance: 20000,
                sender_name: 'CLICK Market',
                cells: cells,
                sender_phone: '998903340959',
                sender_floor: '0',
                receiver_name: receivername,
                receiver_phone: telPhone,
                receiver_floor: '0',
                loaders: '0',
                payment_type: 'click',
                payment_method: 'prepay',
                parcel_cost_enabled: '0',
                parcel_cost: '0',
                company_id: $('#clickbox_rateid').val() ?? 2
            };
    
            if ($('#billing_first_name').val().toString().trim() == '') {
                alert('Заполните имя');
                $('#billing_first_name').focus();
                loadingBtnFalse('#create-order');
                return false;
            }
    
            if($('#clickbox_address').val()){
                fetch(
                    api_host+url_send_cell,
                    {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Basic MTo1ZGU0ZmI3MjcyMGFl',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    }
                )
                .then(async(response) => {
                    loadingBtnFalse('#create-order');
                    if (response.ok) {
                        let json = await response.json();
                        $('#clickbox_orderid').val(json.data.id);
                        $('.checkout').submit();
                        return false;
                    } else {
                        if (response.status == 426) {
                            clickboxModal.open();
                            $('#the4-header').css('z-index', '-1');
							$('#kalles-section-toolbar_mobile').css('z-index', '-1');
                            $('#pochtamat-map').html('<h4>В почтомате не осталось мест</h4>');
                        }
                        let json = await response.json();
                        alert(json)
                    }
                })
                .catch(error => {
                    loadingBtnFalse('#create-order');
                    clickboxModal.open();
                    $('#the4-header').css('z-index', '-1');
					$('#kalles-section-toolbar_mobile').css('z-index', '-1');
                    $('#pochtamat-map').html('<h4>Не удалось отправить данные. Попробуйте позже</h4>');
                });
            } else{
                loadingBtnFalse('#create-order');
                alert('Не выбран почтомат');
            }
        });
    });
})(jQuery);