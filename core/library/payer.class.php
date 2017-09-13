<?php
// declare(strict_types = 1);
namespace Nooper;

use DateTime;
use DateTimeZone;
use DateInterval;

class Payer {
	/**
	 * Constants
	 */
	const operate_create = 1;
	const operate_query = 2;
	const operate_close = 3;
	const operate_refund = 4;
	const operate_refund_query = 5;
	const operate_download = 6;
	const operate_qrcode_create = 7;
	const operate_qrcode_change = 8;
	const operate_question = 9;
	const operate_answer = 10;
	const operate_notify = 11;
	const operate_reply = 12;
	
	/**
	 * Properties
	 */
	protected $app_id;
	protected $mch_id;
	protected $api_key;
	protected $hash = 'MD5';
	protected $operate_urls = [self::operate_create=>'https://api.mch.weixin.qq.com/pay/unifiedorder', self::operate_query=>'https://api.mch.weixin.qq.com/pay/orderquery', self::operate_close=>'https://api.mch.weixin.qq.com/pay/closeorder ', self::operate_refund=>'https://api.mch.weixin.qq.com/secapi/pay/refund', self::operate_refund_query=>'https://api.mch.weixin.qq.com/pay/refundquery', self::operate_download=>'https://api.mch.weixin.qq.com/pay/downloadbill', self::operate_qrcode_create=>'weixin://wxpay/bizpayurl', self::operate_qrcode_change=>'https://api.mch.weixin.qq.com/tools/shorturl', self::operate_question=>null, self::operate_answer=>null, self::operate_notify=>null, self::operate_reply=>null];
	protected $create_params = [['trade_type', 'device_info', 'out_trade_no', 'product_id', 'openid', 'body', 'detail', 'total_fee', 'fee_type', 'limit_pay', 'goods_tag', 'spbill_create_ip', 'time_start', 'time_expire', 'attach'], ['return_code', 'result_code', 'trade_type', 'prepay_id', 'code_url']];
	protected $query_params = [['transaction_id', 'out_trade_no'], ['return_code', 'result_code', 'trade_type', 'trade_state', 'transaction_id', 'out_trade_no', 'openid', 'total_fee', 'settlement_total_fee', 'cash_fee', 'coupon_fee', 'time_end', 'attach']];
	protected $close_params = [['out_trade_no'], ['return_code', 'result_code']];
	protected $refund_params = [['device_info', 'transaction_id', 'out_trade_no', 'out_refund_no', 'total_fee', 'refund_fee', 'refund_fee_type', 'refund_account', 'op_user_id'], ['return_code', 'result_code', 'transaction_id', 'out_trade_no', 'refund_id', 'out_refund_no', 'refund_fee', 'settlement_refund_fee', 'cash_refund_fee', 'coupon_refund_fee']];
	protected $refund_query_params = [['device_info', 'transaction_id', 'out_trade_no', 'refund_id', 'out_refund_no'], ['return_code', 'result_code', 'transaction_id', 'out_trade_no', 'refund_count']];
	protected $download_params = [['device_info', 'bill_date', 'bill_type', 'tar_type'], []];
	protected $qrcode_create_params = [['product_id', 'time_stamp'], []];
	protected $qrcode_change_params = [['long_url'], ['return_code', 'result_code', 'short_url']];
	protected $question_params = [[], ['openid', 'product_id']];
	protected $answer_params = [['return_code', 'return_msg', 'prepay_id', 'result_code', 'err_code_des'], []];
	protected $notify_params = [[], ['return_code', 'result_code', 'trade_type', 'transaction_id', 'out_trade_no', 'openid', 'total_fee', 'settlement_total_fee', 'cash_fee', 'coupon_fee', 'time_end', 'attach']];
	protected $reply_params = [['return_code', 'return_msg'], []];
	protected $params = [];
	protected $datas = [];
	
	/**
	 * public void function __construct(string $app_id, string $mch_id, string api_key)
	 */
	public function __construct(string $app_id, string $mch_id, string $api_key) {
		$keys = array_merge($this->create_params[0], $this->query_params[0], $this->close_params[0]);
		$keys = array_merge($keys, $this->refund_params[0], $this->refund_query_params[0], $this->download_params[0]);
		$keys = array_merge($keys, $this->qrcode_create_params[0], $this->qrcode_change_params[0]);
		$keys = array_merge($keys, $this->question_params[0], $this->answer_params[0]);
		$keys = array_merge($keys, $this->notify_params[0], $this->reply_params[0]);
		$this->params = array_unique($keys);
		// $this->urls[self::operate_notify] = $notify_url;
		$this->api_key = $api_key;
		$this->mch_id = $mch_id;
		$this->app_id = $app_id;
	}
	
	/**
	 * public void function __destruct(void)
	 */
	function __destruct() {
		// echo '- end -';
	}
	
	/**
	 * public boolean function data(string $key, string $param)
	 */
	public function data(string $key, string $param): bool {
		if(in_array($key, $this->params, true)){
			$this->datas[$key] = $param;
			return true;
		}
		return false;
	}
	
	/**
	 * public integer function datas(array $params)
	 */
	public function datas(array $params): int {
		$counter = 0;
		foreach($params as $key => $param){
			$end = $this->data($key, $param);
			if($end) $counter++;
		}
		return $counter;
	}
	
	/**
	 * public void function clear(void)
	 */
	public function clear(): void {
		$this->datas = [];
	}
	
	/**
	 * public ?array function create(boolean $clip = true)
	 */
	public function create(bool $clip = true): array {
		$ends = $this->parse($this->send(self::operate_create));
		return is_array($ends) && $clip ? $this->clip(self::operate_create, $ends) : $ends;
	}
	
	/**
	 * public ?array function query(boolean $clip = true)
	 */
	public function query(bool $clip = true): array {
		$ends = $this->parse($this->send(self::operate_query));
		return is_array($ends) && $clip ? $this->clip(self::operate_query, $ends) : $ends;
	}
	
	/**
	 * pulblic ?array function close(boolean $clip = true)
	 */
	public function close(bool $clip = true): array {
		$ends = $this->parse($this->send(self::operate_close));
		return is_array($ends) && $clip ? $this->clip(self::operate_close, $ends) : $ends;
	}
	
	/**
	 * public array function refund(boolean $clip = true)
	 */
	public function refund(bool $clip = true): array {
		$ends = $this->parse($this->send(self::operate_refund));
		return $clip ? $this->clip(self::operate_refund, $ends) : $ends;
	}
	
	/**
	 * public array function queryr(boolean $clip = true)
	 */
	public function queryr(bool $clip = true): array {
		$ends = $this->parse($this->send(self::operate_refund_query));
		return $clip ? $this->clip(self::operate_refund_query, $ends) : $ends;
	}
	
	
	
	
	
	
	/**
	 * public array function download(boolean $pack = true)
	 */
	public function download(bool $pack = true): array {
		$this->data('tar_type', $pack ? 'GZIP' : null);
		$end = $this->send(self::operate_download);
		$mime_type = $pack ? 'application/zip' : 'text/plain';
		$file_basic_name = $this->datas['bill_date'] ?? 'bill';
		$file_name = $file_basic_name . '.' . $pack ? 'gzip' : 'txt';
		$this->header($mime_type, true, $file_name);
		echo $end;
	}
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * public array function qrcode(string $prodouct_id)
	 */
	public function qrcode(string $product_id): array {
		$this->data('product_id', $product_id);
		$this->data('time_stamp', $this->now()['timestamp']);
		$datas = $this->prepare(self::operate_qrcode_create);
		foreach($datas as $key => &$data){
			$data = ($key . '=' . $data);
		}
		$ends['long_url'] = $this->urls[self::operate_qrcode_create] . '?' . implode('&', $datas);
		$ends['short_url'] = $this->qrcodec($ends['long_url']);
		return $ends;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * public ?array function qrcodec(string $url, boolean $clip = true)
	 */
	public function qrcodec(string $url, bool $clip = true): array {
		$this->data('long_url', $url);
		$ends = $this->parse($this->send(self::operate_qrcode_change));
		return $clip ? $this->clip(self::operate_qrcode_change, $ends) : $ends;
	}
	
	/**
	 * public array function question(boolean $clip = true)
	 */
	public function question(bool $clip = true): array {
		$xml = file_get_contents('php://input');
		$ends = $this->parse($xml);
		return $clip ? $this->clip(self::operate_question, $ends) : $ends;
	}
	
	/**
	 * public void function answer(void)
	 */
	public function answer(): void {
		$datas = $this->prepare(self::operate_answer, false);
		$helper = new Translator();
		$xml = $helper->createXML($datas);
		$this->header('text/xml');
		echo $xml;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * public array function notify(boolean $clip = true)
	 */
	public function notify(bool $clip = true): array {
		$xml = file_get_contents('php://input');
		$ends = $this->parse($xml);
		return $clip ? $this->clip(self::operate_notify, $ends) : $ends;
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * public void function reply(string $code, ?string $message = null)
	 */
	public function reply(string $code, string $message = null): void {
		$this->data('return_code', $code);
		if(!is_null($message) && $message != '') $this->data('return_msg', $message);
		$datas = $this->prepare(self::operate_reply, false);
		$helper = new Translator();
		$xml = $helper->createXML($datas);
		$this->header('text/xml');
		echo $xml;
	}
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * protected array function prepare(integer $operate, boolean $primary = true)
	 */
	protected function prepare(int $operate, bool $primary = true): array {
		$datas = [];
		$params = $this->map($operate);
		foreach($params as $param){
			if(isset($this->datas[$param])) $datas[$param] = $this->datas[$param];
		}
		if($primary){
			$datas = array_merge($datas, ['appid'=>$this->app_id, 'mch_id'=>$this->mch_id, 'nonce_str'=>get_rand_str()]);
			$datas['sign'] = get_digital_sign($datas, $this->api_key);
		}
		return $datas;
	}
	
	/**
	 * protected string function send(integer $operate)
	 */
	protected function send(int $operate, array $datas = null): string {
		$url = $this->operate_urls[$operate];
		$datas = $this->prepare($operate);
		$mmc = new Mimicry();
		$helper = new Translator();
		try{
			$end = $mmc->post($url, $helper->create_xml($datas));
		}catch(\Exception $err){
			return '';
		}
		return $end;
	}
	
	/**
	 * protected array function parse(string $xml)
	 */
	protected function parse(string $xml): array {
		$helper = new Translator();
		$datas = $helper->parse_xml($xml);
		if(!is_array($datas)) return null;
		elseif(!isset($datas['return_code']) or strtolower($datas['return_code']) == 'fail') return null;
		elseif(!isset($datas['result_code']) or strtolower($datas['result_code']) == 'fail') return null;
		elseif(!isset($datas['sign']) or $datas['sign'] !== get_digital_sign($datas, $this->api_key)) return null;
		return $datas;
	}
	
	/**
	 * protected void function error(integer $code, ?string $description = null)
	 */
	protected function error(int $code, string $description = null): void {
		switch($code){
			case 10001:
				$message = 'Nooper_Pay_Operate_Error';
				break;
			case 10002:
				$message = 'Nooper_Pay_Empty_Data_Error';
				break;
			case 20001:
				$message = 'Nooper_Pay_Curl_Error';
				break;
			case 30001:
				$message = 'Nooper_Pay_Empty_XML_Error';
				break;
			case 30002:
				$message = 'Nooper_Pay_XML_Format_Error';
				break;
			case 40001:
				$message = 'Nooper_Pay_Comm_Error';
				break;
			case 50001:
				$message = 'Nooper_Pay_Trade_Failure';
				break;
			case 60001:
				$message = 'Nooper_Pay_Sign_Failure';
			default:
				$messgae = 'Nooper_Pay_System_Error';
				break;
		}
		if(!is_null($description) and trim($description) != '') $message .= '[' . $description . ']';
		throw new \Exception($message, $code);
	}
	
	/**
	 * protected array function map(int $operate, boolean $send = true)
	 */
	protected function map(int $operate, bool $send = true): array {
		switch($operate){
			case self::operate_create:
				return $this->create_params[$send ? 0 : 1];
				break;
			case self::operate_query:
				return $this->query_params[$send ? 0 : 1];
				break;
			case self::operate_close:
				return $this->close_params[$send ? 0 : 1];
				break;
			case self::operate_refund:
				return $this->refund_params[$send ? 0 : 1];
				break;
			case self::operate_refund_query:
				return $this->refund_query_params[$send ? 0 : 1];
				break;
			case self::operate_download:
				return $this->download_params[$send ? 0 : 1];
				break;
			case self::operate_qrcode_create:
				return $this->qrcode_create_params[$send ? 0 : 1];
				break;
			case self::operate_qrcode_change:
				return $this->qrcode_change_params[$send ? 0 : 1];
				break;
			case self::operate_question:
				return $this->question_params[$send ? 0 : 1];
				break;
			case self::operate_answer:
				return $this->answer_params[$send ? 0 : 1];
				break;
			case self::operate_notify:
				return $this->notify_params[$send ? 0 : 1];
				break;
			case self::operate_reply:
				return $this->reply_params[$send ? 0 : 1];
				break;
			default:
				return [];
				break;
		}
	}
	
	/**
	 * protected array function clip(integer $operate, array $datas)
	 */
	protected function clip(int $operate, array $datas): array {
		$keys = $this->map($operate, false);
		if(is_null($keys)) return $datas;
		foreach($keys as $key){
			if(isset($datas[$key])) $ends[$key] = $datas[$key];
		}
		return $ends ?? $datas;
	}
	//
}









