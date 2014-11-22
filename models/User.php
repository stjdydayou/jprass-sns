<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Administrator
 */
class User extends BaseModel {

	public $mapping = array(
		'id' => 'id',
		'login_name' => 'loginName',
		'login_pwd' => 'loginPwd',
		'real_name' => 'realName',
		'last_login_time' => 'lastLoginTime',
		'last_login_ip' => 'lastLoginIp',
		'create_time' => 'createTime'
	);
}
