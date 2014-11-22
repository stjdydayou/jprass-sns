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
class MainCtrl extends BaseCtrl {

	public function index() {
		$query = new Query("#_role", "t");
		return "main";
	}

}

?>