<?php
// declare(strict_types = 1);
namespace NooperShop;

class Unique {
	
	/**
	 * public string gift_card(void)
	 */
	public function gift_card(): string {
		foreach([4, 6, 6, 3] as $length){
			$sub_strs[] = $this->get_rand_str($length);
		}
		return implode('-', $sub_strs);
	}
	
	/**
	 * protected string unique_id(array $lengths)
	 */
	
	/**
	 * protected string get_rand_str(integer $length)
	 */
	function get_rand_str(int $length): string {
		$queue = '';
		$chars = array_merge(range('0', '9'), range('a', 'z'));
		for($i = 0; $i < $length; $i++){
			$queue .= $chars[mt_rand(0, count($chars) - 1)];
		}
		return strtoupper($queue);
	}
}