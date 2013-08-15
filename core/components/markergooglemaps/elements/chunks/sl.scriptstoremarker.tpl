<script type="text/javascript">
	storeLocatorMarkers.push({
		position: new google.maps.LatLng([[+store.latitude]], [[+store.longitude]]),
		itemid: 'itemMapWindow[[+store.id]]'[[+markerImage:eq=`0`:then=``:else=`,
		icon: new google.maps.MarkerImage('[[+markerImage]]')`]]
	});
</script>