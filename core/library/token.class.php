<?php
// declare(strict_types = 1);
namespace Nooper;

use Exception;

class Token {
	
	/**
	 * Properties
	 */
	protected $curl;
	protected $cert;
	protected $certPwd;
	protected $key;
	
	/**
	 * public void function __construct(void)
	 */
	public function __construct() {
	}
	
	/**
	 * public void function __destruct(void)
	 */
	function __destruct() {
	}
	
	/**
	 */
	public function write(): void {
	}
	
	/**
	 */
	public function read(): string {
	}
	
	/**
	 * public string function cert(string $full_name)
	 */
	public function cert(string $full_name): string {
		$this->cert = $full_name;
		return $full_name;
	}
	//
}










