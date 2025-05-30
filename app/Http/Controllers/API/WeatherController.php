<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\City;



class WeatherController extends Controller
{
    public function getWeatherTest(City $selectedCity) {
        return file_get_contents(base_path('tests/data/response.json'));
    }

    public function getWeather(City $selectedCity) {
        $response = Http::get("https://api.openweathermap.org/data/3.0/onecall?lat={$selectedCity->lat}&lon={$selectedCity->lon}&exclude=minutely,hourly,daily,alerts&appid={env(API_KEY)}");

        if($response->successful()){
            $data = $response->json();
            return $data;
        }
        else{
            return response()->json(['error' => 'API request failed'], $response->status());
        }
    }

    public function Weather(string $city) {
        $selectedCity = City::select('lat', 'lon')->firstWhere('name', $city);
        if($selectedCity){
            return WeatherController::getWeather($selectedCity);
            // return WeatherController::getWeatherTest($selectedCity);
        }
        else{
            return response()->json(['error'=>'City not found'], 404);
        }
    }
}
