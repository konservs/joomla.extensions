function loadcities(val){
	window.console&&console.log('[Regoffices] loadcities()...');
	var citsel=jQuery(val).data('city-selector');
	if(citsel==''){
		return;
		}
	var citelem=jQuery(citsel);
	var citval=citelem.data('active');
	var filter={};
	filter.region=jQuery(val).val();
	if(filter.region==''){
		window.console&&console.log('[Regoffices] loadcities(). There is no selected region!');
		return;
		}
	jQuery.ajax({
		data: filter,
		url: url_cities_json,
		dataType: 'json',
		success: function(data){
			window.console&&console.log('[Regoffices] Cities loaded.');
			citelem.find('option').remove();
			citelem.append(jQuery('<option value="">Пожалуйста, выберите</option>'));
			for(var i=0; i<data.cities.length; i++){
				var city=data.cities[i];
				citelem.append(jQuery('<option value="'+city.id+'"'+(citval==city.id?' selected':'')+'>'+city.name+'</option>'));
				}
			}
		});

	}

function loadregions(val){
	window.console&&console.log('[Regoffices] loadregions()...');
	var regsel=jQuery(val).data('region-selector');
	if(regsel==''){
		return;
		}
	var regelem=jQuery(regsel);
	var regval=regelem.data('active');
	var filter={};
	filter.country=jQuery(val).val();
	jQuery.ajax({
		data: filter,
		url: url_regions_json,
		dataType: 'json',
		success: function(data){
			regelem.find('option').remove();
			regelem.append(jQuery('<option value="">Пожалуйста, выберите</option>'));
			for(var i=0; i<data.regions.length; i++){
				var reg=data.regions[i];
				regelem.append(jQuery('<option value="'+reg.id+'">'+reg.name+'</option>'));
				}
			regelem.val(regval);
			regelem.change();
			}
		});
	}
jQuery(document).ready(function(){
	window.console&&console.log('[Regoffices] The document is ready.');
	jQuery('#brillianttabs a').click(function(){
		var hrf=jQuery(this).attr('href');

		jQuery('#brillianttabs a').removeClass('active');
		jQuery(this).addClass('active');

		jQuery('#tabs_content .tab').removeClass('active');
		jQuery('#tabs_content '+hrf).addClass('active');
		});
	//Add events...
	jQuery('.ajaxloadregion').each(function(ind,val){
		jQuery(val).change(function(){
			loadregions(this);
			});
		});
	jQuery('.ajaxloadcity').each(function(ind,val){
		jQuery(val).change(function(){
			loadcities(this);
			});
		});

	jQuery('.ajaxloadregion').each(function(ind,val){
		loadregions(val);
		});
	window.console&&console.log('[Regoffices] Initialization done.');
	});