<?php

/**
 * JPrass framework
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	MainCtrl.class.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */
require_once __JPRASS_SERVICE_DIR__ . '/UserService.class.php';

class MainCtrl extends BaseCtrl {

	public function index() {
		return "main";
	}

}
