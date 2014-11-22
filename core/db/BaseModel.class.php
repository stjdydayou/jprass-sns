<?php

/**
 * @filename	BaseModel.class.php 
 * @encoding	UTF-8 
 * @author		jprass.com 
 * @link		http://www.jprass.com 
 * @mail		i@jprass.com
 * @copyright	Copyright (C) 2014 www.jprass.com 
 * @datetime	2014-5-15 16:25:18
 * @version	1.0.0
 * @Description
 */
abstract class BaseModel {

	public $mapping = array();

	public function __construct(Array $array = array()) {
		foreach ($array as $key => $value) {
			$property = $this->mapping[$key];
			$this->$property = $value;
		}
	}

	public function toArray($dbIndex = true) {
		$arr = array();
		foreach ($this->mapping as $key => $val) {
			if (isset($this->$val)) {
				$arr[$dbIndex ? $key : $val] = $this->$val;
			}
		}
		unset($this->mapping);
		return $arr;
	}

}
