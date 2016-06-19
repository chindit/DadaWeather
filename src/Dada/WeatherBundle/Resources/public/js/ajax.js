/**
 * Created by david on 7/06/16.
 */
$(document).ready(function(){
    //Changing background if needed -> only if data is already be set by controller
    changeBackground();

    /**
     * Get current location when user click on #actual_city button
     */
    $('#actual_city').click(function(event){
        event.preventDefault();
        getLocation();
    });

    /**
     * Calls «updateWeatherData» when slider is moved
     */
    $('#weather-range').on("change mousemove", function() {
        if((typeof weatherData.list[$(this).val()] != 'undefined')) {
            updateWeatherData(weatherData.list[$(this).val()]);
        }
    });

    /**
     * Change background image when weather is updated
     */
    function changeBackground(){
        if($('#town-pressure').html().length > 0){
            var iconCode = $('#town-stats').attr('data-background').substr(0, 2);
            console.log(imageLinks[iconCode]);
            $('.jumbotron').css("background-image", imageLinks[iconCode]); //imageLinks is hardcoded in index.html.twig
            $('.jumbotron').css("background-size", "cover");
        }

    }

});

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
        weatherData = json; //weatherData is a global var defined in layout.html.twig
        updateWeatherData(json.list[0]);
    });
}
/**
 * Show an error if user's position is missing
 * @param err
 */
function locationError(err) {
    console.warn('ERROR(' + err.code + '): ' + err.message);
}

/**
 * Get user location
 * Calls «locationSuccess» or «locationError»
 */
function getLocation(){
    var options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };
    navigator.geolocation.getCurrentPosition(locationSuccess, locationError, options);
}

/**
 * Update weather data.  This function just replace content of <table> with new data
 * @param json
 */
function updateWeatherData(json){
    $('#town-error').hide();
    $('#town-stats').removeClass('hidden');
    $('#town-nav').removeClass('hidden');
    $('#town-name').html(weatherData.city.name);
    $('#town-weather').html('<img src="http://openweathermap.org/img/w/'+json.weather[0]['icon']+'.png" alt="'+json.weather[0]['description']+'">'+json.weather[0]['description']);
    $('#town-temperature').html(json.main.temp+"°C");
    $('#town-temperature-min').html(json.main.temp_min+"°C");
    $('#town-temperature-max').html(json.main.temp_max+"°C");
    $('#town-humidity').html(json.main.humidity+"%");
    $('#town-pressure').html(json.main.pressure+" hpa");
    $('#town-wind').html(json.wind.speed+" m/s");
    $('#town-wind-direction').html(json.wind.deg+" deg");
    $('#town-clouds').html(json.clouds.all+"%");

    var currentDate = new Date(json.dt*1000);
    $('#weather-range-show').html(currentDate.toLocaleDateString()+' '+currentDate.toLocaleTimeString());
    $('#town-date').html(currentDate.toLocaleDateString()+' '+currentDate.toLocaleTimeString());

    //Parsing icon
    var iconCode = json.weather[0]['icon'].substr(0, 2);
    $('.jumbotron').css("background-image", imageLinks[iconCode]); //imageLinks is hardcoded in layout.html.twig
}