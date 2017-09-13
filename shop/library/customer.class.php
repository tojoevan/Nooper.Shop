<?php
// declare(strict_types = 1);
namespace Nooper;

class Customer extends Mysql {
	
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
	 * public integer function get_message_category_num(void)
	 */
	public function get_message_category_num(): int {
		$ends = $this->field(['num'=>'count(*)'])->table(['customer_message_categories'])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_message_category_page(integer $page_num, integer $page_length = 20)
	 */
	public function get_message_category_page(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['cmc.id', 'cmc.code', 'cmc.name', 'cmc.position', 'cmc.add_time', 'message_num'=>'count(`cm`.`id`)']);
		$this->table(['cmc'=>'customer_message_categories'])->join(['cm'=>'customer_messages', 'cmc.id'=>'cm.category_id']);
		$this->group(['cmc.id'])->order(['cmc.position'=>'desc', 'cmc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_message_num_by_customer_id(integer $customer_id)
	 */
	public function get_message_num_by_customer_id(int $customer_id): int {
		$ends = $this->field(['num'=>'count(*)'])->table(['customer_messages'])->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_message_page_by_customer_id(integer $customer_id, integer $page_num, integer $page_length = 20)
	 */
	public function get_message_page_by_customer_id(int $customer_id, int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['cm.id', 'cmc.id', 'cmc.code', 'cm.title', 'cm.add_time', 'cm.status']);
		$this->table(['cm'=>'customer_messages'])->join(['cmc'=>'customer_message_categories', 'cm.category_id'=>'cmc.id']);
		$this->where(['cm.customer_id'=>$customer_id])->order(['cm.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_unread_message_num_by_customer_id(integer $customer_id)
	 */
	public function get_unread_message_num_by_customer_id(int $customer_id): int {
		$ends = $this->field(['num'=>'count(*)'])->table(['customer_messages'])->where(['customer_id'=>$customer_id, 'status'=>"'unread'"])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_unread_message_page_by_customer_id(integer $customer_id, integer $page_num, integer $page_length = 20)
	 */
	public function get_unread_message_page_by_customer_id(int $customer_id, int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['cm.id', 'cmc.id', 'cmc.code', 'cm.title', 'cm.add_time', 'cm.status']);
		$this->table(['cm'=>'customer_messages'])->join(['cmc'=>'customer_message_categories', 'cm.category_id'=>'cmc.id']);
		$this->where(['cm.customer_id'=>$customer_id, 'cm.status'=>"'unread'"])->order(['cm.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_deliver_address_num_by_customer_id(integer $customer_id)
	 */
	public function get_deliver_address_num_by_customer_id(int $customer_id): int {
		$ends = $this->field(['num'=>'count(*)'])->table(['customer_deliver_addresses'])->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_deliver_address_page_by_customer_id(integer $customer_id, integer $page_num, integer $page_length = 20)
	 */
	public function get_deliver_address_page_by_customer_id(int $customer_id, int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['id', 'receiver', 'phone', 'primary_address', 'is_default', 'add_time']);
		$this->table(['customer_deliver_addresses'])->where(['customer_id'=>$customer_id])->order(['is_default'=>'desc', 'id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_review_num_by_customer_id(integer $customer_id)
	 */
	public function get_review_num_by_customer_id(int $customer_id): int {
		$ends = $this->field(['num'=>'count(*)'])->table(['customer_reviews'])->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_review_page_by_customer_id(integer $customer_id, integer $page_num, integer $page_length = 20)
	 */
	public function get_review_page_by_customer_id(int $customer_id, int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['cr.id', 'o.id', 'o.unique_id', 'p.id', 'p.unique_id', 'p.code', 'p.name', 'cr.grade', 'cr.add_time']);
		$this->table(['cr'=>'customer_reviews'])->join(['o'=>'orders', 'cr.order_id'=>'o.id'])->join(['p'=>'products', 'cr.product_id'=>'p.id']);
		$this - where(['customer_id'=>$customer_id]);
		$this->order(['cr.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
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











