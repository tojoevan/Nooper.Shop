<?php
// declare(strict_types = 1);
namespace Nooper;

class Order extends Mysql {
	
	/**
	 * public array function get_message_category_page(integer $page_num, integer $page_length = 20)
	 * ----------
	 * cmc.id
	 * cmc.code
	 * cmc.name
	 * cmc.position
	 * cmc.add_time
	 * url: message_num
	 * ----------
	 * op: item
	 * op: edit
	 * op: ?delete
	 */
	public function get_message_category_page(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$field_datas = ['cmc.id', 'cmc.code', 'cmc.name', 'cmc.position', 'cmc.add_time', 'message_num'=>'count(`cm`.`id`)'];
		$memory_datas = ['cmc'=>'customer_message_categories'];
		$plus_datas = ['cm'=>'customer_messages', 'cmc.id'=>'cm.category_id'];
		$group_datas = ['cmc.id'];
		$order_datas = ['cmc.position'=>'desc', 'cmc.id'=>'desc'];
		return $this->field($field_datas)->table($memory_datas)->join($plus_datas, 'left')->group($group_datas)->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	
	/**
	 * public integer function get_num(void)
	 */
	public function get_num(): int {
		$ends = $this->field(['num'=>'count(*)'])->table(['customers'])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function page(integer $page_num, integer $page_length = 20)
	 * ----------
	 * id
	 * unique_id
	 * open_id
	 * nickname
	 * url: point
	 * url: balance
	 * add_time
	 * status
	 * ----------
	 * url: order_num
	 * url: coupon_num
	 * url: gift_card_num
	 * url: review_num
	 * url: deliver_address_num
	 * url: message_num
	 * ----------
	 * op: detail
	 * op: send_message
	 * op: lock or unlock
	 */
	public function page(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$field_datas = ['id', 'unique_id', 'open_id', 'nickname', 'point', 'balance', 'add_time', 'status'];
		$ends = $this->field($field_datas)->table(['customers'])->order(['id'=>'desc'])->limit($page_length, $offset_num)->select();
		return $this->get_plus_page($ends);
	}
	
	/**
	 * public integer function get_locked_num(void)
	 */
	public function get_locked_num(): int {
		$ends = $this->field(['num'=>'count(*)'])->table(['customers'])->where(['status'=>"'locked'"])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_locked_page(integer $page_num, integer $page_length = 20)
	 * ----------
	 * id
	 * unique_id
	 * open_id
	 * nickname
	 * url: point
	 * url: balance
	 * add_time
	 * status
	 * ----------
	 * url: order_num
	 * url: coupon_num
	 * url: gift_card_num
	 * url: review_num
	 * url: deliver_address_num
	 * url: message_num
	 * ----------
	 * op: detail
	 * op: send_message
	 * op: unlock
	 */
	public function get_locked_page(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$field_datas = ['id', 'unique_id', 'open_id', 'nickname', 'point', 'balance', 'add_time', 'status'];
		$ends = $this->field($field_datas)->table(['customers'])->where(['status'=>"'locked'"])->order(['id'=>'desc'])->limit($page_length, $offset_num)->select();
		return $this->get_plus_page($ends);
	}
	
	/**
	 * protected array function get_plus_page(array $datas)
	 */
	protected function get_plus_page(array $datas): array {
		if($datas){
			$order_nums = $this->get_order_nums();
			$coupon_nums = $this->get_coupon_nums();
			$gift_card_nums = $this->get_gift_card_nums();
			$review_nums = $this->get_review_nums();
			$address_nums = $this->get_deliver_address_nums();
			$message_nums = $this->get_message_nums();
			foreach($datas as &$data){
				$id = $data['id'];
				$data['order_num'] = $order_nums[$id] ?? 0;
				$data['coupon_num'] = $coupon_nums[$id] ?? 0;
				$data['gift_card_num'] = $gift_card_nums[$id] ?? 0;
				$data['view_num'] = $review_nums[$id] ?? 0;
				$data['deliver_address_num'] = $address_nums[$id] ?? 0;
				$data['message_num'] = $message_nums[$id] ?? 0;
			}
		}
		return $datas;
	}
	
	/**
	 * protected array function get_order_nums(void)
	 */
	protected function get_order_nums(): array {
		$datas = $this->field(['customer_id', 'num'=>'count(*)'])->table(['orders'])->group(['customer_id'])->order(['customer_id'=>'desc'])->select();
		foreach($datas as $data){
			list('customer_id'=>$id, 'num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	/**
	 * protected array function get_coupon_nums(void)
	 */
	protected function get_coupon_nums(): array {
		$datas = $this->field(['customer_id', 'num'=>'count(*)'])->table(['coupon_get_records'])->group(['customer_id'])->order(['customer_id'=>'desc'])->select();
		foreach($datas as $data){
			list('customer_id'=>$id, 'num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	/**
	 * protected array function get_gift_card_nums(void)
	 */
	protected function get_gift_card_nums(): array {
		$datas = $this->field(['customer_id', 'num'=>'count(*)'])->table(['gift_card_sale_records'])->group(['customer_id'])->order(['customer_id'=>'desc'])->select();
		foreach($datas as $data){
			list('customer_id'=>$id, 'num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	/**
	 * protected array function get_review_nums(void)
	 */
	protected function get_review_nums(): array {
		$datas = $this->field(['customer_id', 'num'=>'count(*)'])->table(['customer_reviews'])->group(['customer_id'])->order(['customer_id'=>'desc'])->select();
		foreach($datas as $data){
			list('customer_id'=>$id, 'num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	/**
	 * protected array function get_deliver_address_nums(void)
	 */
	protected function get_deliver_address_nums(): array {
		$datas = $this->field(['customer_id', 'num'=>'count(*)'])->table(['customer_deliver_addresses'])->group(['customer_id'])->order(['customer_id'=>'desc'])->select();
		foreach($datas as $data){
			list('customer_id'=>$id, 'num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	/**
	 * protected array function get_message_nums(void)
	 */
	protected function get_message_nums(): array {
		$datas = $this->field(['customer_id', 'num'=>'count(*)'])->table(['customer_messages'])->group(['customer_id'])->order(['customer_id'=>'desc'])->select();
		foreach($datas as $data){
			list('customer_id'=>$id, 'num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	//
}











