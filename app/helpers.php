<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
use Geocoder\Query\GeocodeQuery;
use Geocoder\Provider\GoogleMaps\GoogleMaps;

function geocodeAddress($address)
{
    $apiKey = config('services.google.maps.api_key');
    $provider = new GoogleMaps($apiKey);

    $geocoder = new \Geocoder\StatefulGeocoder();
    $geocoder->registerProvider($provider);

    $result = $geocoder->geocodeQuery(GeocodeQuery::create($address));
    dd($result);
    return [
        'latitude' => $result->first()->getCoordinates()->getLatitude(),
        'longitude' => $result->first()->getCoordinates()->getLongitude(),
    ];
}