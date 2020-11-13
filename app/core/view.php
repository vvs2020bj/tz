<?php

namespace app\core;


Class View
{

	public $route;
	public $vars;
	public $layout = '__layout';


	function __construct($route, $vars)
	{
		
		$this->route = $route;
		$this->vars = $vars;

	}


	public function render($file, $title='', $vars=[])
	{
		$PREF 		= $this->vars['site-pref'].'/public/templates/base';
		$PREF_LIB 	= $this->vars['site-pref'].'/public/lib';
		$TPL_PATH 	= APP_PATH.'/public/templates/base';

		if(!empty($vars)) { extract($vars); }

		ob_start();
		require($TPL_PATH.'/_header.html');
		require($TPL_PATH.'/'.$file.'.html');
		require($TPL_PATH.'/_footer.html');
		$page_content = ob_get_clean();

		require($TPL_PATH.'/'.$this->layout.'.html');
	}


	public function render_body($file, $title='', $vars=[])
	{
		$PREF 		= $this->vars['site-pref'].'/public/templates/base';
		$PREF_LIB 	= $this->vars['site-pref'].'/public/lib';
		$TPL_PATH 	= APP_PATH.'/public/templates/base';

		if(!empty($vars)) { extract($vars); }

		require($TPL_PATH.'/'.$file.'.html');
	}


	public static function error($code)
	{
		http_response_code($code);
		require(APP_PATH.'/public/templates/base/err'.$code.'.html');
		exit;
	}

}