$(document).ready(function () {
    var $map = $('#map');
    if ($($map).length){
        google.maps.event.addDomListener(window, 'load', function() {
            var cords = new google.maps.LatLng($($map).data('lat'), $($map).data('lon'));
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: $($map).data('zoom'),
                center: cords
            });

            var infoWindow = new google.maps.InfoWindow();
            infoWindow.setOptions({
                content: $('#info_window').html(),
                position: cords,
            });
            infoWindow.open(map);
        });
    }
});