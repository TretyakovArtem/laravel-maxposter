<?php

namespace Pathfinder\LaravelMaxposter\Services\Maxposter;

use SimpleXMLElement;
use \GuzzleHttp\Client;
use Pathfinder\LaravelMaxposter\Models\Vehicle;

/**
 * Сервис для обработки данных с maxposter
 */
class Service
{
	public $cars;

	private $client;
	
	function __construct(Client $client)
	{
		$this->client = $client;
	}


	public function loadData()
	{
		$login    = env('MAXPOSTER_LOGIN', false);
		$password = env('MAXPOSTER_PASSWORD', false);


		$response = $this->client->get('http://export1.maxposter.ru/'. env('MAXPOSTER_API_VER') . '/' . env('MAXPOSTER_USER_ID') . '/vehicles.xml?page_size=20&page=1', [
		    'auth' => [$login, $password, 'basic']
		]);

		return new SimpleXMLElement($response->getBody()->getContents());
	}


	function xmlToArray ( $xmlObj, $output = array () )
	{      
	   foreach ( (array) $xmlObj as $index => $node ) {
			$output[$index] = (is_object($node)) ? $this->xmlToArray($node): $node;
		}
	  return $output;
	}


	function toCollect()
    {
    	$cars = [];
        $xml = $this->loadData();
        foreach ($xml->vehicles as $vehicle) {
        	foreach ($vehicle as $car) {
        		$cars[] = $this->xmlToArray($car);
        	}
        }

		$vehicles = collect($cars)->each(function($element, $key){
			return Vehicle::make($element);
		});

        return $vehicles;
    }
}