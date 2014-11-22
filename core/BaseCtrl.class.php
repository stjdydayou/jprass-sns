<?php

/**
 * JPrass framework
 * 
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	BaseCtrl.class.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */
if (!defined('__JPRASS_CORE_DIR__')) {
	exit('request error');
}

//Smarty引擎
require_once(__JPRASS_CORE_DIR__ . "/smarty/Smarty.class.php");

abstract class BaseCtrl {

	protected $request;
	private $smarty;
	private $smartyConfig;

	/**
	 * 控制器初始化参数
	 */
	public function __init(HttpRequest $request) {

		$this->request = $request;

		//初始化Smarty模板
		$this->smarty = new Smarty;
		$this->smartyConfig = JPrassApi::C("smarty");
		$this->smarty->template_dir = __JPRASS_APP_RUN_DIR__ . "/" . $this->smartyConfig['template_dir']; //模板存放目录
		$this->smarty->compile_dir = __JPRASS_RUNTIME_PATH__ . "/tpl_c"; //编译目录
		$this->smarty->auto_literal = $this->smartyConfig['auto_literal'];  //忽略Smarty的左右限定符周围的空格
		$this->smarty->left_delimiter = $this->smartyConfig['left_delimiter']; //左定界符
		$this->smarty->right_delimiter = $this->smartyConfig['right_delimiter']; //右定界符
		$this->smarty->debugging = $this->smartyConfig['debugging']; //是否开启smarty调试模式
		$this->smarty->cache_dir = __JPRASS_RUNTIME_PATH__ . '/caches'; //缓存文件目录
		$this->smarty->caching = $this->smartyConfig['caching']; //是否开启缓存
		$this->smarty->cache_lifetime = $this->smartyConfig['cache_lifetime']; //缓存生命周期
	}

	public function __before() {
		//前置方法，在执行主方法前执行
	}

	public function __after() {
		//后置方法，在执行主方法后执行
	}

	/**
	 * 给视图赋值
	 */
	public function assign($key, $var, $nocache = false) {
		$this->smarty->assign($key, $var, $nocache);
	}

	/**
	 * 调用模板，实现模板展现
	 */
	public function display($tpl) {
		$this->smarty->display($tpl . $this->smartyConfig['tpl_suffix']);
	}

}

?>
