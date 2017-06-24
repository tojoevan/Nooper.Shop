<?php
// declare(strict_types = 1);
namespace NooperShop;

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
 * boolean function is_class_named_regular(string $data)
 */
function is_class_named_regular(string $data):bool {
	$pattern = '/^[a-z]+(_[a-z]+)*\.class\.php$/';
	return preg_match($pattern, $data)?true: false;
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
//