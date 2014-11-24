<?php

/**
 * JPrass framework
 * 
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	JPrassRun.class.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */
class JPrassRun {

	//各应用所在目录
	private $request;

	public function __construct(HttpRequest $request) {
		$this->request = $request;
	}

	public function run() {

		ob_start(); //控制输出
		//设置运行时区
		date_default_timezone_set("Etc/GMT" . (JPrassApi::C("site.default_timezone") * -1));

		//自动开启session
		if (JPrassApi::C('auto_session')) {
			@session_start();
		}

		header("Content-Type:text/html; charset=utf-8");

		$handleCtrlName = ucfirst($this->request->_ctrl) . "Ctrl";

		$ctrlFile = __JPRASS_APP_RUN_DIR__ . "/ctrl/" . $handleCtrlName . ".class.php";

		if (JPrassApi::C("Debug")) {
			JPrassApi::dump(array("Ctrl" => $ctrlFile, "Action" => $this->request->_action));
		}

		if (file_exists($ctrlFile)) {

			require_once(__JPRASS_CORE_DIR__ . "/BaseCtrl.class.php");

			require_once $ctrlFile;

			$startTime = microtime(); //开始执行方法时间

			$handleCtrl = new $handleCtrlName;

			$handleCtrl->__init($this->request);

			$handleCtrl->assign("handle_ctrl", $this->request->_ctrl);
			$handleCtrl->assign("handle_action", $this->request->_action);

			//执行前置方法
			if (method_exists($handleCtrl, "__before")) {
				$handleCtrl->__before();
			}

			//将同一个action分不同的请求类型去处理
			$handleAction = "_" . $this->request->_request_method . "_" . $this->request->_action;

			//如果请求类型是GET/POST则会去检测  _get_($this->action) / _post_($this->action)  方法不否存在,如果存在话则会放弃请求$this->action方法
			if (!method_exists($handleCtrl, $handleAction)) {
				$handleAction = $this->request->_action;
			}
			/**
			 * 检查所需要执行的方法是否存在
			 * 屏蔽掉以“__”(双下划线)开头的方法
			 */
			if (method_exists($handleCtrl, $handleAction) && !(strpos($this->request->_action, "__") === 0)) {

				//执行核心方法并得到返回结果
				$result = $handleCtrl->{$handleAction}();

				if (!empty($result)) {
					if (is_string($result)) {
						$handleCtrl->display($result); //如果执行方法返回的值为一个字符串，系统自认为是需要调用smarty模板
					} else {
						echo json_encode($result); //如果执行方法返回的值不是一个字符串，系统自动认为需要得到json
					}
				}
			} else {
				throw new Exception("您访问的内容不存在！", 404);
			}

			//执行前置方法
			if (method_exists($handleCtrl, "__after")) {
				$handleCtrl->__after();
			}
			$endTime = microtime(); //开始执行方法时间

			if (JPrassApi::C("Debug")) {
				JPrassApi::dump("执行所用时间：" . ($endTime - $startTime) . 'ms');
			}
		} else {
			throw new Exception("您访问的内容不存在！", 404);
		}
	}

}

?>
