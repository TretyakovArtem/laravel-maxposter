<?php

use \GuzzleHttp\Client;

/**
 * Сервис для обработки данных с maxposter
 */
class MaxposterService
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

		$response = $client->get('http://www.server.com/endpoint', [
		    'auth' => [
		        'username' => $login, 
		        'password' => $password
		    ]
		]);
	}
}