<?php
// declare(strict_types = 1);
namespace Nooper;

class Coupon extends Mysql {
	
	/**
	 * public array function get_categories(integer $pagea_num, integer $page_length = 20)
	 */
	public function get_categories(int $page_num, int $page_length = 20): array {
		$offset_num = ($page_num - 1) * $page_length;
		return $this->fields(['cc.code', 'cc.name', 'model_num'=>'count(distinct cm.id)', 'coupon_num'=>'count(c.id)'])->table(['cc'=>'coupon_categories'])->join(['cm'=>'coupon_models', 'cc.id'=>'cm.category_id'], 'left')->join(['c'=>'coupons', 'cm.id'=>'c.model_id'])->group(['cc.code'])->order(['cc.id'=>'asc'])->limit($page_length, $offset_num);
	}
	
	/**
	 * public array function get_models(integer $page_num, integer $page_length = 20)
	 */
	public function get_models(int $page_num, int $page_length = 20): array {
		$offset_num = ($page_num - 1) * $page_length;
		$field_datas = ['cc.code', 'cm.code', 'cm.name', 'cm.tag_price', 'cm.min_charge', 'cm.quantity', 'cm.begin_time', 'cm.end_time', 'cm.add_time'];
		$table_datas = ['cm'=>'coupon_models'];
		$join_datas = ['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id'];
		$order_datas = ['cm.id'=>'desc'];
		return $this->field($field_datas)->table($table_datas)->join($join_datas)->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	
	/**
	 * public array function get_coupons(integer $page_num, integer $page_length = 20)
	 */
	public function get_coupons(int $page_num, int $page_length = 20): array {
		$offset_num = ($page_num - 1) * $page_length;
		$field_datas = ['c.id', 'cc.code', 'cm.code', 'c.unique_id', 'cm.tag_price', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'c.add_time', 'c.status'];
		$table_datas = ['cc'=>'coupon_categories'];
		$join1_datas = ['cm'=>'coupon_models', 'cc.id'=>'cm.category_id'];
		$join2_datas = ['c'=>'coupons', 'cm.id'=>'c.model_id'];
		$order_datas = ['c.id'=>'desc'];
		return $this->field($field_datas)->table()->join($join1_datas)->join($join2_datas)->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	
	/**
	 * public array function get_coupon_sale_records(integer $page_num, integer $page_length = 20)
	 */
	public function get_coupon_sale_records(int $page_num, int $page_length = 20): array {
		$offset_num = ($page_num - 1) * $page_length;
		$field_datas = ['c.id', 'cc.code', 'cm.code', 'c.unique_id', 'cm.tag_price', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'c.add_time', 'c.status', 'u.unique_id', 'u.nickname', 'csr.add_time'];
		$table_datas = ['cc'=>'coupon_categories'];
		$join1_datas = ['cm'=>'coupon_models', 'cc.id'=>'cm.category_id'];
		$join2_datas = ['c'=>'coupons', 'cm.id'=>'c.model_id'];
		$join3_datas = ['csr'=>'coupon_sale_records', 'c.id'=>'csr.coupon_id'];
		$join4_datas = ['u'=>'customers', 'csr.customer_id'=>'u.id'];
		$order_datas = ['c.id'=>'desc'];
		return $this->field($field_datas)->table()->join($join1_datas)->join($join2_datas)->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	
	/**
	 * public array function get_coupon_use_records(interger $page_num, integer $page_length = 20)
	 */
	public function get_coupon_use_records(int $page_num, int $page_length = 20): array {
		$offset_num = ($page_num - 1) * $page_length;
		$field_datas = ['cur.id', 'cc.code', 'cm.code', 'c.unique_id', 'cm.tag_price', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'c.add_time', 'c.status', 'u.unique_id', 'u.nickname', 'cur.add_time'];
		$table_datas = ['cur'=>'coupon_use_records'];
		$join1_datas = ['c'=>'coupons', 'cur.coupon_id'=>'c.id'];
		$join2_datas = ['cm'=>'coupon_models', 'c.model_id'=>'cm.id'];
		$join3_datas = ['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id'];
		$join4_datas = ['o'=>'orders', 'cur.order_id'=>'o.id'];
		$join5_datas = ['u'=>'customers', 'o.customer_id'=>'u.id'];
		$order_datas = ['cur.id'=>'desc'];
		return $this->field($field_datas)->table($table_datas)->join($join1_datas)->join($join2_datas)->join($join3_datas)->join($join4_datas)->join($join5_datas)->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	//
}










