<?php
// declare(strict_types = 1);
namespace NooperShop;

class Callback {
	
	/**
	 * Properties
	 */
	protected $access_token;
	
	/**
	 * public void function __construct(?string $token = null)
	 */
	public function __construct(string $token = null) {
		if(is_null($token)){
			$token = new Token();
			$this->access_token = $token->read();
		}else
			$this->access_token = $token;
	}
	
	/**
	 * public void function __destruct(void)
	 */
	function __destruct() {
		// echo '- end -';
	}
	
	/**
	 * public ?array function get_message(void)
	 */
	public function get_message(): array {
		$xml = file_get_contents('php://input');
		$helper = new Translator();
		$datas = $helper->parseXML($xml);
		return is_array($datas) ? $this->change(datas) : null;
	}
	
	/**
	 * public ?string function get_message_type(array $datas)
	 */
	public function get_message_type(array $datas): string {
		if($this->is_user_text_message($datas)) return 'user.text';
		elseif($this->is_event_subscribe_messgae($datas)) return 'event.subscribe';
		elseif($this->is_event_qrscene_subscribe_message($datas)) return 'event.subscribe.qrscene';
		elseif($this->is_event_unsubscribe_message($datas)) return 'event.unsubscribe';
		elseif($this->is_event_click_message($datas)) return 'event.click';
		elseif($this->is_event_view_message($datas)) return 'event.view';
		return null;
	}
	
	/**
	 * protected boolean function is_user_text_message($datas)
	 */
	protected function is_user_text_message($datas): bool {
		$message_keys = ['to_user_name', 'from_user_name', 'create_time', 'msg_type', 'content', 'msg_id'];
		foreach($datas as $key => $data){
			if(!in_array($key, $message_keys, true)) return false;
			elseif(!is_string($data)) return false;
		}
		return 'text' == $datas['msg_type'] ? true : false;
	}
	
	/**
	 * protected boolean function is_event_subscribe_message($datas)
	 */
	protected function is_event_subscribe_message($datas): bool {
		$message_keys = ['to_user_name', 'from_user_name', 'create_time', 'msg_type', 'event'];
		foreach($datas as $key => $data){
			if(!in_array($key, $message_keys, true)) return false;
			elseif(!is_string($data)) return false;
		}
		return 'event' == $datas['msg_type'] && 'subscribe' == $datas['event'] ? true : false;
	}
	
	/**
	 * protected boolean function is_event_qrscene_subscribe_message($datas)
	 */
	protected function is_event_qrscene_subscribe_message($datas): bool {
		$message_keys = ['to_user_name', 'from_user_name', 'create_time', 'msg_type', 'event', 'event_key', 'ticket'];
		foreach($datas as $key => $data){
			if(!in_array($key, $message_keys, true)) return false;
			elseif(!is_string($data)) return false;
		}
		return 'event' == $datas['msg_type'] && 'subscribe' == $datas['event'] ? true : false;
	}
	
	/**
	 * protected boolean function is_event_unsubscribe_message($datas)
	 */
	protected function is_event_unsubscribe_message($datas): bool {
		$message_keys = ['to_user_name', 'from_user_name', 'create_time', 'msg_type', 'event'];
		foreach($datas as $key => $data){
			if(!in_array($key, $message_keys, true)) return false;
			elseif(!is_string($data)) return false;
		}
		return 'event' == $datas['msg_type'] && 'unsubscribe' == $datas['event'] ? true : false;
	}
	
	/**
	 * protected boolean function is_event_click_message($datas)
	 */
	protected function is_event_click_message($datas): bool {
		$message_keys = ['to_user_name', 'from_user_name', 'create_time', 'msg_type', 'event', 'event_key'];
		foreach($datas as $key => $data){
			if(!in_array($key, $message_keys, true)) return false;
			elseif(!is_string($data)) return false;
		}
		return 'event' == $datas['msg_type'] && 'click' == $datas['event'] ? true : false;
	}
	
	/**
	 * protected boolean function is_event_view_message($datas)
	 */
	protected function is_event_view_message($datas): bool {
		$message_keys = ['to_user_name', 'from_user_name', 'create_time', 'msg_type', 'event', 'event_key'];
		foreach($datas as $key => $data){
			if(!in_array($key, $message_keys, true)) return false;
			elseif(!is_string($data)) return false;
		}
		return 'event' == $datas['msg_type'] && 'view' == $datas['event'] ? true : false;
	}
	
	/**
	 * protected array function change(array $datas)
	 */
	protected function change(array $datas): array {
		$keys = array_keys($datas);
		$values = array_values($datas);
		foreach($keys as &$key){
			if(is_string($key)) $key = camel_to_underline_named($key);
		}
		return array_combine($keys, $values);
	}
	//
}

