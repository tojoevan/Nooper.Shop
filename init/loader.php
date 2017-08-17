<?php
// declare(strict_types = 1);
namespace NooperShop;

/**
 * void function load_funcs(void)
 */
function load_funcs(): void {
	$full_name = '../core/function/nooper.func.php';
	if(is_file($full_name)) @include_once $full_name;
}

/**
 * void function load_libs(void)
 */
function load_libs(): void {
	$path = '../core/library';
	if(is_dir($path)){
		foreach(scandir($path) as $file_name){
			$full_name = implode('/', array($path, $file_name));
			if(is_class_named_regular($file_name) && is_file($full_name)) include_once $full_name;
		}
	}
}

/**
 * void function load_configs(void)
 */
function load_configs(): void {
	$full_name = '../core/config/nooper.conf.php';
	if(is_file($full_name)){
		$configs = include_once $full_name;
		if(is_array($configs)){
			foreach($configs as $key => $config){
				set_config($key, $config);
			}
		}
	}
}

/**
 * void function fire(void)
 */
function fire(): void {
	load_funcs();
	load_libs();
	load_configs();
}

/**
 * init
 */
fire();