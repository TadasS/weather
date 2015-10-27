<?php

namespace Weather\Bundle\WeatherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;

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
        $unit = 'metric';
        $error = false;
        $valid_units = array(
            'metric',
            'imperial'
        );
        $lang = 'en'; // if translations would be needed some day

        if (isset($_GET['units']) && $_GET['units'] != '' && in_array($_GET['units'], $valid_units)) {
            $unit = $_GET['units'];
        }

        if (isset( $_GET['city'] ) && $_GET['city'] != '') {
            $city = urlencode($_GET['city']);
            try {
                $result = file_get_contents('http://api.openweathermap.org/data/2.5/forecast?units='.$unit.'&q='.$city.'&appid='.$api_key.'&lang='.$lang);
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
                        'unit' => ($unit == 'metric' ? '°C' : '°F')
                    );
                } else if ($result->cod == 404) {
                    $error = $result->message;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        return array(
            'city' => (isset($result->city->name) ? $result->city->name : $city),
            'error' => $error,
            'info' => $info,
            'selected_unit' => $unit,
            'units' => $valid_units
        );
    }
}
