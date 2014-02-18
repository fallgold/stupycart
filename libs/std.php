<?php
/**
 * std method collage
 *
 */
class STD
{
	/**
	 * 计算时间差 $t1 - $t0
	 * （参数同时支持microtime及microtime(true)的返回值）
	 *
	 * @param string/float $t1
	 * @param string/float $t0
	 * @return float(%.4f)
	 */
	static function subt($t1, $t0, $isRet = true)
	{
		if (!is_float($t1)) {
			list($usec1, $sec1) = explode(" ", $t1);
			$t1 = (float)$usec1 + (float)$sec1;
		}
		if (!is_float($t0)) {
	    	list($usec0, $sec0) = explode(" ", $t0);
	    	$t0 = (float)$usec0 + (float)$sec0;
		}
		$ret = sprintf('%.4f', $t1-$t0);
		if ($isRet)
			return $ret;
		else
			echo "<div style=\"color:#fff\">$ret</div>";
	}

	static function time_log($info='')
	{
		if(in_array((isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:''), array(
			'192.168.137.161',
		))) {
			if ($info != '') {
				$tmp_t = std::subt(microtime(), $GLOBALS['t']);
				$GLOBALS['t'] = microtime();
				file_put_contents(_TMP_.'/time.log', sprintf("%-20s:  %s \n", $info, $tmp_t), FILE_APPEND);
			} else {
				$GLOBALS['t'] = microtime();
			}
		}
	}

	/**
	 * debug trace
	 * format and print debug_backtrace's result
	 *
	 * @param int $ignoreDepthHead 前置忽略深度
	 * @param int $ignoreDepthHead 后置忽略深度
	 */
	static function trace($return=false, $ignoreDepthHead=0, $ignoreDepthEnd=0)
	{
		$s = debug_backtrace();
		$cnt = count($s);
		$ignoreDepthEnd++; // ignore self::trace();
		$str = '';
		for ($i=0,$j=($cnt-1)-$ignoreDepthHead; $j>=$ignoreDepthEnd; $i++,$j--) {
			$item =& $s[$j];
			$str .= sprintf("#%d: %s:%d,  %s%s%s()\n", $i, isset($item['file'])?$item['file']:'', isset($item['line'])?$item['line']:'', isset($item['class'])?$item['class']:'null', isset($item['type'])?$item['type']:'->', $item['function']);
		}
		$str .= "\n";
		if ($return) {
			return $str;
		} else {
			echo $str;
		}
	}

	/**
	 * 检测返回调用者(caller)函数名，类名
	 *
	 * @return array
	 */
	static function getCaller()
	{
		$trace = debug_backtrace();
		array_shift($trace);
		array_shift($trace);
		$caller = array_shift($trace);
		$s = array(
			'class' => isset($caller['class']) ? $caller['class'] : '',
			'function' => isset($caller['function']) ? $caller['function'] : '',
		);
		return $s;
	}

	/**
	 * object to array
	 *
	 * @param object $obj (ref)
	 */
	static function obj2array(&$obj)
	{
		$obj = (array) $obj;
		foreach ($obj as &$item) {
			if (is_object($item) || is_array($item)) {
				self::obj2Array($item);
			}
		}
	}

	/**
	 * array to object
	 *
	 * @param array $arr (ref)
	 */
	static function array2obj(&$arr)
	{
		$arr = (object) $arr;
		foreach ($arr as &$item) {
			if (is_object($item) || is_array($item)) {
				self::array2Obj($item);
			}
		}
	}


	/**
	 * debug log
	 * 打印变量写入log文件中
	 * 默认写到同一个文件 ./tmp/std.log
	 *
	 * 如果某个参数是个字符串且形如"\$myvar"，则只记录字符串，不做变量打印处理，用以记录变量名。
	 *
	 * @param mix $var, $var1 ... $varN #要记录的变量
	 * @example
	 *	std::log2file($this->_request->getControllerName());
	 *	std::log2file($this->_request->getActionKey(), $this->_request->getParams());
	 *  std::log2file('$result', $result); // 同时记录变量名
	 */
	static function log2file($var1)
	{
		static $logFile = null; // 同一进程里多次调用本函数时，使用同一个logFile
		$firstLog = false;
		if (is_null($logFile)) {
			$firstLog = true;
			if (defined('_TMP_')) {
				$tmpDir = _TMP_;
			} else {
				$tmpDir = '/tmp';
			}
			/*
			//是否单独文件记录(单进程请求内还是同一个文件)
			//$logFile = $tmpDir . 'std.'. date('ymd.H.i') .'.log';*/
			$logFile = $tmpDir . '/std.log';
		}

		/**
		 * 读取调用本函数的上下文环境
		 */
		$s = debug_backtrace();
		$item = current($s);
		//$debugTraceInfo = sprintf("#%d: %s:%d,  %s%s%s()\n", 0, $item['file'], $item['line'], isset($item['class'])?$item['class']:'null', isset($item['type'])?$item['type']:'->', $item['function']);
		$debugTraceInfo = sprintf("log> %s:%d\n", $item['file'], $item['line']);
		//$item = next($s);
		//$debugTraceInfo1 = sprintf("log> %s:%d\n", $item['file'], $item['line']);

		// log
		ob_start();
		if ($firstLog) {
			echo '[' . date('Y-m-d H:i:s') . "]\n";
		}
		echo $debugTraceInfo;
		//echo $debugTraceInfo1;
		$argc = func_get_args();
		foreach ($argc as $var) {
			if (is_string($var) && preg_match('/^\$.+$/', $var)) { // 变量名
				echo $var . ":\n";
			} else { // 变量
				var_dump($var);
			}
		}
		echo "\n\n";
		$s = ob_get_clean();
		file_put_contents($logFile, $s, FILE_APPEND);
	}

	/**
	 * debug log
	 * 变量序列化存储到文件中
	 *
	 * 提供key则存储于 ./tmp/var.key.log
	 * 默认key则存储至 ./tmp/var.log
	 *
	 * @param mix $var
	 * @param string $key
	 */
	static function logvar_set($var, $key=null)
	{
		if (empty($key) || !is_string($key)) {
			$file = _TMP_ . "/var.log";
		} else {
			$file = _TMP_ . "/var.{$key}.log";
		}
		file_put_contents($file, serialize($var));
	}

	/**
	 * debug log
	 * 读取序列化存储到文件中的变量
	 *
	 * 提供key则查询 ./tmp/var.key.log
	 * 默认key则查询 ./tmp/var.log
	 *
	 * @param string $key
	 */
	static function logvar_get($key=null)
	{
		if (empty($key) || !is_string($key)) {
			$file = _TMP_ . "/var.log";
		} else {
			$file = _TMP_ . "/var.{$key}.log";
		}
		if (!file_exists($file)) {
			//throw new Exception('file not exist');
			return null;
		}
		return unserialize(file_get_contents($file));
	}

	/**
	 * 生成json格式数据
	 * php的json_encode默认给汉字unicode编码！
	 * 本函数进行反编码，调用接口需保持编码一致
	 *
	 * 主要是flash接口返回数据使用，flash端不会自动转编码.
	 *
	 * 注意：
	 * apt-get默认安装的iconv使用glibc
	 * window下或别的一些环境使用libiconv
	 * 目前，不知道怎么把glibc换成libiconv
	 * 为了统一，改为mb_convert_encoding
	 *
	 * @param mix $data
	 * @return string
	 */
	static function json_encode($data)
	{
		$s = json_encode($data);
		// 可使用iconv，但须确认iconv使用的lib：
		//$s = preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2', 'UTF-8', pack('v', 0x\\1))", $s); // glibc, little endian
		//$s = preg_replace("/\\\u([0-9a-f]{4})/ie", "iconv('UCS-2', 'UTF-8', pack('n', 0x\\1))", $s); // libiconv, big endian
		$s = preg_replace("/\\\u([0-9a-f]{4})/ie", "mb_convert_encoding(pack('H4', '\\1'), 'UTF-8', 'UCS-2')", $s);
		$s = str_replace("\\/", '/', $s);
		return $s;
	}

	/**
	 * 取客户端IP
	 *
	 * @return string
	 */
	static function getIp() {
		return
		(!empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown') ? $_SERVER['HTTP_CLIENT_IP'] :
			(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown') ? $_SERVER['HTTP_X_FORWARDED_FOR'] :
				(!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown') ? $_SERVER['REMOTE_ADDR'] : '')
			)
		);
	}

	/**
	 * 取客户端Hostname
	 *
	 * @return string
	 */
	static function getHost() {
		if (!empty($_SERVER['REMOTE_HOST']) && strcasecmp($_SERVER['REMOTE_HOST'], 'unknown')) {
			return $_SERVER['REMOTE_HOST'];
		} else {
			$ip = self::getIp();
			if (!empty($ip) && substr($ip, 0, 3) == '192') {
				return gethostbyaddr($ip);
			}
			return $ip;
		}
	}

	static function ksort($data, $isR=true)
	{
		if (is_array($data)
			&& !is_numeric(key($data)) // 关联数组才进行处理，自然数组跳过
		) {
			/**
			 * 按照key进行自然排序
			 * ksort不能自然排序，所以会造成比如10排在2前面，B排在a前面
			 * 所以使用uksort
			 */
			//ksort($data);
			uksort($data, "strnatcasecmp");
		}

		if ($isR
			&&
			(is_array($data) || is_object($data))
		) {
			foreach ($data as $k=>&$item) {
				$item = self::ksort($item, $isR);
			}
		}

		return $data;
	}

	static function dump()
	{
		static $a = 0;
		if (!$a && !isset($_GET['curl'])) {
			$a = 1;
			echo "<style>
				pre {font-size:14px;line-height:150%;padding:2px 5px 5px;margin:0}
				b {font-size:14px;line-height:120%;padding:2px;margin:0}
				hr {line-height:50%;padding:0;margin:2px 0 5px}
				</style>";
		}
		$arr = func_get_args();
		if ($arr[0] == '_dump_error_') {
			unset($arr[0]);
			echo '<pre style="color:red">';
		} elseif ($arr[0] == '_dump_debug_') {
			unset($arr[0]);
			echo '<pre style="color:#faa05b">';
		} else
			echo '<pre>';
		foreach ($arr as $a) {
			print_r($a);
			echo "\n";
		}
		echo '</pre>';
	}

	static function dumperr()
	{
		$arr = func_get_args();
		array_unshift($arr, '_dump_error_');
		call_user_func_array('self::dump', $arr);
	}

	static function debug()
	{
		$arr = func_get_args();
		array_unshift($arr, '_dump_debug_');
		call_user_func_array('self::dump', $arr);
	}

	/**
	 * exec方式运行系统命令
	 * @param string $cmd
	 * @param array $result output
	 * @param string $ret
	 */
	static function exec($cmd, &$result=null, &$ret=null)
	{
		return exec($cmd, $result, $ret);
	}

	//static function exec1($cmd, &$output=null, &$ret=null)
	//{
		//if (strpos($cmd, '2>&1')===false) {
			//$cmd .= ' 2>&1';
		//}
		//ob_start();
		//passthru($cmd, $ret);
		//$output = ob_get_clean();
		//return true;
	//}

	const EXEC_SIGN_BEGIN = 'BEGIN';
	const EXEC_SIGN_END = 'END';
	static function execCtrl($sign, $key = 'common')
	{
		$pid = _TMP_ . "/exec_ctrl.{$key}.pid";
		if ($sign == self::EXEC_SIGN_BEGIN) {
			if (file_exists($pid)) {
				if (file_get_contents($pid) != '') {
					$base = ".";
					if ($app = Yaf_Application::app())
						$base = $app->getDispatcher()->getRequest()->getBaseUri();
					throw new Exception("并发冲突，请稍候再尝试。<a href='{$base}/admin/conflictresolved/?key={$key}' onclick='return confirm(\"确定强制取消冲突？\");'>[强制取消冲突]</a>");
				} elseif (!is_writable($pid)) {
					throw new Exception('exec pid not writable');
				}
			}
			file_put_contents($pid, 'running...');
			@chmod($pid, 0777);
			return true;

		} else {
			file_put_contents($pid, '');
			return true;
		}
	}

	/**
	 * 截取utf-8编码字符串
	 * @chlen: 汉字当几个长度计算. 1, 2, ...或者-1(原始长度)
	 */
	static function substr($string, $length, $chlen = -1) {
		$slen = strlen($string);
		if(!$length || $slen <= $length)
			return $string;
		$n = 0;
		$tn = 0;
		$noc = 0;
		while ($n < $slen) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1;
				$n++;
				$noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2;
				$n += 2;
				$noc += ($chlen == -1 ? $tn : $chlen);
			} elseif(224 <= $t && $t < 239) {
				$tn = 3;
				$n += 3;
				$noc += ($chlen == -1 ? $tn : $chlen);
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4;
				$n += 4;
				$noc += ($chlen == -1 ? $tn : $chlen);
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5;
				$n += 5;
				$noc += ($chlen == -1 ? $tn : $chlen);
			} elseif($t == 252 || $t == 253) {
				$tn = 6;
				$n += 6;
				$noc += ($chlen == -1 ? $tn : $chlen);
			} else {
				$n++;
			}
			if ($noc >= $length)
				break;
		}
		if ($noc > $length)
			$n -= $tn;
		return rtrim(substr($string, 0, $n));
	}

	static function flipList($list, $field) {
		$newlist = array();
		foreach($list as $k => $item)
			$newlist[$item[$field]] = $item;
		return $newlist;
	}
}
?>
