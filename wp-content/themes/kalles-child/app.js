(function($) {
    $(window).on('load', function(){
        function hideClickbox(){
            $('.selectBox').hide();
            $('#create-order').hide();
        }
        function showClickbox(){
            $('.selectBox').show();
            $('#create-order').show();
        }
        let regionLoad;
        $( "#billing_state option:selected").each(function() {
            regionLoad = $(this).val();
        });
        if(regionLoad === '01') {
            showClickbox();
        } else {
            hideClickbox();
        }

        $(document.body).on('change', 'select[name=billing_state]', function(){
            shipMethodLoading();
        });

        function shipMethodLoading(){
            let regionLoadChange;
            $( "#billing_state option:selected").each(function() {
                regionLoadChange = $(this).val();
            });
            if(regionLoadChange === '01') {
                showClickbox();
            } else {
                hideClickbox();
            }
        }
        let allFilter = $('.the4-filter-wrap').find('.wrap_filter>.col-12>.widget-title');
        $.each(allFilter, function(index, value){
            if(value.outerText == 'по Цвет для фильтров'){
                $(this).text("По цвету")
            }
            if(value.outerText == 'по Размер'){
                $(this).text('По размеру')
            }
            if(value.outerText == 'по Цена'){
                $(this).text('По цене')
            }
        });
        $('.the4-banner a').removeAttr('target');
        $('#user_phone').inputmask('\\9\\9\\8 (99) 999-99-99');
        $( 'form.checkout' ).on( 'change', 'input[name^="shipping_method"]', function () {
            var val = jQuery( this ).val();
            if ( val.match( "^clickbox" ) ) {
                $('.selectBox').show();
                $('#create-order').show();
            } else {
                $('.selectBox').hide();
                $('#create-order').hide();
            }
        } );
    });
})(jQuery);