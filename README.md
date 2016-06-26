# DadaWeather

[![Build Status](https://travis-ci.org/chindit/DadaWeather.svg?branch=master)](https://travis-ci.org/chindit/DadaWeather)[![Coverage Status](https://coveralls.io/repos/github/chindit/DadaWeather/badge.svg?branch=master)](https://coveralls.io/github/chindit/DadaWeather?branch=master)

A simple weather app

## INSTAL
1) Add these lines into your `config.yml`
 ```# Config for OpenWeather
dada_weather:
    openweather_keys:
        units: metric
        lang: fr```

2) Add your OpenWeather API key into `parameters.yml`
3) Load SQL schema `php bin/console doctrine:schema:update --force`
4) That's it, you're good to go ;)
