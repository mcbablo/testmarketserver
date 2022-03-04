(function($) {
    $(window).on('load', function(){
        $('#banner1').find('a[target="_blank"]').removeAttr('target');
        var api_host = 'https://dev.clickbox.uz/api';
        var url_cells = '/merchant/handbooks/cell-types';
        var url_pochtamats = '/merchant/handbooks/postomats';
        var url_cells_pochtomat = '/merchant/handbooks/postomats?cells[0][cell_type_id]=';
        var url_rates_cell = '/merchant/booking/delivery-to-cell/rates';
        var url_send_cell = '/merchant/booking/delivery-to-cell';
        var myMap, myPlacemark;
        var postomatSelected;
    
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
            footer: true,
            cssClass: ['clickbox-modal'],
        });

        var clickboxModalPlace = new tingle.modal({
            footer: false,
            onClose: function() {
                $('.header-sticky').css('z-index', '1001');
            },
            cssClass: ['clickbox-modal'],
        });
    
        clickboxModal.setContent('<div id="pochtamat-map"></div>');
        clickboxModalPlace.setContent('<div id="pochtamat-place"></div>');
    
        clickboxModal.addFooterBtn('Закрыть', 'clickbox-btn-close tingle-btn--pull-right', function () {
            clickboxModal.close();
            $('.header-sticky').css('z-index', '1001');
        });

        let selectLoadCity = $( "#billing_state option:selected" ).val();
        let selectPickup = !$('#shipping_method_0_local_pickup-8').val();
        let selectShipAll = $('input[name="shipping_method[0]"]:checked').val();
        if(selectLoadCity === '01' && selectPickup && selectShipAll == 'clickbox'){
            $('.selectBox').show();
            $('#create-order').show();
        } else if (selectLoadCity === '01' && !selectPickup && selectShipAll == 'clickbox'){
            $('.selectBox').show();
            $('#create-order').show();
        } else {
            $('.selectBox').hide();
            $('#create-order').hide();
        }

        $( "#billing_state" ).change(function() {
            let selectChangeCity = '';
            let selectPickupChange = !$('#shipping_method_0_local_pickup-8').val();
            let selectShip = $('input[name="shipping_method[0]"]:checked').val();
            $( "#billing_state option:selected").each(function() {
                selectChangeCity = $(this).val();
            });
            if(selectChangeCity === '01' && selectPickupChange && selectShip == 'clickbox'){
                console.log('Ташкент, не сотрудник, почтомат')
                $('#create-order').show();
            } else if(selectChangeCity === '01' && !selectPickupChange && selectShip == 'clickbox'){
                $('.selectBox').show();
                $('#create-order').show();
            } else {
                $('.selectBox').hide();
                $('#create-order').hide();
            }
        }).trigger( "change" );

    
        function init(pochtamats = null) {
            $('#pochtamat-map').html('');
            $('#pochtamat-place').html('');
            var myMap = new ymaps.Map("pochtamat-map", {
                center: [41.31688073, 69.24690049],
                zoom: 12
            }, {
                searchControlProvider: 'yandex#search'
            });
            if (pochtamats) {
                pochtamats.map((pochtamat, index) => {
                    var marker = new ymaps.Placemark(
                        [pochtamat.loc_latitude, pochtamat.loc_longitude], {
                            balloonContentBody: '<h4 class="pchtName">'+pochtamat.name+'</h4>'+'<p class="pchtDes">Адрес: <span class="ymaps-geolink" data-bounds="[[55.63333783240489,37.486741441564136],[55.75433517114847,37.69017466910319]]" data-type="geo">'+pochtamat.address+'</span></p>',
                        }, {
                            preset: 'islands#blueDotIcon',
                            iconColor: '#0095b6'
                        });
                    marker.events.add('balloonopen', function(e) {
                        $('.header-sticky').css('z-index', '-1');
                        let pcht1 = '<h4 class="pchtName">'+pochtamat.name+'</h4><p class="pchtDes"><span>Адрес: </span>'+pochtamat.address+'</p>';
                        let pcht2 = '<div class="pchtBox"><div class="pchtImage"><img src="https://www.spot.uz/media/img/2021/11/B6LGmS16375611296395_b.jpg" class="pchtImg" width="100%"></div><div class="pchtText"><p class="pchtMarsh"><span>Как добраться:</span> На изи, заходишь в макро она возле банкоматов.</p>'+pochtamat.description+'</div></div>';
                        let pcht3 = pochtamat.id == $('#clickbox_address').val() ? '<div class="pchtBtns"><button type="button" class="btn-pochtamat btn-danger" data-address="'+pochtamat.address+'" data-lng="'+pochtamat.loc_longitude+'" data-lat="'+pochtamat.loc_latitude+'" data-id="'+pochtamat.id+'" data-state="1" id="pcht-edit">Оставить</button></div>' : '<div class="pchtBtns"><button type="button" class="btn-pochtamat btn-success" data-address="'+pochtamat.address+'" data-lng="'+pochtamat.loc_longitude+'" data-lat="'+pochtamat.loc_latitude+'" data-id="'+pochtamat.id+'" data-state="1" id="pcht-select">Подтвердить</button></div>';
                        clickboxModalPlace.open();
                        clickboxModal.close();
                        $('#pochtamat-place').html(pcht1+pcht2+pcht3);
                    });
                    myMap.geoObjects.add(marker);
                });
            }
        }
    
        $('#clickbox-btn').click(function(){
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
                            'Content-Type': 'application/json'
                        }
                    }
                )
                .then(async (response) => {
                    if (response.ok) {
                        let json = await response.json();
                        if (json.data.length > 0) {
                            loadingBtnFalse('#clickbox-btn');
                            $('.header-sticky').css('z-index', '-1');
                            $('#pochtamat-map').css('height', '500px').css('width', '100%');
                            $('#pochtamat-map').show();
                            ymaps.ready(init(json.data));
                        } else {
                            loadingBtnFalse('#clickbox-btn');
                            $('.header-sticky').css('z-index', '-1');
                            $('#pochtamat-map').html('<h4>К сожалению свободных почтоматов не осталось. Попробуйте удалить товары с корзины (если их много)</h4>');
                        }
                    } else {
                        loadingBtnFalse('#clickbox-btn');
                        $('.header-sticky').css('z-index', '-1');
                        $('#pochtamat-map').html('<h4>Ошибка HTTP. Пожалуйста, позвоните к службу поддержки.</h4>');
                    }
                })
                .catch((error) => {
                    loadingBtnFalse('#clickbox-btn');
                    $('.header-sticky').css('z-index', '-1');
                    $('#pochtamat-map').html('<h4>Ошибка HTTP. Пожалуйста, позвоните к службу поддержки.</h4>');
                });
            }
        });
    
        $(document).on('click', '.btn-pochtamat', function() {
            $('#clickbox-btn').text('Выбрать');
            $('#clickbox-edit').text('Выберите почтомат');
            clickboxModalPlace.close();
            if ($(this).data('state') == 1) {
                fetch(
                    api_host+url_cells + '?postomat_id=' + $(this).data('id'),
                    {
                        method: 'GET',
                        headers: {
                            'Authorization': 'Basic MTo1ZGU0ZmI3MjcyMGFl',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
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
                $('.btn-pochtamat').addClass('btn-success').removeClass('btn-danger').data('state', 0).text('Выбрать');
                $(this).addClass('btn-danger').removeClass('btn-success').data('state', 1).text('Отменить');
                $('#clickbox-edit').text($(this).data('address'));
                $('#clickbox-btn').text('Изменить');
            } else {
                $(this).data('state', 0).text('Выбрать');
                $('#clickbox_address').val('');
            }
            return false;
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
                            $('.header-sticky').css('z-index', '-1');
                            $('#pochtamat-map').html('<h4>В почтомате не осталось мест</h4>');
                        }
                        let json = await response.json();
                        alert(json)
                    }
                })
                .catch(error => {
                    loadingBtnFalse('#create-order');
                    clickboxModal.open();
                    $('.header-sticky').css('z-index', '-1');
                    $('#pochtamat-map').html('<h4>Не удалось отправить данные. Попробуйте позже</h4>');
                });
            } else{
                loadingBtnFalse('#create-order');
                alert('Не выбран почтомат');
            }
        });
    });
})(jQuery);