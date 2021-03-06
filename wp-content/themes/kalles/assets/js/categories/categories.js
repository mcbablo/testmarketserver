/*
 * Categories js function 
 * @since 1.0.0
*/

(function( $ ) {
	"use strict";
    var body = $('body');

    // Init sidebar filter
    The4Kalles.wcInitSidebarFilter = function() {
        //Filter sidebar position
        body.on( 'click', '.filter-trigger.sidebar', function(e) {
            e.preventDefault();

            body.toggleClass( 'slidebar-opened' );

            var mask = $('.mask-overlay');
            mask.addClass('mask_opened');

            $( '.mask-overlay, .close-filter' ).on( 'click', function() {
                body.removeClass( 'slidebar-opened' );
                mask.removeClass('mask_opened');
            });


        });

        //Filter Top inner position
        body.on( 'click', '.filter-trigger.top', function(e) {
            e.preventDefault();
            var _this = $( this );
            _this.toggleClass('opened');
            var $filter = $('#section-nt_filter');

            if ( $filter.is( ":hidden" ) ) {
                $filter.stop().slideDown(200);
            } else {
                $filter.slideUp(200);
            }
        });

        //Hide filter slidebar when click filter
        body.on('click', '.filter-sidebar .nt_filter_block a', function(e) {
            if ( body.hasClass('slidebar-opened')) {
                body.removeClass( 'slidebar-opened' );
                $( '.mask-overlay' ).remove();
            }

        });

        //Hide filter slidebar when click filter
        body.on('click', '.filter-top.nt_filter_block a, .result_clear a, .is_pjax_item, .price_slider_wrapper .button', function(e) {
            var $filter = $('#section-nt_filter');
            $filter.slideUp(200);
        });

        //filter dropdown
        body.on( 'click', '.filter-dropdown__title', function( e ) {
            var _this = $(this);

            e.preventDefault();

            if ( _this.hasClass('opened') ) {
                _this.removeClass('opened');
                 _this.next().slideUp(100);
            } else {
                _this.addClass('opened');
                _this.next().slideDown(100);
            }

            body.on( 'click', function( e ) {

                var target = e.target;

                if (_this.hasClass('opened') && (  !$(target).is(_this) && $(target).closest('.filter-dropdown').find(_this).length == 0 ) ) {
                    _this.removeClass('opened');
                    _this.next().slideUp(100);
                }
            })

        })
    }

    // Init wc switch layout
    The4Kalles.wcInitSwitchLayout = function() {
        $( 'body' ).on( 'click', '.wc-col-switch a', function(e) {
            e.preventDefault();

            var _this            = $( this ),
                _col             = _this.data( 'col' ),
                _skey            = _this.data( 'skey' ),
                _parent          = _this.closest( '.wc-col-switch' ),
                _products        = $( '#the4-content .products .product' ),
                _sizer           = $( '.products .grid-sizer' ),
                _cartView_holder = $('.container_cat');

            //Integrations WCFM plugin
            if ( body.hasClass( 'wcfmmp-store-page' ) ) {
                _products        = $( '#wcfmmp-store .products .product' );
                _cartView_holder = $('#products-wrapper');
            }

            _parent.addClass('pe_none');

            if ( _this.hasClass( 'active' ) ) {
                return;
            }

            if (_col == 'listt4') {
                _cartView_holder.removeClass('on_list_view_false').addClass('on_list_view_true')

            } else {
                _cartView_holder.removeClass('on_list_view_false').addClass('on_list_view_true')
            }

            (_col == 'listt4') ? _cartView_holder.removeClass('on_list_view_false').addClass('on_list_view_true') : _cartView_holder.removeClass('on_list_view_true').addClass('on_list_view_false');

            _parent.find( 'a' ).removeClass( 'active' );
            _this.addClass( 'active' );

            _products.removeClass( 'col-md-2 col-md-3 col-md-4 col-md-6 col-lg-2 col-lg-3 col-lg-4 col-lg-6 col-6' ).addClass( 'col-md-' + _col + ' col-lg-' + _col );
            _sizer.removeClass( 'size-2 size-3 size-4 size-6 size-12' ).addClass( 'size-' + _col )

            if ( $( '.container_cat .products' ).hasClass( 'the4-masonry' ) || $( '#products-wrapper .products' ).hasClass( 'the4-masonry' ) ) {
                The4Kalles.initMasonry();
            }

            //Set default view for this customer

            $.ajax({
                url: THE4_AjaxURL,
                type: 'POST',
                dataType: 'json',
                data: {
                    security_code : _skey,
                    action: 'the4_kalles_set_colunm_view',
                    col:  _col
                },
                success: function( data ) {
                    _parent.removeClass('pe_none');
                }
            });

        });
    }

    /*
     * Shop Ajax Filter
     * using pjax library. https://github.com/defunkt/jquery-pjax
     * @since 1.0.0
     */
    The4Kalles.The4KallesShopAjaxFilter = function() {
        if (THE4_Data_Js['ajax_shop'] != '1' || $('.container_cat').length == 0 || typeof ($.fn.pjax) == 'undefined' ) return;

        //Setup Pjax status
        $(document).on('pjax:beforeSend', function(xhr, options) {});
        $(document).on('pjax:timeout', function(e) {
            // Prevent default timeout redirection behavior
            e.preventDefault()
        });
        $(document).on('pjax:error', function(xhr, textStatus, error, options) {
            console.log('pjax error ' + error);
        });
        $(document).on('pjax:start', function(xhr, textStatus, options) {
            if ( $.magnificPopup.instance.isOpen ) {
                $.magnificPopup.close();
            }
            body.addClass('ajax_loading');
            scrollToTop();

        });
        $(document).on('pjax:end', function(xhr, textStatus, options) {
            body.removeClass('ajax_loading');
            The4Kalles.initAjaxLoad();
            The4Kalles.initMasonry();
            The4Kalles.kalles_filter_color();

            if ( $('.price_slider_wrapper').length > 0 ) {
                The4Kalles.init_price_filter();
            }
            
        });

        //Filter product
        $(document).pjax(
            '.nt_filter_block a, .is_pjax_item, .is_pjax > a, .result_clear a, .tagcloud .tag-cloud-link'
            , '.container_cat',
            {
                container: '.container_cat',
                fragment: '.container_cat',
                timeout: 5000,
                scrollTo: false
            });

        $( document ).on( 'click', '.widget_price_filter form .button', function( e ) {
            var form  = $( '.widget_price_filter form' );
            
            $.pjax({
                container: '.container_cat',
                fragment: '.container_cat',
                url: form.attr('action'),
                data: form.serialize(),
                timeout: 5000,
                scrollTo: false
            });

            return false;
        });

        var scrollToTop = function () {
            var $scrollTo = $('.container_cat'),
                scrollTo = $scrollTo.offset().top - 100;

            $('html, body').stop().animate({
                scrollTop: scrollTo
            }, 400);
        };

    }

    /*
     * Init filter price slider
     * See woo/js/frontend/price-slider.js
     * @since 1.0.3
     */
    
    The4Kalles.init_price_filter = function() {
        $( 'input#min_price, input#max_price' ).hide();
        $( '.price_slider, .price_label' ).show();

        var min_price         = $( '.price_slider_amount #min_price' ).data( 'min' ),
            max_price         = $( '.price_slider_amount #max_price' ).data( 'max' ),
            step              = $( '.price_slider_amount' ).data( 'step' ) || 1,
            current_min_price = $( '.price_slider_amount #min_price' ).val(),
            current_max_price = $( '.price_slider_amount #max_price' ).val();

        $( '.price_slider:not(.ui-slider)' ).slider({
            range: true,
            animate: true,
            min: min_price,
            max: max_price,
            step: step,
            values: [ current_min_price, current_max_price ],
            create: function() {

                $( '.price_slider_amount #min_price' ).val( current_min_price );
                $( '.price_slider_amount #max_price' ).val( current_max_price );

                $( document.body ).trigger( 'price_slider_create', [ current_min_price, current_max_price ] );
            },
            slide: function( event, ui ) {

                $( 'input#min_price' ).val( ui.values[0] );
                $( 'input#max_price' ).val( ui.values[1] );

                $( document.body ).trigger( 'price_slider_slide', [ ui.values[0], ui.values[1] ] );
            },
            change: function( event, ui ) {

                $( document.body ).trigger( 'price_slider_change', [ ui.values[0], ui.values[1] ] );
            }
        });
    }

    //Sort by Product list
    The4Kalles.The4KallesSortbyPicker = function () {

        if ($('.cat_sortby_js').length == 0) return;

        body.on('click', 'a.sortby_pick', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $this = $(this),
                pr = $this.closest('.cat_sortby_js');

            if ($(pr).hasClass('opended')) {
                $(pr).removeClass('opended');
                body.removeClass('sortby_opended');
            } else {

                $(pr).addClass('opended');
                body.addClass('sortby_opended');
            }
        });

        body.click(function (e) {

            if ($(e.target).hasClass('sortby_pick')) return;
            $('.cat_sortby_js.opended').removeClass('opended');
            body.removeClass('sortby_opended');
        });

    };

    The4Kalles.kalles_filter_color = function() {
        if ($('.nt_filter_color.type-brand').length > 0) {
            var $this = $('.nt_filter_color').parent('.nt_filter_block');
            $this.addClass('filter_block_brand');

        } else if ($('.nt_filter_color').length > 0) {
            var $this = $('.nt_filter_color').parent('.nt_filter_block');
            $this.addClass('filter_block_color');

        }
    }


    $( document ).ready( function() {
        The4Kalles.wcInitSidebarFilter();
        The4Kalles.wcInitSwitchLayout();
        The4Kalles.The4KallesShopAjaxFilter();
        The4Kalles.init_price_filter();
        The4Kalles.The4KallesSortbyPicker();
        The4Kalles.kalles_filter_color();
    })

})( jQuery );
