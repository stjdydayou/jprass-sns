<?php

/**
 * JPrass framework
 * 
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	RunException.class.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */

/**
 * 导演异常处理基类
 */
class RunException extends Exception {

	public function __construct($message, $code = 500) {
		$this->message = $message;
		$this->code = $code;
	}

}

?>
