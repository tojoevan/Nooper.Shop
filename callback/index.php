<?php
// declare(strict_types = 1);
namespace NooperShop;

$callback = new Callback();
$message = $callback->get_message();
if(!is_null($message)){
	//list('type'=>$message_type, 'datas'=>$message_datas)=$message;
	switch($message_type){
		case 'user.text':
			$datas = [
				'message_id'=>(int)$message_datas['msg_id'],
				'user_open_id'=>$message_datas['from_user_name'],
				'content'=>$message_datas['content'],
				'create_time'=>$message_datas['create_time']
			];
			$mysql = new Mysql();
			$mysql->memory('user_text_message')->add($datas);
			break;
		case 'event.subscribe':
			$datas=[
				'user_open_id'=>$message_datas['from_user_name'],
				'create_time'=>$message_datas['create_time']
			];
			$mysql=new Mysql();
			$mysql->memory('user_subscribe_record')->add($datas);
			break;
		case 'event.subscribe.qrscene':
			$datas=[
					'user_open_id'=>$message_datas['from_user_name'],
					'create_time'=>$message_datas['create_time'],
					'qr_scene_key'=>$message_datas['event_key']
			];
			$mysql=new Mysql();
			$mysql->memory('user_subscribe_record')->add($datas);
			break;
		case 'event.unsubscribe':
			$datas=[
					'user_open_id'=>$message_datas['from_user_name'],
					'create_time'=>$message_datas['create_time']
			];
			$mysql=new Mysql();
			$mysql->memory('user_unsubscribe_record')->add($datas);
			break;
		default:
			break;
	}
}
$callback->send_empty_message();

