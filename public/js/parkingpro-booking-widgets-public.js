(function( $ ) {
	'use strict';

	$( window ).load(function() 
	{	
		var form = document.getElementById('ppwidget__form'),
			ppwidget_el = document.getElementById('ppwidget'),
			pp_productselection_el = document.getElementById('pp__productselection');

		if( form && ppwidget_el )
		{
			form.addEventListener( "change", ( evt ) => 
			{
			    var trg = evt.target,
			        trg_par = trg.parentElement;
			    
			    if ( trg.type === "radio" && trg_par && trg_par.tagName.toLowerCase() === "label" ) 
			    {
			        var prior = form.querySelector( 'label.checked input[name="' + trg.name + '"]' );
			        if ( prior ) 
			        {
			            prior.parentElement.classList.remove( "checked" );
			        }
			        trg_par.classList.add( "checked" );
			    }
			}, false );
		

			// Resize the widget once on page load
			calculateWidgetSize(ppwidget_el);

			// Resize the widget after the viewport changes
			window.onresize = function(event) 
			{
			    calculateWidgetSize(ppwidget_el);
			};

			// Retrieve service prices
			$('#ppwidget__parkingdate__alt, #ppwidget__returndate__alt, #ppwidget__parkingtime, #ppwidget__returntime').change(function() 
			{
				var arrivalDate = $('#ppwidget__parkingdate__alt').val(),
					arrivalTime = $('#ppwidget__parkingtime').val(),
					departureDate = $('#ppwidget__returndate__alt').val(),
					departureTime = $('#ppwidget__returntime').val();

				if( arrivalDate && departureDate && arrivalTime && departureTime )
				{
					$('.ppwidget__form__serviceradio').each(function(i, item) 
					{
						var location_id = $(item).attr('for');
						getLocationPrice(location_id, arrivalDate, departureDate, arrivalTime, departureTime); 
					});
				}
			});

			// Init Timepickers
			var parkingtimeOptions = { 'step': 15, 'timeFormat': 'H:i' };
			var returntimeOptions = { 'step': 15, 'timeFormat': 'H:i' };

			// Use settings from API config
			if( typeof parkingpro_booking_widgets_config !== 'undefined' ) 
			{
				if( parkingpro_booking_widgets_config.minArrivalDepartureTime !== null ) 
				{
					parkingtimeOptions.minTime = parkingpro_booking_widgets_config.minArrivalDepartureTime;
					returntimeOptions.minTime = parkingpro_booking_widgets_config.minArrivalDepartureTime;
				}

				if( parkingpro_booking_widgets_config.maxArrivalDepartureTime !== null ) 
				{
					parkingtimeOptions.maxTime = parkingpro_booking_widgets_config.maxArrivalDepartureTime;
					returntimeOptions.maxTime = parkingpro_booking_widgets_config.maxArrivalDepartureTime;
				}
			}

			$('#ppwidget__parkingtime').timepicker(parkingtimeOptions);
			$('#ppwidget__returntime').timepicker(returntimeOptions);

			// Use settings from API config
			if( typeof parkingpro_booking_widgets_config !== 'undefined' ) 
			{
				if( parkingpro_booking_widgets_config.defaultArrivalTime !== null )
				{
					var defaultArrivalTimeDate = new Date('2020-01-01T' + parkingpro_booking_widgets_config.defaultArrivalTime);
					$('#ppwidget__parkingtime').timepicker('setTime', defaultArrivalTimeDate);
				}

				if( parkingpro_booking_widgets_config.defaultDepartureTime !== null )
				{
					var defaultDepartureTimeDate = new Date('2020-01-01T' + parkingpro_booking_widgets_config.defaultDepartureTime);
					$('#ppwidget__returntime').timepicker('setTime', defaultDepartureTimeDate);	
				}
			}

			if( typeof parkingpro_booking_widgets_config !== 'undefined' && parkingpro_booking_widgets_config.minArrivalDate !== null ) 
				var minArrivalDate = '08-08-2020';
			else
				var minArrivalDate = 0;

			// Init datepickers
			var parkingdate = $( "#ppwidget__parkingdate" ).datepicker({
				defaultDate: 0,
				minDate: minArrivalDate,
				changeMonth: false,
				numberOfMonths: 1,
				altFormat: "yy-mm-dd",
				dateFormat: "dd-mm-yy",
				altField: "#ppwidget__parkingdate__alt",
				onClose: function() {
					$('#ppwidget__parkingdate__alt').trigger('change');
				}
			}).on( "change", function() {
				if( getDate( this ) )
					returndate.datepicker( "option", "minDate", getDate( this ) );
			});
			
			var returndate = $( "#ppwidget__returndate" ).datepicker({
				defaultDate: 0,
				minDate: minArrivalDate,
				changeMonth: false,
				numberOfMonths: 1,
				altFormat: "yy-mm-dd",
				dateFormat: "dd-mm-yy",
				altField: "#ppwidget__returndate__alt",
				onClose: function() {
					$('#ppwidget__returndate__alt').trigger('change');
				}
			}).on( "change", function() {
				parkingdate.datepicker( "option", "maxDate", getDate( this ) );
			});

			// Trigger change to load date values from HTML val="" attribute
			parkingdate.trigger('change');
			returndate.trigger('change');
			$('#ppwidget__parkingdate__alt').trigger('change');

			// Don't submit empty form inputs
			$(form).submit(function() {
				$(this).find(".ppwidget__button").addClass('ppwidget__button--submitted').attr('disabled', true);
				$(this).find(":input").filter(function(){ 
					return !this.value;
				}).attr("disabled", "disabled");

				// Disable datepickers extra fields, only submit the altFields
				$('#ppwidget__parkingdate').attr("disabled", "disabled");
				$('#ppwidget__returndate').attr("disabled", "disabled");

				return true;
			});

			$('#ppwidget__form .ppwidget__airport #airport').change(function(el) 
			{
				var airportId = this.value;
				var services = $('#ppwidget__form .ppwidget__services__option');
				var services_input = $('#ppwidget__visible_services_list');

				services.show();
				services_input.val(parkingpro_booking_widgets_myparkingpro_service_ids);

				if( airportId )
				{
					var visible_services = [];

					services.each(function(index, item)
					{
						if( item.dataset.airport !== airportId ) {
							$(item).hide();
						}
						else
						{
							visible_services.push(item.dataset.location);
						}
					});

					services_input.val( visible_services.join() );
				}
			});
		}

		if( pp_productselection_el )
		{
			$('.js-product-more-info').click(function(e) 
			{
				e.preventDefault();
				$(this).closest('li').find('.pp__products__block__info').toggleClass('pp__products__block__info--hidden');
				$(this).closest('li').find('.pp__products__block__info .lightSlider').lightSlider({item: 1, slideMargin: 0, galleryMargin: 0});
				return false;
			});

			// Resize the widget once on page load
			calculateProductSelectionSize(pp_productselection_el, pp_productselection_el.clientWidth);

			// Resize the widget after the viewport changes
			window.onresize = function(event) 
			{
			    calculateProductSelectionSize(pp_productselection_el, pp_productselection_el.clientWidth);
			};

			$('.pp__productselection__form').submit(function() {
				$(this).find(".pp__products__block__availability__button").addClass('pp__products__block__availability__button--submitted').attr('disabled', true);
				return true;
			});

			$('.js-tag-filter input:checkbox').change(function()
			{
				if( $(this).is(':checked') === true )
					$(this).parent().addClass('pp__products__filters__filter--active');
				else
					$(this).parent().removeClass('pp__products__filters__filter--active');

				updateFilters();
			});

			$('.js-products-sorting').change(function()
			{
				var selected_sort = $(this).find('option:selected').val();
				sortProductList(selected_sort);
			});

			// Sort services by price by default
			sortProductList('price');
		}
	});

	function getDate( element ) 
	{
		var date;
		try 
		{
			date = $.datepicker.parseDate( "dd-mm-yy", element.value );
		} 
		catch( error ) 
		{
			date = null;
		}

		return date;
	}

	function calculateWidgetSize(el)
	{
		var width = el.clientWidth;

		if( el.parentNode )
			width = el.parentNode.clientWidth;

		// If the size is provided via a parameter, don't change it
		if( jQuery(el).hasClass('ppwidget--large--forced') || jQuery(el).hasClass('ppwidget--medium--forced') || jQuery(el).hasClass('ppwidget--small--forced') )
			return;

	    if( width < 400 )
	    {
	        el.classList.add('ppwidget--small');
	        el.classList.remove('ppwidget--medium', 'ppwidget--large', 'ppwidget--row');
	    }
	    else if( width < 750 )
	    {
	        el.classList.add('ppwidget--medium');
	        el.classList.remove('ppwidget--small', 'ppwidget--large', 'ppwidget--row');
	    }
	    else if( width < 1024 )
	    {
	        el.classList.add('ppwidget--large');
	        el.classList.remove('ppwidget--small', 'ppwidget--medium', 'ppwidget--row');
	    }
	    else
	    {
	        el.classList.add('ppwidget--row');
	        el.classList.remove('ppwidget--small', 'ppwidget--medium', 'ppwidget--large');
	    }
	}

	function calculateProductSelectionSize(el, width)
	{
		// If the size is provided via a parameter, don't change it
		if( jQuery(el).hasClass('pp__productselection--medium--forced') || jQuery(el).hasClass('pp__productselection--small--forced') )
			return;

	    if( width < 400 )
	    {
	        el.classList.add('pp__productselection--small');
	        el.classList.remove('pp__productselection--medium', 'pp__productselection--large');
	    }
	    else if( width < 600 )
	    {
	        el.classList.add('pp__productselection--medium');
	        el.classList.remove('pp__productselection--small', 'pp__productselection--large');
	    }
	    else
	    {
	        el.classList.add('pp__productselection--large');
	        el.classList.remove('pp__productselection--small', 'pp__productselection--medium');
	    }
	}

	function getLocationPrice(location_id, arrivalDate, departureDate, arrivalTime, departureTime)
	{		
		if( !location_id || !arrivalDate || !departureDate )
			return;

		if( arrivalTime && departureTime ) {
			arrivalDate = arrivalDate + ' ' + arrivalTime + ':00';
			departureDate = departureDate + ' ' + departureTime + ':00';
		}

		$.ajax({
	        url: parkingpro_booking_widgets.ajax_url,
	        type: 'POST',
	        dataType: 'json',
	        data: {
	            action: 'pp_get_prices',
	            location_id: location_id,
	            arrivalDate: arrivalDate,
				departureDate: departureDate
	        },
	        success:function(data) {
	        	if( !data || data.isUnavailable === true )
	        	{
		    		$('input#' + location_id).prop( "disabled", true );
		    		$('label[for="' + location_id + '"]').addClass('ppwidget__form__serviceradio--disabled');
		    		$('label[for="' + location_id + '"]').removeClass('checked');
		    		$('label[for="' + location_id + '"] .ppwidget__services__option__price span').html('');
	        	}
	        	else
	        	{
	        		var price = data.totalWithTax.toFixed(2);
	        		$('input#' + location_id).prop( "disabled", false );
	        		$('label[for="' + location_id + '"]').removeClass('ppwidget__form__serviceradio--disabled');
		    		$('label[for="' + location_id + '"] .ppwidget__services__option__price span').html('&euro;' + price );
	        	}
	            
	        },
	        error: function(errorThrown){
	            console.log(errorThrown);
	        }
	    });
	}

	function updateFilters()
	{
		var active_filters = $('.pp__products__filters .pp__products__filters__filter input:checkbox:checked');

		if( active_filters.length > 0 )
		{
			var number_of_active_filters = active_filters.length;

			// Hide all services
			$('.pp__products__list li').hide();

			// Loop all services
			$('.pp__products__list li').each(function(i, service_el) 
			{
				var service_tags = $(service_el).attr('data-tags');
				var tag_matches = 0;

				active_filters.each(function(i, filter_el) 
				{
					if( service_tags.indexOf(filter_el.value) !== -1 )
						tag_matches++;
				});

				if( tag_matches === number_of_active_filters )
					$(service_el).show();
			});
			
		}
		else
		{
			$('.pp__products__list li').show();
		}
	}

	function sortProductList(sort_by)
	{
		var $productList = $('.pp__products__list');

		if( sort_by === 'price' ) 
		{
			$productList.find('li').sort(function(a, b) {
			    return +a.dataset.price - +b.dataset.price;
			})
			.appendTo($productList);
		}
		else if( sort_by === 'name' ) 
		{
			$productList.find('li').sort(function(a, b) {
				if(a.dataset.name.toLowerCase() < b.dataset.name.toLowerCase()) { return -1; }
			    if(a.dataset.name.toLowerCase() > b.dataset.name.toLowerCase()) { return 1; }
			    return 0;
			}).appendTo($productList);
		}
	}

})( jQuery );
