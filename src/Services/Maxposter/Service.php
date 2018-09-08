<?php

namespace Pathfinder\LaravelMaxposter\Services\Maxposter;

use SimpleXMLElement;
use \GuzzleHttp\Client;
use Pathfinder\LaravelMaxposter\Models\Vehicle;


class Service
{
	private $client;
	
	function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * Function for load data from maxposter server
	 *
	 * @return SimpleXMLElement
	 */
	public function loadData()
	{
		$login    = env('MAXPOSTER_LOGIN', false);
		$password = env('MAXPOSTER_PASSWORD', false);


		$response = $this->client->get('http://export1.maxposter.ru/'. env('MAXPOSTER_API_VER') . '/' . env('MAXPOSTER_LOGIN') . '/vehicles.xml?page_size=20&page=1', [
		    'auth' => [$login, $password, 'basic']
		]);

		return new SimpleXMLElement($response->getBody()->getContents());
	}

	/**
	 * Function for convert xml to array
	 *
	 * @param [type] $xmlObj
	 * @param [type] $output
	 * @return array
	 */
	public function xmlToArray ( $xmlObj, $output = array () )
	{      
	   foreach ( (array) $xmlObj as $index => $node ) {
			$output[$index] = (is_object($node)) ? $this->xmlToArray($node): $node;
		}
	  return $output;
	}


	/**
	 * Make collection of Vehicles from array
	 *
	 * @return Illuminate\Support\Collection
	 */
	public function toCollect()
    {
    	$cars = [];
        $xml = $this->loadData();
        foreach ($xml->vehicles as $vehicle) {
        	foreach ($vehicle as $car) {
        		$cars[] = $this->xmlToArray($car);
        	}
		}
		
		$vehicles = collect($cars)->map(function($element, $key){
			return Vehicle::make($element);
		});

        return $vehicles;
    }
}