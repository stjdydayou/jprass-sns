<?php

/**
 * jprass framework
 * 
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	Jprass.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */
define('__JPRASS_VERSION__', "1.1.0");

//定义根目录
define('__JPRASS_ROOT_DIR__', dirname(__FILE__));

//定义运行目录
define('__JPRASS_RUNTIME_PATH__', __JPRASS_ROOT_DIR__ . "/runtime");

//定义核心文件目录
define('__JPRASS_CORE_DIR__', __JPRASS_ROOT_DIR__ . "/core");

//数据模型存放目录
define('__JPRASS_MODEL_DIR__', __JPRASS_ROOT_DIR__ . "/models");

//业务逻辑存放目录
define('__JPRASS_SERVICE_DIR__', __JPRASS_ROOT_DIR__ . "/services");

//子应用的运行目录
define('__JPRASS_APP_RUN_DIR__', dirname($_SERVER['SCRIPT_FILENAME']));

//字符过滤处理类
require_once(__JPRASS_CORE_DIR__ . "/StringFilter.class.php");

//引入API方法公共方法
require_once(__JPRASS_CORE_DIR__ . "/JPrassApi.class.php");

//HTTP请求公共类
require_once(__JPRASS_CORE_DIR__ . "/HttpRequest.class.php");

//异常处理类
require_once __JPRASS_CORE_DIR__ . '/exp/RunException.class.php';

//数据库查询公共类
require_once(__JPRASS_CORE_DIR__ . "/db/Query.class.php");

//应用主导类
require_once(__JPRASS_CORE_DIR__ . "/JPrassRun.class.php");
?>
