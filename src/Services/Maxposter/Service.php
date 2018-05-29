<?php

namespace Pathfinder\LaravelMaxposter\Services\Maxposter;

use \GuzzleHttp\Client;
use SimpleXMLElement;

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


	public function toCollect()
    {
        $xml = $this->loadData();
        dd($xml);
    }

}