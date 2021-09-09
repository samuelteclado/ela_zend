jQuery(document).ready(function($)
{
	
	$('#event_fields').css('display','block');
	
	$('#event_time').datetimepicker();
	
	$('#post').submit(function(){
			var stopme = false;
			if($('#event_toggle').is(':checked'))
			{
				if( ! $('#event_time').val()) stopme = confirmSubmit('Please Select Event Time, continue anyway?');
				else if( ! $('#event_place').val() || ! $('#lang').val() || ! $('#lat').val()) stopme = confirmSubmit('Please Type Event Place, continue anyway?');
				else if( ! $('#event_organizer').val()) stopme = confirmSubmit('Please Type Event Organizer Name, continue anyway?');
			}
			
			if($('#video_toggle').is(':checked'))
			{
				$('input#video_code').each(function(){
						if($(this).val() == '')
						{
							$(this).css( {'background-color':'#af0101', 'color' : '#ffffff'} );
							$(this).focus();
							stopme = true;
						}
						else $(this).css( {'background-color':'#ffffff', 'color' : '#000000'} );
					});
				if(stopme) stopme = confirmSubmit('Oops! few video links are missing, still want to continue?');
			}
			if(stopme) return false;
		});
	
	function confirmSubmit(msg)
	{
		if ( ! confirm(msg)) return true;
	}
		
	$('#video_toggle').click(function() {
		$('div#video_fields').toggle();
	});

	if($('#video_toggle').is(':checked')) $('#video_fields').css('display','block');
	else $('#video_fields').css('display','none');
	
	$('#add_video').live('click', function(){
			$('#video_fields').append('<span><label for="video">Video: </label><input type="text" name="video_code[]" size="60" id="video_code" value="" />&nbsp;<a href="javascript:void(0);" id="add_video">[+]</a> <a href="javascript:void(0);" id="delete_video">[-]</a><br /></span>');		
		});
	
	$('#delete_video').live('click', function(){
			if($('#video_fields > span').length <= 1) alert('You cannot delete all video fields');
			else $(this).parent('span').remove();
		});
		
	function validateURL(textval)
	{
		var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
		console.log(urlregex);
		return urlregex.test(textval);
	}


function initialize() {
		var lat = ($('#lat').val()) ? $('#lat').val() : -33.8688;
		var lng = ($('#lang').val()) ? $('#lang').val() : 151.2195;
		
        var mapOptions = {center: new google.maps.LatLng(lat, lng),zoom: 15,mapTypeId: google.maps.MapTypeId.ROADMAP};

        var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
        var infowindow = new google.maps.InfoWindow();
		if($('#lat').val()) var marker = new google.maps.Marker({position: new google.maps.LatLng(lat, lng),map: map});
		else var marker = new google.maps.Marker({map: map});
		
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
          infowindow.close();
          var place = autocomplete.getPlace();
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }

          var image = new google.maps.MarkerImage(place.icon, new google.maps.Size(71, 71), new google.maps.Point(0, 0), new google.maps.Point(17, 34), new google.maps.Size(35, 35));
          marker.setIcon(image);
          marker.setPosition(place.geometry.location);

          var address = '';
          if (place.address_components) {
            address = [(place.address_components[0] && place.address_components[0].short_name || ''), (place.address_components[1] && place.address_components[1].short_name || ''),
						(place.address_components[2] && place.address_components[2].short_name || '')].join(' ');
          }
		
		$('#event_place').val(place.name);
		$('#lang').val(marker.position.lng());
		$('#lat').val(marker.position.lat());

          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
        });

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          google.maps.event.addDomListener(radioButton, 'click', function() {
            autocomplete.setTypes(types);
          });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);
      }
      google.maps.event.addDomListener(window, 'load', initialize);
});