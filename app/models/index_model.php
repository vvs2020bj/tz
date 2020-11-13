<?php

namespace app\models;

use app\core\model;


Class Index_Model Extends Model {

	public function query_tasks($limit_from, $limit_num) {
		$query = $this->db->query('SELECT * FROM `tasks`,`user` WHERE `user`.`u_id`=`tasks`.`ts_user_id` ORDER BY `tasks`.`ts_id` DESC LIMIT '.$limit_from.','.$limit_num);
		return $query->fetchAll();
	}


	public function query_tasks_count() {
		$query = $this->db->query('SELECT COUNT(*) FROM `tasks`');
		return $query->fetch();
	}


	public function task_save($name, $email, $text) {

		$user = $this->get_user_by_email($email);
		
		if(!isset($user['u_email'])) {
			$str = 'INSERT INTO `user` (`u_name`,`u_email`) VALUES (:name, :email)';
			$params = [
				':name' => $name,
				':email' => $email
			];
			$query = $this->db->prepare($str);
			$query->execute($params);
			$uid = $this->db->lastInsertId();


			$str = 'INSERT INTO `tasks` (`ts_user_id`,`ts_text`) VALUES (:uid, :txt)';
			$params = [
				':uid' => $uid,
				':txt' => $text
			];
			$query = $this->db->prepare($str);
			$query->execute($params);

		} else {

			$str = 'INSERT INTO `tasks` (`ts_user_id`,`ts_text`) VALUES (:uid, :txt)';
			$params = [
				':uid' => $user['u_id'],
				':txt' => $text
			];
			$query = $this->db->prepare($str);
			$query->execute($params);

		}

	}


	public function login($login, $password) {

		/*$query = $this->db->prepare('SELECT `u_id`,`u_name`,`u_email` FROM `user` WHERE `u_login` = :login AND `u_psw` = :password');
		$query->execute([':login' => $login, ':password' => hash('sha256', $password)]);
		$user = $query->fetch();*/
		return false;

	}


	public function get_user_by_email($email) {

		$query = $this->db->prepare('SELECT `u_id`,`u_name`,`u_email` FROM `user` WHERE `u_email` = ?');
		$query->execute([$email]);
		return $query->fetch();
		
	}

}