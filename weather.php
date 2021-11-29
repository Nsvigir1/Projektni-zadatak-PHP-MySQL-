<?php
    $apiKey = "658ed3cbe0bbcc0a33987fda855b4f64";
    $cityId = "6618983";
    $googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response);
    $currentTime = time();

    print'
    <!doctype html>
    <html>
    <head>
    <title>Forecast Weather using OpenWeatherMap with PHP</title>
    </head>
    <body>
        <div class="report-container">
            <h2> ';echo $data->name; print'  Weather Status</h2>
            <div class="time">
                <div> ';echo date("l g:i a", $currentTime); print' </div>
                <div> ';echo date("jS F, Y",$currentTime); print' </div>
                <div> ';echo ucwords($data->weather[0]->description); print' </div>
            </div>';
                print'
                <img src="http://openweathermap.org/img/w/'; echo $data->weather[0]->icon; print '.png "';  
                    print' <br> <br>';
                    echo $data->main->temp_max; print' °C<span
                    class="min-temperature" ';echo $data->main->temp_min; print' °C</span>
            </div>
            <div class="time">
                <div>Humidity:  ';echo $data->main->humidity; print'  %</div>
                <div>Wind:  ' ;echo $data->wind->speed; print'  km/h</div>
            </div>
        </div>
    </body>
    </html>
    '
 
?>