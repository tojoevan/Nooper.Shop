<?php
// declare(strict_types = 1);
namespace NooperShop;

class Token {
	
	/**
	 * Properties
	 */
	protected $app_id;
	protected $app_secret;
	protected $url = 'https://api.weixin.qq.com/cgi-bin/token';
	
	/**
	 * public void function __construct(void)
	 */
	public function __construct() {
		$this->app_id = get_config('app_id');
		$this->app_secret = get_config('app_secret');
	}
	
	/**
	 * public void function __destruct(void)
	 */
	function __destruct() {
		// echo '-end-';
	}
	
	/**
	 * public boolean function write(void)
	 */
	public function write(): bool {
		$datas = ['grant_type'=>'client_credential', 'appid'=>$this->app_id, 'secret'=>$this->app_secret];
		$mmc = new Mimicry();
		$helper = new Translator();
		$json_datas = $helper->parseJSON($mmc->get($this->url, $datas));
		$token=$json_datas['access_token'] ?? null;
		if($token){
			$mysql = new Mysql();
			$datas = $mysql->memory('access_token')->field('count(*) as `row_num`')->select();
			$write_datas = ['string'=>$token];
			$end = $datas && $datas[0]['row_num'] > 0 ? $mysql->modify($write_datas) : $mysql->add($write_datas);
			return $end > 0 ? true : false;
		}
		return false;
	}
	
	/**
	 * public string function read(boolean $deep = true)
	 */
	public function read(bool $deep = true): string {
		$mysql = new Mysql();
		$datas = $mysql->memory('access_token')->limit(1)->field('`string`')->select();
		if($datas) return $datas[0]['string'];
		else{
			if(!$deep) return null;
			return $this->write() ? $this->read(false) : null;
		}
	}
	//
}










