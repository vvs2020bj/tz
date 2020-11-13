<?php

namespace app\core;

use app\core\view;


Class Router
{

	protected $routes = [];
	protected $params = [];


	public function __construct()
	{
		$this->routes = [
			'/login' => [ 'controller' => 'auth', 'action' => 'login' ]
		];

		$this->vars = [
			'site-pref' => ''
		];
	}


	public function run()
	{
		$param = $this->match();
		
		if($param) {
		
			// Файл контроллера доступен?
			if(is_readable(APP_PATH.'/app/controllers/'.$param['controller'].'_controller.php')) {
					
				$path = 'app\controllers\\'.strtolower($param['controller']).'_controller';
				
				$class = new $path($param, $this->vars);

				if(is_callable([$class, $param['action']])) {
					$action = $param['action'];
					$class->$action();
					return;
				}

			}
			
		}

		View::error(404);

	}


	public function match()
	{
		$url = parse_url($_SERVER['REQUEST_URI']);

		if(isset($this->routes[$url['path']])) {

			// Маршрут в правилах найден
			return $this->routes[$url['path']];

		} else {

			// Маршрут в правилах не найден
			$this->get_params($controller, $action, $file);
			return [ 'controller' => $controller, 'action' => $action ];

		}

		return false;
	}


	protected function get_params(&$controller, &$action, &$file)
	{
		
		$pref = str_replace($_SERVER['DOCUMENT_ROOT'], '', APP_PATH);
		$this->vars['site-pref'] = $pref;
		$uri = parse_url(str_replace($pref, '', $_SERVER['REQUEST_URI']));

		$parts = explode('/', trim(urldecode($uri['path']), '/'));
		if(empty($parts) || empty($parts[0])) { $controller = 'index'; }

		// Перебираем секции URI, ищем название файла контроллера
		foreach ($parts as $part) {
			$file = APP_PATH.'/app/controllers/'.$part.'_controller.php';
			array_shift($parts);

			if(is_readable($file)) { // ...если нашли - прекращаем обход
				$controller = $part;
				break;
			}
		}

		// Action
		$action = array_shift($parts);
		if($action == null) {
			$action = 'default'; // ...если метод не указан - устанавливаем по-умолчанию
		}

		$file = APP_PATH.'/app/controllers/'.$controller.'.php'; // путь к файлу контроллера

	}

}