<?php

// declare(strict_types = 1);
namespace NooperShop;

class Unique {
	
	/**
	 * public string customer(void)
	 */
	public function customer(): string {
		return $this->get_unique_id([3, 4, 3]); // length=12, @@
	}
	
	/**
	 * public string product(void)
	 */
	public function product(): string {
		return $this->get_unique_id([4, 5, 3]); // length=14, @@
	}
	
	/**
	 * public string gift_card(void)
	 */
	public function gift_card(): string {
		return $this->get_unique_id([4, 6, 6, 3]); // length=22, @@
	}
	
	/**
	 * public string coupon(void)
	 */
	public function coupon(): string {
		return $this->get_unique_id([4, 7, 5]); // length=18, @@
	}
	
	/**
	 * public string message(void)
	 */
	public function message(): string {
		return $this->get_unique_id([5, 6, 3]); // length=16, @@
	}
	
	/**
	 * public string password(void)
	 */
	public function password(): string {
		return get_rand_str(12); // length=12, @@
	}
	
	/**
	 * protected string get_unique_id(array $lengths)
	 * @$lengths = [integer $length,...]
	 */
	protected function get_unique_id(array $lengths): string {
		foreach($lengths as $length){
			$strs[] = $this->get_rand_str($length);
		}
		return implode('-', $strs);
	}
	
	/**
	 * protected string get_rand_str(integer $length)
	 */
	protected function get_rand_str(int $length): string {
		$queue = '';
		$chars = array_merge(range('0', '9'), range('a', 'z'));
		for($i = 0; $i < $length; $i++){
			$queue .= $chars[mt_rand(0, count($chars) - 1)];
		}
		return strtoupper($queue);
	}
}