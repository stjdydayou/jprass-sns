<?php

/**
 * JPrass framework
 * 
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	index.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */
//引用mvc框架
require_once "./JPrass.php";

try {
	//初始化一个请求
	$request = new HttpRequest();
	
	//创建主体对象
	$JPrass = new JPrassRun($request);
	
	//执行主方法
	$JPrass->run();
	
} catch (Exception $exp) {
	
	//对异常的处理
	JPrassApi::handlerException($exp);
}
