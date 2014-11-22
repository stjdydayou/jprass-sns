<?php

/**
 * JPrass framework
 * 
 * @copyright  Copyright (c) 2013 www.jprass.com
 * @filename	StringFilter.class.php
 * @author		jprass.com
 * @mail		i@jprass.com
 * @QQ			97142822
 * @version    1.0
 */
class StringFilter {

	private $_strval;
	//系统可用的过滤器
	private $_endable_filters = array(
		'int' => 'intval',
		'search' => array('self', 'searchQuery'),
		'xss' => array('self', 'removeXSS'),
		'url' => array('self', 'safeUrl'),
		'text' => array('self', 'html2Text')
	);

	public function __construct($str) {

		$this->_strval = $str;
	}

	public function filter() {
		$filters = func_get_args();

		foreach ($filters as $filter) {

			$useFilter = is_string($filter) && isset($this->_endable_filters[$filter]) ? $this->_endable_filters[$filter] : $filter;

			$this->_strval = is_array($this->_strval) ? array_map($useFilter, $this->_strval) : call_user_func($useFilter, $this->_strval);
		}

		return $this->_strval;
	}

	public static function val($str) {

		$stringFilter = new StringFilter($str);

		return $stringFilter;
	}

	/**
	 * 处理XSS跨站攻击的过滤函数
	 *
	 * @author kallahar@kallahar.com
	 * @link http://kallahar.com/smallprojects/php_xss_filter_function.php
	 * @access public
	 * @param string $val 需要处理的字符串
	 * @return string
	 */
	private static function removeXSS($val) {
		// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
		// this prevents some character re-spacing such as <java\0script>
		// note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
		$val = preg_replace('/([\x00-\x08]|[\x0b-\x0c]|[\x0e-\x19])/', '', $val);

		// straight replacements, the user should never need these since they're normal characters
		// this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
		$search = 'abcdefghijklmnopqrstuvwxyz';
		$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$search .= '1234567890!@#$%^&*()';
		$search .= '~`";:?+/={}[]-_|\'\\';

		for ($i = 0; $i < strlen($search); $i++) {
			// ;? matches the ;, which is optional
			// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
			// &#x0040 @ search for the hex values
			$val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
			// &#00064 @ 0{0,7} matches '0' zero to seven times
			$val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
		}

		// now the only remaining whitespace attacks are \t, \n, and \r
		$ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
		$ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		$ra = array_merge($ra1, $ra2);

		$found = true; // keep replacing as long as the previous round replaced something
		while ($found == true) {
			$val_before = $val;
			for ($i = 0; $i < sizeof($ra); $i++) {
				$pattern = '/';
				for ($j = 0; $j < strlen($ra[$i]); $j++) {
					if ($j > 0) {
						$pattern .= '(';
						$pattern .= '(&#[xX]0{0,8}([9ab]);)';
						$pattern .= '|';
						$pattern .= '|(&#0{0,8}([9|10|13]);)';
						$pattern .= ')*';
					}
					$pattern .= $ra[$i][$j];
				}
				$pattern .= '/i';
				$replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
				$val = preg_replace($pattern, $replacement, $val); // filter out the hex tags

				if ($val_before == $val) {
					// no replacements were made, so exit the loop
					$found = false;
				}
			}
		}

		return $val;
	}

	/**
	 * 过滤用于搜索的字符串
	 *
	 * @access public
	 * @param string $query 搜索字符串
	 * @return string
	 */
	private static function searchQuery($query) {
		return str_replace(array('%', '?', '*', '/', '{', '}'), '', $query);
	}

	/**
	 * 将url中的非法字符串
	 *
	 * @access private
	 * @param string $string 需要过滤的url
	 * @return string
	 */
	private static function safeUrl($url) {
		//~ 针对location的xss过滤, 因为其特殊性无法使用removeXSS函数
		//~ fix issue 66
		$params = parse_url(str_replace(array("\r", "\n"), '', $url));

		/** 禁止非法的协议跳转 */
		if (isset($params['scheme'])) {
			if (!in_array($params['scheme'], array('http', 'https'))) {
				return;
			}
		}

		//过滤解析串:将url中的非法xss去掉
		foreach ($params as $key => $value) {
			$value = str_replace(array('%0d', '%0a'), '', strip_tags($value));
			preg_replace(array(
				"/\(\s*(\"|')/i", //函数开头
				"/(\"|')\s*\)/i", //函数结尾
					), '', $value);
			$params[$key] = $value;
		}

		return (isset($params['scheme']) ? $params['scheme'] . '://' : NULL)
				. (isset($params['user']) ? $params['user'] . (isset($params['pass']) ? ':' . $params['pass'] : NULL) . '@' : NULL)
				. (isset($params['host']) ? $params['host'] : NULL)
				. (isset($params['port']) ? ':' . $params['port'] : NULL)
				. (isset($params['path']) ? $params['path'] : NULL)
				. (isset($params['query']) ? '?' . $params['query'] : NULL)
				. (isset($params['fragment']) ? '#' . $params['fragment'] : NULL);
	}
	
	/**
	 * 取出html中的纯文本内容
	 * @param String $str
	 * @return String
	 */
	private static function html2Text($str) {
		$str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU", "", $str);
		$alltext = "";
		$start = 1;
		for ($i = 0; $i < strlen($str); $i++) {
			if ($start == 0 && $str [$i] == ">") {
				$start = 1;
			} else if ($start == 1) {
				if ($str [$i] == "<") {
					$start = 0;
					$alltext .= " ";
				} else if (ord($str [$i]) > 31) {
					$alltext .= $str [$i];
				}
			}
		}
		$alltext = str_replace("　", " ", $alltext);
		$alltext = str_replace(" ", "", $alltext);
		$alltext = preg_replace("/&([^;&]*)(;|&)/", "", $alltext);
		$alltext = preg_replace("/[ ]+/s", " ", $alltext);
		$alltext = str_replace(array("'"), array('&#39;'), $alltext);
		
		$alltext = htmlspecialchars($alltext);
		return $alltext;
	}

}

?>
