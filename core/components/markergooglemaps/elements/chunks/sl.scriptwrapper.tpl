<script type="text/javascript">
    MGMaps.setConfig({
        autoPosition: [[+autoPosition]],
        mapType: '[[+mapType]]',
        centered: {
            latitude: [[+centerLatitude]],
            longitude: [[+centerLongitude]]
        },
        zoom: [[+zoom]]
    });

    function clickItem(latitude, longitude, storeZoom){
        MGMaps.MAP.setCenter(new google.maps.LatLng(latitude,longitude));
        MGMaps.MAP.setZoom(storeZoom);
    }
</script>
<div id="markergooglemaps_canvas" style="width: [[+width]]px; height: [[+height]]px;"></div>
<style>#markergooglemaps_canvas img{max-width: none;}</style>
