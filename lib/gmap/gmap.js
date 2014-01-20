/*
 * gmap
 *
 * @author Jonathan Roy <jroy@optimumweb.ca>
 * @version 1.1
 */

!function($) {

    $(function() {

        "use strict"; // jshint ;_;

        jQuery.fn.gmap = function() {

            var geocoder = new google.maps.Geocoder();

            return this.each(function() {

                var $gmap    = $(this);
                var gmapId   = $gmap.attr('id') ? $gmap.attr('id') : $gmap.attr('id', 'map-' + (new Date().getTime())).attr('id');
                var gmapType = $gmap.data('type') || 'standard';
                var gmapLat  = $gmap.data('latitude');
                var gmapLng  = $gmap.data('longitude');
                var gmapZoom = $gmap.data('zoom') || 14;
                var $markers = $gmap.children('.marker');

                var gmapLatLng = new google.maps.LatLng(gmapLat, gmapLng);

                var gmapOptions = {
                    zoom:      gmapZoom,
                    center:    gmapLatLng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }

                var gmap = new google.maps.Map(document.getElementById(gmapId), gmapOptions);

                var n = $markers.size();

                $markers.each(function(i) {

                    var $marker = $(this);

                    console.log("Marker " + (i + 1) + "/" + n);

                    var markerId       = $marker.attr('id') ? $marker.attr('id') : $marker.attr('id', 'map-marker-' + (new Date().getTime())).attr('id');
                    var markerLat      = $marker.data('latitude');
                    var markerLng      = $marker.data('longitude');
                    var markerAddress  = $marker.data('address');
                    var markerTitle    = $marker.data('title');
                    var markerIcon     = $marker.data('icon');
                    var markerMaxWidth = $marker.data('max-width') || 300;
                    var markerContent  = $marker.html();

                    if ( markerLat && markerLng ) {
                        var markerLatLng = new google.maps.LatLng(markerLat, markerLng);

                        var marker = new google.maps.Marker({
                            position: markerLatLng,
                            title:    markerTitle,
                            icon:     markerIcon,
                            map:      gmap
                        });

                        console.log("Marker added by coordinates:", markerLat, markerLng);
                    }
                    else if ( markerAddress ) {
                        geocoder.geocode({'address': markerAddress}, function(res, status) {
                            if ( status == google.maps.GeocoderStatus.OK ) {
                                var lat = res[0].geometry.location.lat();
                                var lng = res[0].geometry.location.lng();
                                var markerLatLng = new google.maps.LatLng(lat, lng);

                                var marker = new google.maps.Marker({
                                    position: markerLatLng,
                                    title:    markerTitle,
                                    icon:     markerIcon,
                                    map:      gmap
                                });

                                console.log("Marker added by address:", lat, lng);
                            } else {
                                console.log(status);
                            }
                        });
                    }

                    if ( markerContent ) {

                        var markerInfoWindow = new google.maps.InfoWindow({
                            content:  markerContent,
                            position: markerLatLng,
                            maxWidth: parseInt(markerMaxWidth)
                        });

                        google.maps.event.addListener(marker, 'click', function() {
                            markerInfoWindow.open(gmap, marker);
                        });

                    }

                });

                if ( gmapType == 'streetview' ) {
                    var svOptions = {
                        pov: {
                            heading: 34,
                            pitch: 10
                        }
                    };
                    var sv = new google.maps.StreetViewPanorama(document.getElementById(gmapId), svOptions);
                    gmap.setStreetView(sv);
                }

            });

        };

    });

}(window.jQuery);