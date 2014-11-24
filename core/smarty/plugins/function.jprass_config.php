<?php

/**
 * JPrass Blog
 * 
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	function.jprass_config.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */
function smarty_function_jprass_config($params){
     $name = $params['name']; //分页数据
     return JPrassApi::C($name);
}
?>
