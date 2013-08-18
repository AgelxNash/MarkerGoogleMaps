<a style="cursor: pointer;" onclick="clickItem('[[+store.latitude]]', '[[+store.longitude]]', '[[+config.storeZoom]]');">
    &bull; [[%markergooglemaps.click_to_view]] [[+store.pagetitle]] ([[+store.latitude]], [[+store.longitude]])
</a><br />

<script type="text/html" id="itemMapWindow[[+store.id]]">
    <div class="storelocator-marker">
        <h3>[[+store.description]]</h3>
        [[+pagetitle]]
    </div>
</script>

<script type="text/javascript">
    var obj = {
        position: new google.maps.LatLng([[+store.latitude]], [[+store.longitude]]),
        itemid: 'itemMapWindow[[+store.id]]'
    };
    var image = '[[+config.markerImage]]';
    if('' != image){
        obj.icon = new google.maps.MarkerImage(image);
    }
    MGMaps.addMarker(obj);
</script>