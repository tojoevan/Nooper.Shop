<?php
// declare(strict_types = 1);
namespace NooperShop;

class ProductType extends Mysql {
	
	/**
	 * Properties
	 */
	
	/**
	 * public void function __construct(void)
	 */
	public function __construct(array $connect_params = null) {
		parent::__construct('product_type', $connect_params);
	}
	
	/**
	 * public void function __destruct(void)
	 */
	function __destruct() {
		parent::__destruct();
	}
	
	/**
	 * public array function get_list(void)
	 */
	public function get_list(): array {
	}

/**
 * public void switch()
 */
	public function switch():void{
		//
	}
	//
}

