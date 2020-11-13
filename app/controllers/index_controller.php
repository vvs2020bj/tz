<?php

namespace app\controllers;

use \app\core\controller;


Class Index_Controller Extends Controller {

	public function default()
	{
		$this->get_task_list($vars);
		$this->view->render('index', 'Главная страница', $vars);
	}


	public function get_task_list(&$vars)
	{
		
		$limit_tasks_on_page = 3;														// кол-во задач, показываемых на странице
		$task_list 		= $this->get_tasks($limit_tasks_on_page);						// список задач
		$paginator 		= $this->paginator($limit_tasks_on_page);						// список страниц

		$vars = [
			'TASK_LIST' => $task_list,
			'PAGINATOR' => $paginator
		];

	}


	public function get_form_task_add()
	{
		$this->view->render_body('task_add', 'Добавить задание', $vars);
	}


	public function save_task_add()
	{
		$name = $_REQUEST['name'];
		$email = $_REQUEST['email'];
		$text = $_REQUEST['text'];
		$this->model->task_save($name, $email, $text);

		$this->get_task_list($vars);
		$this->view->render_body('index', 'Главная страница', $vars);
	}


	public function get_form_login()
	{
		$this->view->render_body('auth_form', 'Авторизоваться');
	}


	public function login()
	{
		/*$login 		= $_REQUEST['login'];
		$password 	= $_REQUEST['password'];
		$this->model->login($login, $password);*/

		$this->get_task_list($vars);
		$this->view->render_body('index', 'Главная страница', $vars);
	}


	// Получение списка задач
	private function get_tasks($limit_tasks_on_page) {
		
		$tasks 		= [];
		
		$page_num 	= isset($_REQUEST['p']) && !empty($_REQUEST['p']) ? (int) $_REQUEST['p'] : false;
		if($page_num==0) { $page_num = 1; }
		$limit_from = $page_num*$limit_tasks_on_page - $limit_tasks_on_page;

		$tasks = $this->model->query_tasks((int) $limit_from, (int) $limit_tasks_on_page);

		return $tasks;

	}


	// Список страниц
	private function paginator($limit_tasks_on_page) {

		$html 		= false;
		$html_last 	= false;
		
		$current_page_num = isset($_REQUEST['p']) && !empty($_REQUEST['p']) ? $_REQUEST['p'] : false;
		if($current_page_num==0) { $current_page_num = 1; }

		$tasks_num = $this->model->query_tasks_count()[0];
		$links_limit = 10;
		$links_limit_half = floor($links_limit/2);
		$links_num = ceil($tasks_num / $limit_tasks_on_page);
		$li_current = '';

		$start 	= $current_page_num <= $links_limit_half ? 1 : $current_page_num - $links_limit_half +2;
		$end 	= $start + $links_limit-1 <= $links_num ? $start + $links_limit-1 : $links_num;
		
		if($links_num - $current_page_num <= $links_limit_half && $links_num - $current_page_num >= 0) {
			$end = $current_page_num + $links_limit_half+1;
		}

		if($links_limit < $links_num) { $end -= 2; }

		if($start >= 3) {
			if($current_page_num==1) { $li_current = 'select'; }
			$html .= '<li class="'.$li_current.'"><a href="'.$this->vars['site-pref'].'/?p=1">1</a></li>
				<li class="no-brd"><span>...</span></li>';
			$end -= 2;
		}

		if($end < $links_num) {
			if($current_page_num==$links_num) { $li_current = 'select'; }
			$html_last .= '<li class="no-brd"><span>...</span></li>
				<li class="'.$li_current.'"><a href="'.$this->vars['site-pref'].'/?p='.$links_num.'">'.$links_num.'</a></li>';
		} else {
			$end = $links_num;
			$start = $end - $links_limit+3;
		}


		$start 	= $start > 0 ? $start : 1;

		for ($i=$start; $i <= $end; $i++) {
			$li_current = '';
			if($current_page_num==$i) { $li_current = 'select'; }
			$html .= '<li class="'.$li_current.'"><a href="'.$this->vars['site-pref'].'/?p='.$i.'">'.$i.'</a></li>';
		}

		return '<div class="paginator"><ul>'.$html.$html_last.'</ul></div>';

	}

}