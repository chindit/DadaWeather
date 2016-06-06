/**
 * Created by david on 6/06/16.
 */

/**
 * LOCATION FUNCTIONS
 */
/**
 * Return user's position
 * @param pos
 */
function locationSuccess(pos) {
    var crd = pos.coords;

    console.log('Your current position is:');
    console.log('Latitude : ' + crd.latitude);
    console.log('Longitude: ' + crd.longitude);
    console.log('More or less ' + crd.accuracy + ' meters.');
}
/**
 * Show an error if user's position is missing
 * @param err
 */
function locationError(err) {
    console.warn('ERROR(' + err.code + '): ' + err.message);
}

function getLocation(){
    var options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };
    navigator.geolocation.getCurrentPosition(locationSuccess, locationError, options);
}
$(document).ready(function(){
    getLocation();

});