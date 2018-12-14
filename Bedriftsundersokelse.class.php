<?php
/**
 * \Teknix\NAV\Bedriftsundersokelse
 * Henter data fra NAV bedriftsundersøkelse
 * 
 * Det er en singleton klasse så det er ikke mulig
 * å instantiere klassen med $var = new Bedriftsundersøkelse();
 * Bruk heller $var = Bedriftsundersøkelse::getInstance();
 * 
 * @author  Kim Eirik Kvassheim <kek@teknix.no>
 * @version 1.0.0
 */
namespace Teknix\NAV;

class Bedriftsundersokelse
{
    private static $instance = null;
    private $csv_url = 'http://data.nav.no/dataset/92173c56-2262-47b2-9662-d0ceacf406d1/resource/a696d765-1ccb-46ca-a43e-7ec69af156ff/download/navs-bedriftsundersokelse---ettersporsel-etter-arbeidskraft-per-naering.csv';
    private $results = [];
    private $count = 0;
    private $error = false;
    
    private function __construct()
    {
        // todo: sjekk fil modified date
        if(!file_exists('cache.csv')) {
            $csv_temp = file_get_contents($this->csv_url);
            file_put_contents('cache.csv', $csv_temp);
        }

        $rows = array_map(
            function($v){ 
                return str_getcsv($v, ";"); 
            }, 
            file('cache.csv')
        );
        $header = array_shift($rows);
        $csv_data = [];
        foreach($rows as $row) {
            $csv_data[] = array_combine($header, $row);
        }
        
        if(!empty($csv_data)) {
            $this->results = $csv_data;
            $this->count = count($csv_data);
        }
        else {
            $this->error = true;
        }
        
    }

    public function getInstance()
    {
        if(!isset(self::$instance)) {
            self::$instance = new Bedriftsundersokelse();
        }
        return self::$instance;
    }

    public function filterByYear($year)
    {
        for($i=0; $i<$this->count; $i++) {
            if($this->results[$i]['År'] != $year) {
                unset($this->results[$i]);
            }
        }
        $this->count = count($this->results);
    }

    public function results()
    {
        return $this->results;
    }

    public function count()
    {
        return $this->count;
    }

    public function error()
    {
        return $this->error;
    }
}


