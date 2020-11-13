<?php

namespace app\core;

use app\core\view;


Abstract Class Controller
{

	public $route;
	public $model;
	public $view;


	public function __construct($route, $vars)
	{
		
		$this->route 	= $route;
		$this->vars 	= $vars;
		$this->view 	= new View($route, $vars);
		$this->model 	= $this->load_model($route['controller']);
		
	}

	public function load_model($name)
	{
		$class = 'app\models\\'.$name.'_model';
		if(class_exists($class)) {
			return new $class;
		}
	}

}