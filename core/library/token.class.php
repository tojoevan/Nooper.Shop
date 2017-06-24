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
	 * boolean public function write(void)
	 */
	public function write(): boolean {
		$datas = ['grant_type'=>'client_credential', 'appid'=>$this->app_id, 'secret'=>$this->app_secret];
		$mm = new Mimicry();
		$json_str = $mm->get($this->url, $datas);
		$helper = new Translator();
		$ends = $helper->parseJSON($json_str);
		if(isset($ends['access_token'])){
			$mysql = new Mysql();
			$sql = "select count(*) as `row_num` from `access_token`";
			$datas = $mysql->query($sql);
			$sql = $datas && $datas[0]['row_num'] > 0 ? "update `access_token` set `string`='".$ends['access_token']."'" : "insert into `access_token`(`string`) values('".$ends['access_token']."')";
			$end = $mysql->cmd($sql);
			return $end > 0 ? true : false;
		}
		return false;
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










