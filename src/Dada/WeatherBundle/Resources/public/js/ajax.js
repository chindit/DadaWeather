/**
 * Created by david on 7/06/16.
 */
$(document).ready(function(){
    //Changing background if needed
    changeBackground();

    $('#actual_city').click(function(event){
        event.preventDefault();
        getLocation();
    });

    $('#weather-range').on("change mousemove", function() {
        if((typeof weatherData.list[$(this).val()] != 'undefined')) {
            updateWeatherData(weatherData.list[$(this).val()]);
        }
    });

    function changeBackground(){
        if($('#town-pressure').html().length > 0){
            var iconCode = $('#town-stats').attr('data-background').substr(0, 2);
            console.log(imageLinks[iconCode]);
            $('.jumbotron').css("background-image", imageLinks[iconCode]); //imageLinks is hardcoded in index.html.twig
            $('.jumbotron').css("background-size", "cover");
        }

    }

});