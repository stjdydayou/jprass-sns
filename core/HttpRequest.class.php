<?php

/**
 * JPrass framework
 * 服务器请求处理类
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	HttpRequest.class.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */
class HttpRequest {

	private $_client_IP;
	private $_params = array();

	public function __construct() {
		$_ctrl = $this->get(JPrassApi::C("default_url.ctrl"), JPrassApi::C("default_route.ctrl"));
		$_action = $this->get(JPrassApi::C("default_url.action"), JPrassApi::C("default_route.action"));

		$this->_params['_ctrl'] = StringFilter::val($_ctrl)->filter("url");
		$this->_params['_action'] = StringFilter::val($_action)->filter("url");
		$this->_params['_request_method'] = strtolower($_SERVER['REQUEST_METHOD']);

		/** 处理PHP的magic_quotes，对所有服务器的PHP版本兼容 */
		if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
			$_GET = JPrassApi::stripslashes($_GET);
			$_POST = JPrassApi::stripslashes($_POST);
			$_COOKIE = JPrassApi::stripslashes($_COOKIE);
			reset($_GET);
			reset($_POST);
			reset($_COOKIE);
		}
	}

	/**
	 * 获取实际传递参数(magic)
	 *
	 * @access public
	 * @param string $key 指定参数
	 * @return void
	 */
	public function __get($key) {
		return $this->get($key);
	}

	/**
	 * 判断参数是否存在
	 *
	 * @access public
	 * @param string $key 指定参数
	 * @return void
	 */
	public function __isset($key) {
		return isset($_GET[$key]) || isset($_POST[$key]) || isset($_COOKIE[$key]) || isset($this->_params[$key]);
	}

	/**
	 * 获取实际传递参数
	 *
	 * @access public
	 * @param string $key 指定参数
	 * @param mixed $default 默认参数 (default: NULL)
	 * @return void
	 */
	public function get($key, $default = NULL) {
		$value = $default;
		switch (true) {
			case isset($this->_params[$key]):
				$value = $this->_params[$key];
				break;
			case isset($_GET[$key]):
				$value = $_GET[$key];
				break;
			case isset($_POST[$key]):
				$value = $_POST[$key];
				break;
			case isset($_COOKIE[$key]):
				$value = $_COOKIE[$key];
				break;
			default:
				$value = $default;
				break;
		}

		return is_array($value) || strlen($value) > 0 ? $value : $default;
	}

	/**
	 * 获取ip地址
	 * @access public
	 * @return string
	 */
	public function getClientIP() {
		if (NULL === $this->_client_IP) {
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {

				$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

				$pos = array_search('unknown', $arr);

				if (false !== $pos) {
					unset($arr[$pos]);
				}

				$this->_client_IP = trim($arr[0]);
			} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {

				$this->_client_IP = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (isset($_SERVER['REMOTE_ADDR'])) {

				$this->_client_IP = $_SERVER['REMOTE_ADDR'];
			}
			// IP地址合法验证
			$this->_client_IP = (false !== ip2long($this->_client_IP)) ? $this->_client_IP : '0.0.0.0';
		}

		return $this->_client_IP;
	}

	/**
	 * 设置http传递参数
	 *
	 * @access public
	 * @param string $name 指定的参数
	 * @param mixed $value 参数值
	 * @return void
	 */
	public function setParam($name, $value) {

		$this->_params[$name] = $value;
	}

	/**
	 * 设置多个参数
	 *
	 * @access public
	 * @param mixed $params 参数列表
	 * @return void
	 */
	public function setParams($params) {
		$this->_params = array_merge($this->_params, $params);
	}

}
