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

    $.getJSON('http://localhost/DadaWeather/web/app_dev.php/api/current/coords/'+crd.latitude+'/'+crd.longitude, function(json){
        console.log(json);
        $('#town-error').hide();
        $('#town-stats').show()
        $('#town-name').html(json.name);
        $('#town-weather').html('<img src="http://openweathermap.org/img/w/'+json.weather[0]['icon']+'.png" alt="'+json.weather[0]['description']+'">'+json.weather[0]['description']);
        $('#town-temperature').html(json.main.temp+"°C");
        $('#town-temperature-min').html(json.main.temp_min+"°C");
        $('#town-temperature-max').html(json.main.temp_max+"°C");
        $('#town-humidity').html(json.main.humidity+"%");
        $('#town-pressure').html(json.main.pressure+" hpa");
        $('#town-wind').html(json.wind.speed+" m/s");
        $('#town-wind-direction').html(json.wind.deg+" deg");
        $('#town-clouds').html(json.clouds.all+"%");
        var sunrise = new Date((json.sys.sunrise*1000));
        var sunset = new Date((json.sys.sunset*1000));
        $('#town-sunrise').html(sunrise.toLocaleTimeString())
        $('#town-sunset').html(sunset.toLocaleTimeString());

        //Parsing icon
        var iconCode = json.weather[0]['icon'].substr(0, 2);
        $('.jumbotron').css("background-image", imageLinks[iconCode]); //imageLinks is hardcoded in index.html.twig
    });

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
