<?php
// declare(strict_types = 1);

/**
 * $GLOBALS[_nooper_configs]
 */

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
 * boolean function is_database_named_regular(string $data, boolean $wild = false)
 */
function is_database_named_regular(string $data, bool $wild = false): bool {
	return is_database_primary_named_regular($data, $wild) or is_database_plus_named_regular($data, $wild);
}

/**
 * boolean function is_database_primary_named_regular(string $data, boolean $wild = false)
 */
function is_database_primary_named_regular(string $data, bool $wild = false): bool {
	if(is_underline_named_regular($data)) return true;
	elseif($wild && '*' == $data) return true;
	return false;
}

/**
 * boolean function is_database_plus_named_regular(string $data, boolean $wild = false)
 */
function is_database_plus_named_regular(string $data, bool $wild = false): bool {
	$pieces = explode('.', $data);
	if(count($pieces) == 2){
		if(is_underline_named_regular($pieces[0]) && is_underline_named_regular($pieces[1])) return true;
		elseif($wild && is_underline_named_regular($pieces[0]) && '*' == $pieces[1]) return true;
	}
	return false;
}

/**
 * boolean function is_class_named_regular(string $data)
 */
function is_class_named_regular(string $data): bool {
	$pattern = '/^[a-z]+(_[a-z]+)*\.class\.php$/';
	return preg_match($pattern, $data) ? true : false;
}

/**
 * boolean function is_database_connect_params(array $datas)
 * @$datas = array(string 'type'|'host'|'port'|'dbname'|'charset'|'username'|'password' => string $value)
 */
function is_database_connect_params(array $datas): bool {
	$keys = array('type', 'host', 'port', 'dbname', 'charset', 'username', 'password');
	if(count($datas) != count($keys)) return false;
	foreach($datas as $key => $value){
		if(!in_array($key, $keys, true)) return false;
		elseif(!is_string($value)) return false;
	}
	return true;
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
 * boolean function is_no_empty_str(?string $data)
 */
function is_no_empty_str(?string $data): bool {
	return $data != '' ? true : false;
}
//