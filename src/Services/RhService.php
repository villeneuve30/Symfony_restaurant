<?php


namespace App\Services;


use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RhService
{
    private $url;
    public function __construct(LoggerInterface $logger, ParameterBagInterface $paramBag)
    {
        $this->url = $paramBag->get('rh_api_endpoint');
    }

    public function getPeople(){
        $url = $this->url.'?method=people';
        $result = json_decode(file_get_contents($url), true);
        return $result;
    }
    public function getDayTeam($date){
        $url = $this->url.'?method=planning&date='.$date;
        $result = json_decode(file_get_contents($url), true);
        return $result;
    }
}