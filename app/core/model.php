<?php

namespace app\core;

use PDO;


Abstract Class Model
{

	public $db;
	

	function __construct()
	{
		$this->db = new PDO('mysql:host=localhost;dbname=tz;charset=utf8;', 'tz', 'mnbjyg6r'); // подключаем БД
	}

}