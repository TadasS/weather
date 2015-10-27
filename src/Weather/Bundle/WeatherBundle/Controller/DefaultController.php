<?php

namespace Weather\Bundle\WeatherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $city = '';
        $info = array();
        $api_key = 'bd82977b86bf27fb59a04b61b657fb6f';
        $units = 'metric';
        $error = false;

        if (isset( $_GET['city'] ) && $_GET['city'] != '') {
            $city = urlencode($_GET['city']);
            $result = file_get_contents('http://api.openweathermap.org/data/2.5/forecast?units='.$units.'&q='.$city.'&appid='.$api_key);
            $result = json_decode($result);

            if ($result->cod == 200) {
                $info = array(
                    'lon' => $result->city->coord->lon,
                    'lat' => $result->city->coord->lat,
                    'name' => $result->city->name,
                    'temp' => $result->list[0]->main->temp,
                    'temp_min' => $result->list[0]->main->temp_min,
                    'temp_max' => $result->list[0]->main->temp_max,
                    'pressure' => $result->list[0]->main->pressure,
                    'humidity' => $result->list[0]->main->humidity,
                    'weather' => $result->list[0]->weather[0]->main,
                    'description' => $result->list[0]->weather[0]->description,
                    'unit' => '°C'
                );
            } else if ($result->cod == 404) {
                $error = $result->message;
            }
        }

        return array(
            'city' => (isset($result->city->name) ? $result->city->name : $city),
            'error' => $error,
            'info' => $info
        );
    }
}
