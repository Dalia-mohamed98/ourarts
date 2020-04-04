jQuery(document).ready(function($) {

	jQuery('body').on('updated_checkout', function() {
		update();
	});

	function end() {
		if (jQuery('#shipping_method li').length === 0) {
			jQuery('#shipping_method').html('<span>' + szbd.checkout_string_1 + '</span>');
			jQuery('#place_order').prop('disabled', true);
		}
	}



	function update() {
		jQuery('#shipping_method').fadeOut();
		jQuery('table.woocommerce-checkout-review-order-table').addClass('processing').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			'action': 'check_address_2',
		};
		$.post(
			woocommerce_params.ajax_url,
			data,
			function(response) {
				var the_response = response;
				var country = $('#billing_country').val();
				var state;
				 if($('#billing_state').val()) { state = $('#billing_state').val();}
				var postcode = $('input#billing_postcode').val();
				var city = $('#billing_city').val();
				var address = $('input#billing_address_1').val();
				var address_2 = $('input#billing_address_2').val();
				var s_country = country;
				var s_state = state;
				var s_postcode = postcode;
				var s_city = city;
				var s_address = address;
				var s_address_2 = address_2;
				if ($('#ship-to-different-address').find('input').is(':checked')) {
					s_country = $('#shipping_country').val();

				 if($('#shipping_state').val()) { s_state = $('#shipping_state').val();}
					//s_state = $('#shipping_state').val();
					s_postcode = $('input#shipping_postcode').val();
					s_city = $('#shipping_city').val();
					s_address = $('input#shipping_address_1').val();
					s_address_2 = $('input#shipping_address_2').val();
				}

				var comp;
				var postcode_ = s_postcode.replace(" ", "");
				if (s_country == 'IL') {
					s_address = s_address + ',' + s_city + ' ' + s_postcode;
					comp = {
						country: s_country,
						administrativeArea: s_city,
						locality: s_city,
					};
				} else if (s_country == 'CA') {
					s_address = s_address + ',' + s_city + ' ' + s_postcode;
					comp = {
						country: s_country,
						administrativeArea: s_state
					};
				} else if (s_country == 'RO') {
					s_address = s_address + ',' + s_city + ' ' + s_postcode;
					comp = {
						country: s_country,
						administrativeArea: s_state,

					};
				} else {
					s_address = s_address + ',' + s_city + ' ' + s_postcode;
					comp = {
						postalCode: postcode_,
						country: s_country,
						locality: s_city
					};
				}
				var geocoder = new google.maps.Geocoder();
				// Geocode the address
				geocoder.geocode({
					'address': s_address,
					'componentRestrictions': comp
				}, function(results, status) {
					var latitude;
					var longitude;
					var ok_types = ["street_address", "subpremise", "premise"];

					if (status === google.maps.GeocoderStatus.OK && findCommonElements(results[0].types, ok_types)) {
						latitude = results[0].geometry.location.lat();
						longitude = results[0].geometry.location.lng();
					} else {
						latitude = null;
						longitude = null;
					}
					// Check if the custom delivery method is applicable
					if ((the_response.status === true) && !(the_response.szbd_zones === null || the_response.szbd_zones === undefined || the_response.szbd_zones.length == 0)) {
						var ok_methods = [];
						the_response.szbd_zones.forEach(function(element, index) {
								var path = [];
								for (i = 0; element.geo_coordinates !== null && i < (element.geo_coordinates).length; i++) {
									path.push(new google.maps.LatLng(element.geo_coordinates[i][0], element.geo_coordinates[i][1]));
								}
								var polygon = new google.maps.Polygon({
									paths: path
								});
								var location = new google.maps.LatLng((latitude), (longitude));
								var address_is_in_zone = google.maps.geometry.poly.containsLocation(location, polygon);
								if (!address_is_in_zone) {
									jQuery('#shipping_method li :input').filter(function() {
										return this.value == element.value_id;
									}).closest('li').fadeOut().remove();
									if (!jQuery('#shipping_method li input').is(":checked")) {
										jQuery('#shipping_method li input').first().prop('checked', true);
									}

								} else {
									//below to get lowest cost method only
									if (the_response.exclude == 'yes') {
										ok_methods.push(element);
										var max = ok_methods.reduce((max, p, index, arr) => p.cost > max.cost ? p : max, ok_methods[0]);
										if (ok_methods.length > 1) {
											jQuery('#shipping_method li :input').filter(function() {
												return this.value == max.value_id;
											}).closest('li').remove();
											if (!jQuery('#shipping_method li input').is(":checked")) {
												jQuery('#shipping_method li input').first().prop('checked', true);
											}
											var min = ok_methods.reduce((min, p, index, arr) => p.cost < min.cost ? p : min, ok_methods[0]);
											ok_methods = [min];
										}
									}
								}
								if (index >= the_response.szbd_zones.length - 1) {
									jQuery('#shipping_method').fadeIn();
									jQuery('table.woocommerce-checkout-review-order-table').removeClass('processing').unblock();
									end();
								}
							}
						);
					} else {
						jQuery('table.woocommerce-checkout-review-order-table').removeClass('processing').unblock();
						jQuery('#shipping_method').fadeIn();
						end();
					}
				});
			}).then(function() {
			//end();
		});
	}
	function findCommonElements(arr1, arr2) {
		return arr1.some(item => arr2.includes(item));
	}
});
