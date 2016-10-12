jQuery(document).ready(function(){
	window.console&&console.log('[Regoffices] Main. Initializing...');
	//(#regoffices-com-map)

	var myLatLng = {lat: -25.363, lng: 131.044};
	var map = new google.maps.Map(document.getElementById('regoffices-com-map'), {
		zoom: 4,
		center: myLatLng
		});

	var ajaxdata={};
	if(window.regoffices_filter!==undefined){
		ajaxdata=window.regoffices_filter;
		}
	window.console&&console.log('[Regoffices] Starting AJAX request.');
	jQuery.ajax({
		url: url_offices_json,
		data: ajaxdata,
		dataType:'json',
		success: function(data){
			window.console&&console.log('[Regoffices] data received!');
			var bounds = new google.maps.LatLngBounds();


			window.console&&console.log('[Regoffices] Offices count: '+data.offices.length);
			for(var i=0; i<data.offices.length; i++){
				var office=data.offices[i];
				if((office.lat==0)&&(office.lng==0)){
					continue;
					}
				window.console&&console.log('[Regoffices] office['+i+'] : ('+office.lat+','+office.lng+')');

				var officeLatLng = new google.maps.LatLng(office.lat,office.lng);
				bounds.extend(officeLatLng);

				var marker = new google.maps.Marker({
					position: officeLatLng,
					map: map,
					title: office.name
					});
				map.fitBounds(bounds);
				}
			window.console&&console.log('[Regoffices] All done!');
			},
		error: function(){
			console.log('[Regoffices] AJAX error!');
			}
		});

	jQuery('.listtoselect').each(function(index,list){
		window.console&&console.log('[Regoffices.list] Found list!');
		var items=[];
		var el=jQuery('<div class="listlist"></div>');
		var el_active=jQuery('<div class="active">Пожалуйста, выберите</div>').appendTo(el);
		var el_list=jQuery('<div class="list"></div>').appendTo(el);
		jQuery('li',list).each(function(index2,li){
			var a=jQuery('a',li);
			var xitem={};
			xitem.html=a.html();
			xitem.href=a.attr('href');
			items.push(xitem);
			if(jQuery(li).hasClass('active')){
				el_active.html(xitem.html);
				}
			var el_list_item=jQuery('<a href="'+xitem.href+'">'+xitem.html+'</a>');
			el_list_item.appendTo(el_list);
			});
		el_active.click(function(){
			el_list.toggleClass('visible');
			});
		//console.log(items);
		el.insertAfter(list);
		jQuery(list).hide();
		});

	});
