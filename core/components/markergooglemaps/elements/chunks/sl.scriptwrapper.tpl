<script type="text/javascript">
	var storeLocatorMap;
	var storeLocatorMarkers = [];
	var storeLocatorInfoWindows = [];
	var storeLocatorGeocoder = null;
	
	function storeLocator_initialize() {
		storeLocatorGeocoder = new google.maps.Geocoder();
		var mapOptions = {
			center: new google.maps.LatLng([[+centerLatitude]], [[+centerLongitude]]),
			zoom: [[+zoom]],
			mapTypeId: google.maps.MapTypeId.[[+mapType]]
		};
		storeLocatorMap = new google.maps.Map(document.getElementById("markergooglemaps_canvas"), mapOptions);
		
		var infoWindow = new google.maps.InfoWindow({
			content: null
		});
		
		// Add all markers
		for (var i = 0; i < storeLocatorMarkers.length; i++) {
			storeLocatorMarkers[i].map = storeLocatorMap;
			var marker = new google.maps.Marker(storeLocatorMarkers[i]);
			
			google.maps.event.addListener(marker, 'click', function() {
				infoWindow.setContent(document.getElementById(this.itemid).innerHTML);
				infoWindow.open(storeLocatorMap, this);
			}); 
		}
	}
	
	function storeLocator_openInfoWindow(infoWindow, marker) {
		infoWindow.open(storeLocatorMap, marker);
	}
	
	google.maps.event.addDomListener(window, 'load', storeLocator_initialize);
</script>