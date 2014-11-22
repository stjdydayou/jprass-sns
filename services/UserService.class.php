<?php

/**
 * Description of UserService
 *
 * @author Administrator
 */
require_once __JPRASS_MODEL_DIR__ . '/User.php';

class UserService {

	private $query;

	public function __construct() {
		$this->query = new Query("#_users", "t");
	}

	public function findById($id) {
		$user = $this->query->where("t.id = '" . $id . "'")->find();
		return new User($user);
	}

	public function updateLastLoginTime($id, $datetime) {
		$user = new User();
		$user->lastLoginTime = $datetime;
		var_dump($user instanceof BaseModel);
		$this->query->where("id='" . $id . "'")->update($user);
	}

}
