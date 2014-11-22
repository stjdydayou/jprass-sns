<?php

/**
 * JPrass framework
 * 
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	Mysql.class.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */
class Mysql {

	/**
	 * 连接数据库
	 *
	 * @access      public
	 * @param       array    $config  数据库配置
	 * @return      resource 数据库连接标识
	 */
	public static function connect($config) {
		//根据配置使用不同函数连接数据库
		$config['host'] = isset($config['port']) ? $config['host'] . ':' . $config['port'] : $config['host'];

		$func = $config['pconnect'] ? 'mysql_pconnect' : 'mysql_connect';

		$link = @$func($config['host'], $config['user'], $config['pwd']);
		if (!$link) {
			throw new RunException("系统错误警告：连接数据库失败，可能数据库密码不对或数据库服务器出错！",503);
		}
		
		$database = @mysql_select_db($config['database'], $link);

		if (!$database) {
			throw new RunException('系统错误警告：选择数据库失败，请确认' . $config['database'] . '是否存在',503);
		}

		@mysql_query("SET NAMES '" . $config['characterSet'] . "', character_set_client=binary, sql_mode='', interactive_timeout=3600 ;", $link);

		return $link;
	}

}

?>
