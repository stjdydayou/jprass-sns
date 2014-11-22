<?php

/**
 * JPrass framework
 * cookie支持
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	Cookie.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */
class Cookie
{
    /**
     * 获取指定的COOKIE值
     *
     * @access public
     * @param string $key 指定的参数
     * @param string $default 默认的参数
     * @return mixed
     */
    public static function get($key, $default = NULL)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
    }

    /**
     * 设置指定的COOKIE值
     *
     * @access public
     * @param string $key 指定的参数
     * @param mixed $value 设置的值
     * @param integer $expire 过期时间,默认为0,表示随会话时间结束
     * @param string $url 路径(可以是域名,也可以是地址)
     * @return void
     */
    public static function set($key, $value, $expire = 0,$path = '/')
    {
        /** 对数组型COOKIE的写入支持 */
        if (is_array($value)) {
            foreach ($value as $name => $val) {
                setcookie("{$key}[{$name}]", $val, $expire, $path);
            }
        } else {
            setcookie($key, $value, $expire, $path);
        }
    }

    /**
     * 删除指定的COOKIE值
     *
     * @access public
     * @param string $key 指定的参数
     * @return void
     */
    public static function delete($key,$path = '/')
    {
        if (!isset($_COOKIE[$key])) { return; }
		
        /** 对数组型COOKIE的删除支持 */
        if (is_array($_COOKIE[$key])) {
            foreach ($_COOKIE[$key] as $name => $val) {
				unset($val);
                setcookie("{$key}[{$name}]", '', time() - 2592000, $path);
            }
        } else {
            setcookie($key, '', time() - 2592000, $path);
        }
    }
}

