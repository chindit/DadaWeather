<?php

namespace Dada\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller{

    /**
     * Front controller.  Nothing special
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(){
        return $this->render('DadaCoreBundle::index.html.twig');
    }

    /**
     * Render when searching name
     *
     * @param Request $request Form submission
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request){
        $townName = $request->get('town_name');
        //town name must be longer than 3
        if(strlen($townName) < 3){
            $this->get('session')->getFlashBag()->add('danger', 'Unable to process the city requested.  Name is too short');
            return $this->redirectToRoute('front_index');
        }
        //If town name is OK -> searching
        $owApi = $this->get('dada_weather.openweather');
        $result = $owApi->getWeatherFromName($townName);
        if($result === false){
            $this->get('session')->getFlashBag()->add('danger', 'Unfortunately, the city you\'re looking for could not be found');
            return $this->redirectToRoute('front_index');
        }
        return $this->render('DadaCoreBundle::index.html.twig', array('weather' => $result->list[0], 'city_name' => $result->city->name, 'weatherData' => json_encode($result)));
    }
}
