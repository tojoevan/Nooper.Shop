<?php
// declare(strict_types = 1);

/**
 * $GLOBALS[_nooper_configs]
 */

/**
 * boolean function _log()
 */
function _log():bool {
	//
}

/**
 * void function set_config(string $key, mixed $value)
 */
function set_config(string $key, $value): void {
	if(is_underline_named_regular($key)) $GLOBALS['_nooper_configs'][$key] = $value;
}

/**
 * ?mixed function get_config(string $key, ?$default = null)
 */
function get_config(string $key, $default = null) {
	return $GLOBALS['_nooper_configs'][$key] ?? $default;
}

/**
 * array function get_configs(?array $keys = null)
 * @$keys = array((string $key => ?mixed $default)|string $key,...)
 */
function get_configs(array $keys = null): array {
	if(is_null($keys)) return $GLOBALS['_nooper_configs'] ?? array();
	foreach($keys as $key => $default){
		$datas[] = is_string($key) ? get_config($key, $default) : get_config($default);
	}
	return $datas ?? array();
}

/**
 * boolean function is_underline_named_regular(string $data)
 */
function is_underline_named_regular(string $data): bool {
	$pattern = '/^[a-z]+(_[a-z]+)*$/';
	return preg_match($pattern, $data) ? true : false;
}

/**
 * boolean function is_database_named_regular(string $data, boolean $wildcard = false)
 */
function is_database_named_regular(string $data, bool $wildcard = false): bool {
	return is_database_primary_named_regular($data, $wildcard) or is_database_plus_named_regular($data, $wildcard);
}

/**
 * boolean function is_database_primary_named_regular(string $data, boolean $wildcard = false)
 */
function is_database_primary_named_regular(string $data, bool $wildcard = false): bool {
	if(is_underline_named_regular($data)) return true;
	elseif($wildcard && '*' == $data) return true;
	return false;
}

/**
 * boolean function is_database_plus_named_regular(string $data, boolean $wildcard = false)
 */
function is_database_plus_named_regular(string $data, bool $wildcard = false): bool {
	$pieces = explode('.', $data);
	if(count($pieces) == 2){
		if(is_underline_named_regular($pieces[0]) && is_underline_named_regular($pieces[1])) return true;
		elseif($wildcard && is_underline_named_regular($pieces[0]) && '*' == $pieces[1]) return true;
	}
	return false;
}

/**
 * boolean function is_database_connect_params(array $datas)
 * @$datas = array(string 'rich'|'host'|'port'|'dbname'|'charset'|'username'|'password' => string $value)
 */
function is_database_connect_params(array $datas): bool {
	$keys = array('protocol', 'host', 'port', 'dbname', 'charset', 'username', 'password');
	if(count($datas) != count($keys)) return false;
	foreach($datas as $key => $value){
		if(!in_array($key, $keys, true)) return false;
		elseif(!is_string($value)) return false;
	}
	return true;
}

/**
 * string function camel_to_underline_named(string $data)
 */
function camel_to_underline_named(string $data): string {
	$pattern = '/([A-Z])/';
	$replace = '_$1';
	return strtolower(preg_replace($pattern, $replace, $data));
}

/**
 * string function pascal_to_underline_named(string $data)
 */
function pascal_to_underline_named(string $data): string {
	$data = camel_to_underline_named($data);
	return substr($data, 1);
}

/**
 * string function underline_to_pascal_named(string $data)
 */
function underline_to_pascal_named(string $data): string {
	$pattern = '/_([a-z])/';
	return preg_replace($pattern, function ($matches) {
		return strtoupper($matches[1]);
	}, '_' . $data);
}

/**
 * string function wrap_database_backquote(string $identifier)
 */
function wrap_database_backquote(string $identifier): string {
	$pieces = explode('.', $identifier);
	foreach($pieces as &$piece){
		$piece = '`' . $piece . '`';
	}
	return implode('.', $pieces);
}

/**
 * void function merge_key_to_data(string &$data, string $key)
 */
function merge_key_to_data(string &$data, string $key): void {
	$data = $key . '=' . $data;
}

/**
 * integer function get_now_timestamp(void)
 */
function get_now_timestamp(): int {
	$dt = new DateTime();
	$dt->setTimezone(new DateTimeZone('Asia/Shanghai'));
	// $datas['datetime'] = $dt->format('YmdHis');
	// $datas['date'] = $dt->format('Ymd');
	return $dt->getTimestamp();
}

/**
 * boolean function is_no_empty_str(?string $data)
 */
function is_no_empty_str(?string $data): bool {
	return $data != '' ? true : false;
}

/**
 * void function header_display(string $mime_type)
 */
function header_display(string $mime_type): void {
	$params = ['Cache-Control: no-cache', 'Pragma: no-cache', 'Content-Type: ' . $mime_type];
	foreach($params as $param){
		header($param);
	}
}

/**
 * void function header_download(string $mime_type, string $file_name)
 */
function header_download(string $mime_type, string $file_name): void {
	$params = ['Accept-Ranges:bytes', 'Cache-Control: no-cache', 'Pragma: no-cache', 'Content-Description: File Transfer', 'Content-Type: ' . $mime_type, 'Content-Disposition: attachment; filename=' . $file_name, 'Content-Transfer-Encoding: binary'];
	foreach($params as $param){
		header($param);
	}
}

/**
 * string function get_rand_str(integer $length = 30)
 */
function get_rand_str(int $length = 30): string {
	$queue = '';
	$chars = array_merge(range('0', '9'), range('a', 'z'));
	for($i = 0; $i < $length; $i++){
		$queue .= $chars[mt_rand(0, count($chars) - 1)];
	}
	return strtoupper($queue);
}

/**
 * ?string get_digital_sign(array $datas, string $api_key)
 */
function get_digital_sign(array $datas, string $api_key): string {
	foreach($datas as $key => $data){
		if(!is_string($key) or !is_string($data)) return null;
		elseif('sign' == $key) unset($datas[$key]);
		elseif('' == $data) unset($datas[$key]);
	}
	if(!$datas){
		ksort($datas);
		array_walk($datas, 'merge_key_to_data');
		$datas[] = ('key=' . $api_key);
		return strtoupper(md5(implode('&', $datas)));
	}
	return null;
}






















//