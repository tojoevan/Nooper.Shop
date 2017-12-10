<?php

// declare(strict_types = 1);
namespace NooperShop;

use Nooper\Mysql;
use Nooper\Product;

class Customer extends Mysql {
	
	/**
	 * Const
	 */
	const page_record_num = 20;
	
	/**
	 * public string unique_id(void)
	 */
	public function unique_id(): string {
		$unique = new Unique();
		do{
			$unique_id = $unique->customer();
			$datas = $this->field(['num'=>'COUNT(*)'])->table(['customers'])->where(['unique_id'=>$unique_id])->select();
			if(isset($datas[0]) && $datas[0]['num'] > 0) continue;
			break;
		}while(true);
		return $unique_id;
	}
	
	/**
	 * public integer get_order_num(integer $customer_id)
	 */
	public function get_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'COUNT(*)'])->table(['orders'])->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array get_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_order_view()->where(['o.customer_id'=>$customer_id])->order(['o.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_unpaid_order_num(integer $customer_id)
	 */
	public function get_unpaid_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'COUNT(*)'])->table(['orders'])->where(['customer_id'=>$customer_id, 'status'=>'unpaid'])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array get_unpaid_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_unpaid_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_order_view()->where(['o.customer_id'=>$customer_id, 'o.status'=>'unpaid'])->order(['o.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_paid_order_num(integer $customer_id)
	 */
	public function get_paid_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'COUNT(*)'])->table(['orders'])->where(['customer_id'=>$customer_id, 'status'=>'paid'])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array get_paid_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_paid_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_order_view()->where(['o.customer_id'=>$customer_id, 'o.status'=>'paid'])->order(['o.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_shipped_order_num(integer $customer_id)
	 */
	public function get_shipped_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'COUNT(*)'])->table(['orders'])->where(['customer_id'=>$customer_id, 'status'=>'shipped'])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array get_shipped_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_shipped_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_order_view()->where(['o.customer_id'=>$customer_id, 'o.status'=>'shipped'])->order(['o.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_completed_order_num(integer $customer_id)
	 */
	public function get_completed_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'COUNT(*)'])->table(['orders'])->where(['customer_id'=>$customer_id, 'status'=>'completed'])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array get_completed_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_completed_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_order_view()->where(['o.customer_id'=>$customer_id, 'o.status'=>'completed'])->order(['o.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_closed_order_num(integer $customer_id)
	 */
	public function get_closed_order_num(int $customer_id): int {
		$ends = $this->field(['order_num'=>'COUNT(*)'])->table(['orders'])->where(['customer_id'=>$customer_id, 'status'=>'closed'])->select();
		return $ends[0]['order_num'] ?? -1;
	}
	
	/**
	 * public array get_closed_order_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_closed_order_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_order_view()->where(['o.customer_id'=>$customer_id, 'o.status'=>'closed'])->order(['o.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_gift_card_num(integer $customer_id)
	 */
	public function get_gift_card_num(int $customer_id): int {
		$ends = $this->field(['gift_card_num'=>'COUNT(*)'])->table(['gift_cards'])->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public arrayget_gift_card_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_gift_card_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_gift_card_view()->where(['gc.customer_id'=>$customer_id])->order(['gc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_unpaid_gift_card_num(integer $customer_id)
	 */
	public function get_unpaid_gift_card_num(int $customer_id): int {
		$ends = $this->field(['gift_card_num'=>'COUNT(*)'])->table(['gift_cards'])->where(['customer_id'=>$customer_id, 'status'=>'unpaid'])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public array get_unpaid_gift_card_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_unpaid_gift_card_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_gift_card_view()->where(['gc.customer_id'=>$customer_id, 'gc.status'=>'unpaid'])->order(['gc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_paid_gift_card_num(integer $customer_id)
	 */
	public function get_paid_gift_card_num(int $customer_id): int {
		$ends = $this->field(['gift_card_num'=>'COUNT(*)'])->table(['gift_cards'])->where(['customer_id'=>$customer_id, 'status'=>'paid'])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public array get_paid_gift_card_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_paid_gift_card_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_gift_card_view()->where(['gc.customer_id'=>$customer_id, 'gc.status'=>'paid'])->order(['gc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_recharged_gift_card_num(integer $customer_id)
	 */
	public function get_recharged_gift_card_num(int $customer_id): int {
		$ends = $this->field(['gift_card_num'=>'COUNT(*)'])->table(['gift_cards'])->where(['customer_id'=>$customer_id, 'status'=>'recharged'])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public array get_recharged_gift_card_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_recharged_gift_card_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_gift_card_view()->where(['gc.customer_id'=>$customer_id, 'gc.status'=>'recharged'])->order(['gc.id'=>'desc']);
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
	 * public integer get_cart_num(integer $customer_id)
	 */
	public function get_cart_num(int $customer_id): int {
		$ends = $this->field(['cart_num'=>'COUNT(*)'])->table(['customer_carts'])->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['cart_num'] ?? -1;
	}
	
	/**
	 * public array get_cart_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_cart_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
	}
	
	/**
	 * public integer get_collection_num(integer $customer_id)
	 */
	public function get_favourite_num(int $customer_id): int {
		$this->field(['favourite_num'=>'COUNT(*)'])->table(['customer_favourites']);
		$ends = $this->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['favourite_num'] ?? -1;
	}
	
	/**
	 * public array get_favourite_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_favourite_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_favourite_view()->where(['cf.customer_id'=>$customer_id])->order(['cf.id'=>'desc']);
		$ends = $this->limit($page_length, $offset_num)->select();
		return $this->enlarge_product_primary_pictures($ends);
	}
	
	/**
	 * public integer get_footmark_num(integer $customer_id)
	 */
	public function get_footmark_num(int $customer_id): int {
		$this->field(['footmark_num'=>'COUNT(*)'])->table(['customer_footmarks']);
		$ends = $this->where(['customer_id'=>$customer_id])->select();
		return $ends[0]['footmark_num'] ?? -1;
	}
	
	/**
	 * public array get_footmark_page(integer $customer_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_footmark_page(int $customer_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_footmark_view()->where(['cf.customer_id'=>$customer_id])->order(['cf.id'=>'desc']);
		$ends = $this->limit($page_length, $offset_num)->select();
		return $this->enlarge_product_primary_pictures($ends);
	}
	
	/**
	 * public integer num(void)
	 */
	public function num(): int {
		$ends = $this->field(['customer_num'=>'COUNT(*)'])->table(['customers'])->select();
		return $ends[0]['customer_num'] ?? -1;
	}
	
	/**
	 * public integer get_normal_num(void)
	 */
	public function get_normal_num(): int {
		$ends = $this->field(['customer_num'=>'COUNT(*)'])->table(['customers'])->where(['status'=>'normal'])->select();
		return $ends[0]['customer_num'] ?? -1;
	}
	
	/**
	 * public array get_normal_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_normal_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_view()->where(['status'=>'normal'])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		$ends = $this->select();
		return $ends;
	}
	
	/**
	 * public integer get_locked_num(void)
	 */
	public function get_locked_num(): int {
		$ends = $this->field(['customer_num'=>'COUNT(*)'])->table(['customers'])->where(['status'=>'locked'])->select();
		return $ends[0]['customer_num'] ?? -1;
	}
	
	/**
	 * public array get_locked_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_locked_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_view()->where(['status'=>'locked'])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		$ends = $this->select();
		return $ends;
	}
	
	/**
	 * public array record(integer $customer_id)
	 */
	public function record(int $customer_id): array {
		$ends = $this->_view()->where(['id'=>$customer_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array find(string $key)
	 * $key = string unique_id or open_id
	 */
	public function find(string $key): array {
		$ends = $this->_view()->where(['unique_id'=>$key, 'open_id'=>$key], 'eq', 'or')->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean lock(integer $customer_id)
	 */
	public function lock(int $customer_id): bool {
		$end = $this->table(['customers'])->where(['id'=>$customer_id])->modify(['status'=>'locked']);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean unlock(integer $customer_id)
	 */
	public function unlock(int $customer_id): bool {
		$end = $this->table(['customers'])->where(['id'=>$customer_id])->modify(['status'=>'normal']);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public array login(string $phone, string $pwd)
	 */
	public function login(string $email, string $pwd): array {
		$pwd = ["PASSWORD('" . $pwd . "')"];
		$ends = $this->field(['id', 'unique_id', 'open_id', 'phone'])->table(['customers'])->where(['phone'=>$phone, 'pwd'=>$pwd])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean password(integer $customer_id, string $pwd)
	 */
	public function password(int $customer_id, string $pwd): bool {
		$pwd = ["PASSWORD('" . $pwd . "')"];
		$end = $this->table(['customers'])->where(['id'=>$customer_id])->modify(['pwd'=>$pwd]);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean edit(integer $customer_id, array $datas)
	 * @$datas = [string $real_name, string $phone]
	 */
	public function edit(int $customer_id, array $datas): bool {
		$end = $this->table(['customer'])->where(['id'=>$customer_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean register(integer $customer_id, string $phone, string $pwd)
	 */
	public function register(int $customer_id, string $phone, string $pwd): bool {
		$datas = ['phone'=>$phone, 'pwd'=>$pwd];
		$end = $this->tables(['customers'])->where(['id'=>$customer_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public ?integer create(array $datas)
	 * @datas = [string $open_id, string $nickname, string $sex, string $head_img_url]
	 */
	public function create(array $datas): ?int {
		$datas = array_merge($datas, ['unique_id'=>$this->unique_id()]);
		$end = $this->table(['customers'])->add($datas);
		return $end > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * protected array get_order_nums(void)
	 */
	protected function get_order_nums(): array {
		$datas = $this->field(['customer_id', 'num'=>'count(*)'])->table(['orders'])->group(['customer_id'])->order(['customer_id'=>'asc'])->select();
		foreach($datas as $data){
			list('customer_id'=>$id, 'num'=>$num) = $data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	/**
	 * protected array enlarge_product_primary_pictures(void)
	 */
	protected function enlarge_product_primary_pictures(array $datas): array {
		$product_primary_pictures = (new Product())->get_primary_pictures();
		foreach($datas as &$data){
			$product_id = $data['product_id'];
			$data['product_primary_picture'] = $product_primary_pictures[$product_id] ?? null;
		}
		return $datas;
	}
	
	/**
	 * protected Customer _order_view(void)
	 */
	protected function _order_view(): Customer {
		$o_cols = ['o.id', 'o.unique_id', 'o.total_tag_money', 'o.total_discount_money', 'o.total_express_carriage_money', 'o.total_money', 'o.add_time', 'o.status'];
		$this->field(array_merge($o_cols))->table(['o'=>'orders']);
		
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.transaction_id', 'gc.code', 'gcm.recharge_money', 'gcm.sale_price', 'gc.pay_time', 'gc.recharge_time', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		
		return $this;
	}
	
	/**
	 * protected Customer _gift_view(void)
	 */
	protected function _gift_view(): Customer {
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.code', 'gcm.recharge_money', 'gcm.sale_price', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		return $this;
	}
	
	/**
	 * protected Customer _favourite_view(void)
	 */
	protected function _collection_view(): Customer {
		$cf_cols = ['cf.id', 'cf.add_time'];
		$p_cols = ['product_id'=>'p.id', 'product_name'=>'p.name', 'product_tag_price'=>'p.tag_price', 'product_discount_price'=>'p.discount_price', 'product_status'=>'p.status'];
		$define_cols = ['product_primary_picture'=>null];
		$this->field(array_merge($cf_cols, $p_cols, $define_cols))->table(['cf'=>'customer_favourites']);
		$this->join(['p'=>'products', 'cf.product_id'=>'p.id']);
		return $this;
	}
	
	/**
	 * protected Customer _footmark_view(void)
	 */
	protected function _footmark_view(): Customer {
		$cf_cols = ['cf.id', 'cf.add_time'];
		$p_cols = ['product_id'=>'p.id', 'product_name'=>'p.name', 'product_tag_price'=>'p.tag_price', 'product_discount_price'=>'p.discount_price', 'product_status'=>'p.status'];
		$define_cols = ['product_primary_picture'=>null];
		$this->field(array_merge($cf_cols, $p_cols, $define_cols))->table(['cf'=>'customer_footmarks']);
		$this->join(['p'=>'products', 'cf.product_id'=>'p.id']);
		return $this;
	}
	
	/**
	 * protected Customer _view(void)
	 */
	protected function _view(): Customer {
		$c_cols = ['c.id', 'c.unique_id', 'c.open_id', 'c.nickname', 'c.balance', 'c.point', 'c.add_time', 'c.status'];
		$define_cols = ['order_num'=>'-1'];
		$this->field(array_merge($c_cols, $define_cols))->table(['c'=>'customers']);
		return $this;
	}
	
	//
}











