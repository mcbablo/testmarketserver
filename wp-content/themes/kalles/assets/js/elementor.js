class KallesMapWidgetHandle extends elementorModules.frontend.handlers.Base {

	getDefaultSettings() {
		return {
			selectors: {
				container: '.kalles-map'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );
		return {
			$container: this.$element.find( selectors.container )
		};
	}

	kallesMapBox() {
			var _this        = this,
				map_data     = [],
				el           = _this.elements.$container,
				dataMap      = el.data('locations'),
				localtions   = dataMap.localtions,
				mapToken     = dataMap.token,
				mapboxInit   = mapboxSdk( {accessToken: mapToken} ),
				map_wrarpper = el.find('.kalles-map__content').attr( 'id' );

			mapboxgl.accessToken = mapToken;

			jQuery.each( localtions, function(index, val) {

				mapboxInit.geocoding.forwardGeocode( {
					query       : val,
					autocomplete: false,
					limit       : 1
				} )
				.send()
				.then( function( response){
					if ( response && response.body && response.body.features && response.body.features.length ) {
						var feature = response.body.features[0],
							map_item = el.find('.kalles-map__list .kalles-map__list--item');
						
						map_item.eq( index ).attr( 'data-latitude', feature.center[0] );
						map_item.eq( index ).attr( 'data-longitude', feature.center[1] );

						var item = {
							"type"    : "Feature",
							'properties': {
								'description': map_item.eq(index).html(),
								'icon': 'theatre'
							},
							"geometry": {
								"type"       : "Point",
								"coordinates": [feature.center[0],feature.center[1]]
							}
						};

						if( index == 0 ) {
							var map_default = [feature.center[0],feature.center[1]],
								name_default = val;
							_this.load_map(map_wrarpper,dataMap,map_item, mapToken, map_data, map_default, name_default);
						}

						map_data.push(item);

						index++;

					}
				});
			});

		return map_data;
	}

	load_map(map_wrarpper,dataMap, map_item, accessToken, map_data, map_default, name_default){
		
			var map = new mapboxgl.Map( {
				container: map_wrarpper,
				style    : 'mapbox://styles/mapbox/'+ dataMap.style ,
				center   : map_default,
				zoom     : dataMap.zom
			} );

			var geocoder = new MapboxGeocoder( {
				accessToken: mapboxgl.accessToken
			} );

			map.addControl( geocoder );

			map.on( 'load', function () {

				createPopUp( map_default, name_default );
				
			} );

			map_item.on('click', function () {
				var  latitude = jQuery(this).data('latitude'),
					 longitude = jQuery(this).data('longitude');

				map.flyTo({
					center: [latitude,longitude ],
					zoom: dataMap.zom,
					essential: true,
					speed: 3,
					curve: 1,
				});

			});

			function createPopUp(currentFeature, name) {
		        const popUps = document.getElementsByClassName('mapboxgl-popup');
		        if (popUps[0]) popUps[0].remove();
		        const popup = new mapboxgl.Popup({ closeOnClick: false })
		          .setLngLat(currentFeature)
		          .setHTML(
		            `<h3>${name}</h3>`
		          )
		          .addTo(map);
		     }
	}

	onInit() {

		super.onInit();
		this.kallesMapBox();
	}
}

class KallesNewsletterFormWidgetHandle extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			selectors: {
				container: '.newsletter_se'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings( 'selectors' );
		return {
			$container: this.$element.find( selectors.container )
		};
	}

	handleKlaviyo() {

		const _form = this.elements.$container.find('.klaviyo_sub_frm');
		
		if( ! _form ) return;

	  	var brand = _form.attr('brand') || 'Kalles Klaviyo';
	    KlaviyoSubscribe.attachToForms( _form, {
	      custom_success_message: true,
	      extra_properties: {$source: 'NewsletterPopup',Brand: brand},
	      success: function ($form) {
	        _form.find('[type="submit"]').removeClass('loading');
	      }
	    });

			
		_form.submit(function(e) {

			var $button = _form.find('[type="submit"]');
		     $button.addClass('loading')
		});

		jQuery('body').on( "klaviyo.subscribe.success klaviyo.subscribe.error",  function(e){

			jQuery(e.target).find('[type="submit"]').removeClass('loading');
		});
	}

	handleMailchimp() {
		jQuery(".nt_ajax_mcsp").submit(function(e) {
	    e.preventDefault();
	    var $form = jQuery(this).closest('form'),
	        $result = $form.find('.mc4wp-response'),
	        $button = $form.find('[type="submit"]');

     	$button.addClass('loading');
     	$result.find('.kalles-message').slideUp(100);

        jQuery.ajax({
            type: "GET",
            url: $form.attr('action'),
            data: $form.serialize(),
            cache: false,
            dataType: 'jsonp',
            jsonp: "c",
            contentType: "application/json; charset=utf-8",
            success: function (data) {
               $button.removeClass('loading');

               if (data.result == "success") {
					$result.find('.kalles-success').html(data.messenger);
                  	$result.find('.kalles-success').slideDown(100);
               } else {
               		$result.find('.kalles-error').html(data.msg);
                  	$result.find('.kalles-error').slideDown(100);
               }
            }
         });
	      //return false;

	   });
	}

	onInit() {

		super.onInit();
		const platform = this.elements.$container.attr('data-platform');
		if ( platform == 'mailchimp' ) {
			this.handleMailchimp();
		} else if( platform == 'klaviyo' ) {
			this.handleKlaviyo();
		}
	}
}

jQuery( window ).on( 'elementor/frontend/init', () => {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/kalles-map.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( KallesMapWidgetHandle, { $element } );
	} );
	elementorFrontend.hooks.addAction( 'frontend/element_ready/kalles-newsletter-form.default', ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( KallesNewsletterFormWidgetHandle, { $element } );
	} );
});