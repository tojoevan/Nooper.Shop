<?php
// declare(strict_types = 1);
namespace NooperShop;

use Nooper\Mysql;

class Customer extends Mysql {
	
	/**
	 * Const
	 */
	const page_record_num = 20;
	
	/**
	 * public integer function num(void)
	 */
	public function num(): int {
		$ends = $this->field(['customer_num'=>'count(*)'])->table(['customers'])->select();
		return $ends[0]['customer_num'] ?? -1;
	}
	
	/**
	 * public array function page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['c.id', 'c.unique_id', 'c.open_id', 'c.nickname', 'order_num'=>'-1', 'c.balance', 'c.point', 'c.add_time', 'c.status']);
		$this->table(['c'=>'customers'])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		$ends = $this->select();
		$order_nums = $this->get_order_nums();
		foreach($ends as &$data){
			$id = $data['id'];
			$data['order_num'] = $order_nums[$id] ?? 0;
		}
		return $ends;
	}
	
	/**
	 * public integer function get_normal_num(void)
	 */
	public function get_normal_num(): int {
		$ends = $this->field(['customer_num'=>'count(*)'])->table(['customers'])->where(['status'=>"'normal'"])->select();
		return $ends[0]['customer_num'] ?? -1;
	}
	
	/**
	 * public array function get_normal_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_normal_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['c.id', 'c.unique_id', 'c.open_id', 'c.nickname', 'order_num'=>'-1', 'c.balance', 'c.point', 'c.add_time', 'c.status']);
		$this->table(['c'=>'customers'])->where(['c.status'=>"'normal'"])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		$ends = $this->select();
		$order_nums = $this->get_order_nums();
		foreach($ends as &$data){
			$id = $data['id'];
			$data['order_num'] = $order_nums[$id] ?? 0;
		}
		return $ends;
	}
	
	/**
	 * public integer function get_locked_num(void)
	 */
	public function get_locked_num(): int {
		$ends = $this->field(['customer_num'=>'count(*)'])->table(['customers'])->where(['status'=>"'locked'"])->select();
		return $ends[0]['customer_num'] ?? -1;
	}
	
	/**
	 * public array function get_locked_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_locked_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['c.id', 'c.unique_id', 'c.open_id', 'c.nickname', 'order_num'=>'-1', 'c.balance', 'c.point', 'c.add_time', 'c.status']);
		$this->table(['c'=>'customers'])->where(['c.status'=>"'locked'"])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		$ends = $this->select();
		$order_nums = $this->get_order_nums();
		foreach($ends as &$data){
			$id = $data['id'];
			$data['order_num'] = $order_nums[$id] ?? 0;
		}
		return $ends;
	}
	
	/**
	 * public boolean function lock(integer $customer_id)
	 */
	public function lock(int $customer_id): bool {
		$datas = ['status'=>'locked'];
		$end = $this->table(['customers'])->where(['id'=>(string)$customer_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean function unlock(integer $customer_id)
	 */
	public function unlock(int $customer_id): bool {
		$datas = ['status'=>'normal'];
		$end = $this->table(['customers'])->where(['id'=>(string)$customer_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean function reset_password(integer $customer_id, string $pwd)
	 */
	public function reset_password(int $customer_id, string $new_pwd): bool {
		$datas = ['pwd'=>$new_pwd];
		$end = $this->table()->where()->modify($datas);
	}
	
	/**
	 * public boolean function message(array $datas)
	 */
	public function message(array $datas): bool {
		$end = $this->table(['customer_messages'])->add($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer function get_order_num(integer $customer_id)
	 */
	public function get_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'count(*)'])->table(['orders'])->where(['customer_id'=>(string)$customer_id])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array function get_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['o.id', 'o.unique_id', 'o.total_tag_money', 'o.total_discount_money', 'o.total_express_carriage_money', 'o.total_money', 'o.add_time', 'o.status']);
		$this->table(['o'=>'orders'])->where_cmd('`customer_id`=' . $customer_id . " and `status`!='closed'")->order(['o.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_unpaid_order_num(integer $customer_id)
	 */
	public function get_unpaid_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'count(*)'])->table(['orders'])->where(['customer_id'=>(string)$customer_id, 'status'=>"'unpaid'"])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array function get_unpaid_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_unpaid_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		// Empty!!
	}
	
	/**
	 * public integer function get_paid_order_num(integer $customer_id)
	 */
	public function get_paid_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'count(*)'])->table(['orders'])->where(['customer_id'=>(string)$customer_id, 'status'=>"'paid'"])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array function get_paid_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_paid_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		//
	}
	
	/**
	 * public integer function get_shipped_order_num(integer $customer_id)
	 */
	public function get_shipped_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'count(*)'])->table(['orders'])->where(['customer_id'=>(string)$customer_id, 'status'=>"'shipped'"])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array function get_shipped_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_shipped_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		//
	}
	
	/**
	 * public integer function get_completed_order_num(integer $customer_id)
	 */
	public function get_completed_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'count(*)'])->table(['orders'])->where(['customer_id'=>(string)$customer_id, 'status'=>"'completed'"])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array function get_completed_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_completed_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		//
	}
	
	/**
	 * public integer function get_closed_order_num(integer $customer_id)
	 */
	public function get_closed_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'count(*)'])->table(['orders'])->where(['customer_id'=>(string)$customer_id, 'status'=>"'closed'"])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array function get_closed_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_closed_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		//
	}
	
	/**
	 * public integer function get_gift_card_num(integer $customer_id)
	 */
	public function get_gift_card_num(int $customer_id): int {
		$this->field(['gift_card_num'=>'count(*)'])->table(['gift_cards']);
		$ends = $this->where(['customer_id'=>(string)$customer_id])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_gift_card_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_gift_card_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.code', 'gcm.recharge_money', 'gcm.sale_price', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->where(['gc.customer_id'=>(string)$customer_id])->order(['gc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_unpaid_gift_card_num(integer $customer_id)
	 */
	public function get_unpaid_gift_card_num(int $customer_id): int {
		$this->field(['gift_card_num'=>'count(*)'])->table(['gift_cards']);
		$ends = $this->where(['customer_id'=>(string)$customer_id, 'status'=>"'unpaid'"])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public array function get_unpaid_gift_card_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_unpaid_gift_card_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.transaction_id', 'gc.code', 'gcm.recharge_money', 'gcm.sale_price', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->where(['gc.customer_id'=>(string)$customer_id, 'status'=>"'unpaid'"])->order(['gc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_paid_gift_card_num(integer $customer_id)
	 */
	public function get_paid_gift_card_num(int $customer_id): int {
		$this->field(['gift_card_num'=>'count(*)'])->table(['gift_cards']);
		$ends = $this->where(['customer_id'=>(string)$customer_id, 'status'=>"'paid'"])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public array function get_paid_gift_card_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_paid_gift_card_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.transaction_id', 'gc.code', 'gcm.recharge_money', 'gcm.sale_price', 'gc.pay_time', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->where(['gc.customer_id'=>(string)$customer_id, 'status'=>"'paid'"])->order(['gc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_recharged_gift_card_num(integer $customer_id)
	 */
	public function get_recharged_gift_card_num(int $customer_id): int {
		$this->field(['gift_card_num'=>'count(*)'])->table(['gift_cards']);
		$ends = $this->where(['customer_id'=>(string)$customer_id, 'status'=>"'recharged'"])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public array function get_recharged_gift_card_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_recharged_gift_card_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.transaction_id', 'gc.code', 'gcm.recharge_money', 'gcm.sale_price', 'gc.pay_time', 'gc.recharge_time', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->where(['gc.customer_id'=>(string)$customer_id, 'status'=>"'recharged'"])->order(['gc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_coupon_num(integer $customer_id)
	 */
	public function get_coupon_num(int $customer_id): int {
		$this->field(['coupon_num'=>'count(*)'])->table(['coupons']);
		$ends = $this->where(['customer_id'=>(string)$customer_id])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_coupon_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_coupon_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['c.id', 'category_id'=>'cc.id', 'category_code'=>'cc.code', 'model_id'=>'cm.id', 'model_code'=>'cm.code', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'c.add_time', 'c.status']);
		$this->table(['c'=>'coupons'])->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id']);
		$this->where(['c.customer_id'=>(string)$customer_id])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_unused_coupon_num(integer $customer_id)
	 */
	public function get_unused_coupon_num(int $customer_id): int {
		$this->field(['coupon_num'=>'count(*)'])->table(['coupons']);
		$ends = $this->where(['customer_id'=>(string)$customer_id, 'status'=>"'unused'"])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_unused_coupon_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_unused_coupon_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['c.id', 'category_id'=>'cc.id', 'category_code'=>'cc.code', 'model_id'=>'cm.id', 'model_code'=>'cm.code', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'c.add_time', 'c.status']);
		$this->table(['c'=>'coupons'])->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id']);
		$this->where(['customer_id'=>(string)$customer_id, 'status'=>"'unused'"])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_used_coupon_num(integer $customer_id)
	 */
	public function get_used_coupon_num(int $customer_id): int {
		$this->field(['coupon_num'=>'count(*)'])->table(['coupons']);
		$ends = $this->where(['customer_id'=>(string)$customer_id, 'status'=>"'used'"])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_used_coupon_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_used_coupon_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['c.id', 'category_id'=>'cc.id', 'category_code'=>'cc.code', 'model_id'=>'cm.id', 'model_code'=>'cm.code', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'o.unique_id', 'c.use_time', 'c.add_time', 'c.status']);
		$this->table(['c'=>'coupons'])->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id'])->join(['o'=>'orders', 'c.order_id'=>'o.id']);
		$this->where(['customer_id'=>(string)$customer_id, 'status'=>"'used'"])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_expired_coupon_num(integer $customer_id)
	 */
	public function get_expired_coupon_num(int $customer_id): int {
		$this->field(['coupon_num'=>'count(*)'])->table(['coupons']);
		$ends = $this->where(['customer_id'=>(string)$customer_id, 'status'=>"'expired'"])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function get_expired_coupon_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_expired_coupon_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['c.id', 'category_id'=>'cc.id', 'category_code'=>'cc.code', 'model_id'=>'cm.id', 'model_code'=>'cm.code', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'c.add_time', 'c.status']);
		$this->table(['c'=>'coupons'])->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id']);
		$this->where(['customer_id'=>(string)$customer_id, 'status'=>"'unused'"])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_review_num(integer $customer_id)
	 */
	public function get_review_num(int $customer_id): int {
		$ends = $this->field(['review_num'=>'count(*)'])->table(['customer_reviews'])->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['review_num'] ?? -1;
	}
	
	/**
	 * public array function get_review_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_review_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['cr.id', 'order_id'=>'o.id', 'order_unique_id'=>'o.unique_id', 'product_id'=>'p.id', 'product_unique_id'=>'p.unique_id', 'product_code'=>'p.code', 'cr.grade', 'cr.add_time']);
		$this->table(['cr'=>'customer_reviews'])->join(['o'=>'orders', 'cr.order_id'=>'o.id'])->join(['p'=>'products', 'cr.product_id'=>'p.id']);
		$this->where(['cr.customer_id'=>$customer_id])->order(['cr.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public 
	 */
	
	/**
	 * public integer function get_deliver_address_num(integer $customer_id)
	 */
	public function get_deliver_address_num(int $customer_id): int {
		$this->field(['address_num'=>'count(*)'])->table(['customer_deliver_addresses']);
		$ends = $this->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['address_num'] ?? -1;
	}
	
	/**
	 * public array function get_deliver_address_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_deliver_address_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['cda.id', 'country_id'=>'eac.id', 'country_code'=>'eac.code', 'cda.receiver', 'cda.phone', 'cda.primary_address', 'cda.detail_address', 'cda.is_default', 'cda.add_time']);
		$this->table(['cda'=>'customer_deliver_addresses'])->join(['eac'=>'express_address_countries', 'cda.country'=>'eac.id']);
		$this->where(['cda.customer_id'=>$customer_id])->order(['cda.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_internal_deliver_address_num(integer $customer_id)
	 */
	public function get_internal_deliver_address_num(int $customer_id): int {
		$this->field(['address_num'=>'count(*)'])->table(['customer_deliver_addresses']);
		$ends = $this->where(['customer_id'=>$customer_id, 'country_id'=>1])->select();
		return $ends[0]['address_num'] ?? -1;
	}
	
	/**
	 * public array function get_internal_deliver_address_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_internal_deliver_address_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['cda.id', 'country_id'=>'eac.id', 'country_code'=>'eac.code', 'cda.receiver', 'cda.phone', 'cda.primary_address', 'cda.detail_address', 'cda.is_default', 'cda.add_time']);
		$this->table(['cda'=>'customer_deliver_addresses'])->join(['eac'=>'express_address_countries', 'cda.country'=>'eac.id']);
		$this->where(['customer_id'=>$customer_id, 'country_id'=>1])->order(['cda.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_external_deliver_address_num(integer $customer_id)
	 */
	public function get_external_deliver_address_num(int $customer_id): int {
		$this->field(['address_num'=>'count(*)'])->table(['customer_deliver_addresses']);
		$ends = $this->where_cmd('`customer_id`=' . $customer_id . ' and `country_id`!=1')->select();
		return $ends[0]['address_num'] ?? -1;
	}
	
	/**
	 * public array function get_external_deliver_address_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_external_deliver_address_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['cda.id', 'country_id'=>'eac.id', 'country_code'=>'eac.code', 'cda.receiver', 'cda.phone', 'cda.primary_address', 'cda.detail_address', 'cda.is_default', 'cda.add_time']);
		$this->table(['cda'=>'customer_deliver_addresses'])->join(['eac'=>'express_address_countries', 'cda.country'=>'eac.id']);
		$this->where_cmd('`cda`.customer_id`=' . $customer_id . ' and `cda`.`country_id`!=1')->order(['cda.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_message_num(integer $customer_id)
	 */
	public function get_message_num(int $customer_id): int {
		$ends = $this->field(['message_num'=>'count(*)'])->table(['messages'])->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * public integer function get_unread_message_num(integer $customer_id)
	 */
	public function get_unread_message_num(int $customer_id): int {
		$this->field(['message_num'=>'count(*)'])->table(['messages']);
		$ends = $this->where(['customer_id'=>$customer_id, 'status'=>'unread'])->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * public array function get_message_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_message_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['m.id', 'category_id'=>'mc.id', 'category_code'=>'mc.code', 'm.unique_id', 'm.title', 'm.add_time', 'm.status']);
		$this->table(['m'=>'messages'])->join(['mc'=>'message_categories', 'm.category_id'=>'mc.id']);
		$this->where(['customer_id'=>$customer_id])->order(['m.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_important_message_num(integer $customer_id)
	 */
	public function get_important_message_num(int $customer_id): int {
		$this->field(['message_num'=>'count(*)'])->table(['messages']);
		$ends = $this->where(['customer_id'=>$customer_id, 'category_id'=>1])->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * public integer function get_unread_important_message_num(integer $customer_id)
	 */
	public function get_unread_important_message_num(int $customer_id): int {
		$this->field(['message_num'=>'count(*)'])->table(['messages']);
		$ends = $this->where(['customer_id'=>$customer_id, 'category_id'=>1, 'status'=>'unread'])->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * public array function get_important_message_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_important_message_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['m.id', 'category_id'=>'mc.id', 'category_code'=>'mc.code', 'm.unique_id', 'm.title', 'm.add_time', 'm.status']);
		$this->table(['m'=>'messages'])->join(['mc'=>'message_categories', 'm.category_id'=>'mc.id']);
		$this->where(['customer_id'=>$customer_id, 'category_id'=>1])->order(['m.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_order_message_num(integer $customer_id)
	 */
	public function get_order_message_num(int $customer_id): int {
		$this->field(['message_num'=>'count(*)'])->table(['messages']);
		$ends = $this->where(['customer_id'=>$customer_id, 'category_id'=>2])->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * public integer function get_unread_order_message_num(integer $customer_id)
	 */
	public function get_unread_order_message_num(int $customer_id): int {
		$this->field(['message_num'=>'count(*)'])->table(['messages']);
		$ends = $this->where(['customer_id'=>$customer_id, 'category_id'=>2, 'status'=>'unread'])->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * public array function get_order_message_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_order_message_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['m.id', 'category_id'=>'mc.id', 'category_code'=>'mc.code', 'm.unique_id', 'm.title', 'm.add_time', 'm.status']);
		$this->table(['m'=>'messages'])->join(['mc'=>'message_categories', 'm.category_id'=>'mc.id']);
		$this->where(['customer_id'=>$customer_id, 'category_id'=>2])->order(['m.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_account_message_num(integer $customer_id)
	 */
	public function get_account_message_num(int $customer_id): int {
		$this->field(['message_num'=>'count(*)'])->table(['messages']);
		$ends = $this->where(['customer_id'=>$customer_id, 'category_id'=>3])->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * public integer function get_unread_account_message_num(integer $customer_id)
	 */
	public function get_unread_account_message_num(int $customer_id): int {
		$this->field(['message_num'=>'count(*)'])->table(['messages']);
		$ends = $this->where(['customer_id'=>$customer_id, 'category_id'=>3, 'status'=>'unread'])->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * public array function get_account_message_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_account_message_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['m.id', 'category_id'=>'mc.id', 'category_code'=>'mc.code', 'm.unique_id', 'm.title', 'm.add_time', 'm.status']);
		$this->table(['m'=>'messages'])->join(['mc'=>'message_categories', 'm.category_id'=>'mc.id']);
		$this->where(['customer_id'=>$customer_id, 'category_id'=>3])->order(['m.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_other_message_num(integer $customer_id)
	 */
	public function get_other_message_num(int $customer_id): int {
		$this->field(['message_num'=>'count(*)'])->table(['messages']);
		$ends = $this->where(['customer_id'=>$customer_id, 'category_id'=>4])->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * public integer function get_unread_other_message_num(integer $customer_id)
	 */
	public function get_unread_other_message_num(int $customer_id): int {
		$this->field(['message_num'=>'count(*)'])->table(['messages']);
		$ends = $this->where(['customer_id'=>$customer_id, 'category_id'=>4, 'status'=>'unread'])->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * public array function get_other_message_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_other_message_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['m.id', 'category_id'=>'mc.id', 'category_code'=>'mc.code', 'm.unique_id', 'm.title', 'm.add_time', 'm.status']);
		$this->table(['m'=>'messages'])->join(['mc'=>'message_categories', 'm.category_id'=>'mc.id']);
		$this->where(['customer_id'=>$customer_id, 'category_id'=>4])->order(['m.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * protected array function get_order_nums(void)
	 */
	protected function get_order_nums(): array {
		$datas = $this->field(['customer_id', 'num'=>'count(*)'])->table(['orders'])->group(['customer_id'])->order(['customer_id'=>'asc'])->select();
		foreach($datas as $data){
			list('customer_id'=>$id, 'num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	//
}











