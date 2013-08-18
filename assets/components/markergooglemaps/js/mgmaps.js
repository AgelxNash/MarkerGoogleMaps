var MGMaps = {
    Geocoder: new google.maps.Geocoder(),
    MAP: null,
    Bounds: new google.maps.LatLngBounds(),
    makrers: [],
    infoWindow: new google.maps.InfoWindow({content: null}),
    conifg: {
        centered: {
            latitude: 0,
            longitude: 0
        },
        zoom: 1,
        autoPosition: false,
        mapType: null
    },
    addMarker: function(data){
        MGMaps.makrers.push(data);
    },
    setConfig : function (cfg) {
        MGMaps.config = cfg
    },
    init: function(){
        MGMaps.MAP = new google.maps.Map(
            document.getElementById("markergooglemaps_canvas"),
            {
                center: new google.maps.LatLng(
                    MGMaps.config.centered.latitude,
                    MGMaps.config.centered.longitude
                ),
                zoom: MGMaps.config.zoom,
                mapTypeId: google.maps.MapTypeId[MGMaps.config.mapType]
            }
        );

        for (var i = 0; i < MGMaps.makrers.length; i++) {
            MGMaps.makrers[i].map = MGMaps.MAP;

            var marker = new google.maps.Marker(MGMaps.makrers[i]);

            MGMaps.Bounds.extend(marker.position);
            google.maps.event.addListener(marker, 'click', function() {
                MGMaps.openWindow(this);
            });
        }
        if(MGMaps.config.autoPosition){
            MGMaps.MAP.fitBounds(MGMaps.Bounds);
            MGMaps.MAP.panToBounds(MGMaps.Bounds);
        }
    },
    openWindow: function(data){
        MGMaps.infoWindow.setContent(document.getElementById(data.itemid).innerHTML);
        MGMaps.infoWindow.open(MGMaps.MAP, data);
    }
};

google.maps.event.addDomListener(window, 'load',   function() {
    MGMaps.init();
});