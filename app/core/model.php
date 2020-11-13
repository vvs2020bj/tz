<?php

namespace app\core;

use PDO;


Abstract Class Model
{

	public $db;
	

	function __construct()
	{
		$this->db = new PDO('mysql:host=localhost;dbname=db;charset=utf8;', 'login', 'password'); // подключаем БД
	}

}