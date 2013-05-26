<script type="text/javascript">
	storeLocatorMarkers.push({
		position: new google.maps.LatLng([[+store.latitude]], [[+store.longitude]]),
		itemid: 'itemMapWindow[[+store.id]]'[[+markerImage:eq=`0`:then=``:else=`,
		icon: new google.maps.MarkerImage('[[+markerImage]]')`]]
	});
</script>
<script type="text/html" id="itemMapWindow[[+store.id]]">
<div class="storelocator-marker">
    <h3>[[+store.description]]</h3>
</div>
</script>
<a style="cursor: pointer;" onclick="[[+onclick]]">&bull; [[+store.description]]</a><br />