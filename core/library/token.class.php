<?php
// declare(strict_types = 1);
namespace Nooper;

class Token {
	
	/**
	 * Properties
	 */
	protected $app_id;
	protected $app_secret;
	protected $operate_url = 'https://api.weixin.qq.com/cgi-bin/token';
	
	/**
	 * public void function __construct(?string $app_id = null, ?string $app_secret = null)
	 */
	public function __construct(string $app_id = null, string $app_secret = null) {
		$this->app_id = $app_id ?? get_config('app_id');
		$this->app_secret = $app_secret ?? get_config('app_secret');
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
		$qry_params = ['grant_type'=>'client_credential', 'appid'=>$this->app_id, 'secret'=>$this->app_secret];
		$mmc = new Mimicry();
		$helper = new Translator();
		try{
			$json_datas = $helper->parse_json($mmc->get($this->operate_url, $qry_params));
		}catch(\Exception $err){
			return false;
		}
		$token = $json_datas['access_token'] ?? null;
		$expire_seconds = $json_datas['expires_in'] ?? null;
		if($token && $expire_seconds){
			$mysql = new Mysql('access_tokens');
			$datas = $mysql->field(['row_num'=>'count(*)'])->select();
			$write_datas = ['string'=>$token, 'expire_seconds'=>$expire_seconds, 'create_time'=>get_now_timestamp()];
			$end = $datas && $datas[0]['row_num'] > 0 ? $mysql->modify($write_datas) : $mysql->add($write_datas);
			return $end > 0 ? true : false;
		}
		return false;
	}
	
	/**
	 * public string function read(boolean $deep = true)
	 */
	public function read(bool $deep = true): string {
		$mysql = new Mysql('access_tokens');
		$datas = $mysql->field(['*'])->limit(1)->select();
		if($datas){
			list('string'=>$string, 'expire_seconds'=>$expire_seconds, 'create_time'=>$create_time)=$datas[0];
			$now = get_now_timestamp();
			if(($now - $create_time) > ($expire_seconds - 600)){
				if(!$deep) return null;
				return $this->write() ? $this->read(false) : null;
			}else
				return $string;
		}else{
			if(!$deep) return null;
			return $this->write() ? $this->read(false) : null;
		}
	}
	//
}









