<?php

// declare(strict_types = 1);
namespace NooperShop;

use Nooper\Mysql;

class Coupon extends Mysql {
	
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
			$unique_id = $unique->coupon();
			$datas = $this->field(['num'=>'COUNT(*)'])->table(['coupons'])->where(['unique_id'=>$unique_id])->select();
			if(isset($datas[0]) && $datas[0]['num'] > 0) continue;
			break;
		}while(true);
		return $unique_id;
	}
	
	/**
	 * public integer get_category_num(void)
	 */
	public function get_category_num(): int {
		$ends = $this->field(['category_num'=>'COUNT(*)'])->table(['coupon_categories'])->select();
		return $ends[0]['category_num'] ?? -1;
	}
	
	/**
	 * public array get_category_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_category_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_category_view()->group(['cc.id'])->order(['cc.id'=>'asc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_category_record(integer $category_id)
	 */
	public function get_category_record(int $category_id): array {
		$ends = $this->_category_view()->where(['cc.id'=>$category_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public integer get_model_num(void)
	 */
	public function get_model_num(): int {
		$ends = $this->field(['model_num'=>'COUNT(*)'])->table(['coupon_models'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public integer get_normal_model_num(void)
	 */
	public function get_normal_model_num(): int {
		$ends = $this->field(['model_num'=>'COUNT(*)'])->table(['coupon_models'])->where(['status'=>'normal'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public array get_normal_model_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_normal_model_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_model_view()->order(['cm.id'=>'desc'])->where(['cm.status'=>'normal']);
		$ends = $this->limit($page_length, $offset_num)->select();
		return $this->get_model_defined_datas($ends);
	}
	
	/**
	 * public integer get_expired_model_num(void)
	 */
	public function get_expired_model_num(): int {
		$ends = $this->field(['model_num'=>'COUNT(*)'])->table(['coupon_models'])->where(['status'=>'expired'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public array get_expired_model_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_expired_model_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_model_view()->order(['cm.id'=>'desc'])->where(['cm.status'=>'expired']);
		$ends = $this->limit($page_length, $offset_num)->select();
		return $this->get_model_defined_datas($ends);
	}
	
	/**
	 * public integer get_deleted_model_num(void)
	 */
	public function get_deleted_model_num(): int {
		$ends = $this->field(['model_num'=>'COUNT(*)'])->table(['coupon_models'])->where(['status'=>'deleted'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public array get_deleted_model_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_deleted_model_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_model_view()->order(['cm.id'=>'desc'])->where(['cm.status'=>'deleted']);
		$ends = $this->limit($page_length, $offset_num)->select();
		return $this->get_model_defined_datas($ends);
	}
	
	/**
	 * public array get_model_record(integer $model_id)
	 */
	public function get_model_record(int $model_id): array {
		$datas = $this->_model_view()->where(['cm.id'=>$model_id])->select();
		$ends = $this->get_model_defined_datas($datas);
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean recover_model(integer $model_id)
	 */
	public function recover_model(int $model_id): bool {
		$datas = $this->field(['end_time'])->table(['coupon_models'])->where(['id'=>$model_id])->select();
		if(isset($datas[0])){
			$status = $datas[0]['end_time'] >= get_now_timestamp() ? 'normal' : 'expired';
			$end = $this->table(['coupon_models'])->where(['id'=>$model_id])->modify(['status'=>$status]);
			return $end > 0 ? true : false;
		}
		return false;
	}
	
	/**
	 * public integer delete_model(integer $model_id)
	 */
	public function delete_model(int $model_id): int {
		$datas = $this->field(['coupon_num'=>'COUNT(*)'])->table(['coupons'])->where(['id'=>$model_id])->select();
		if(isset($datas[0])){
			$this->table(['coupon_models'])->where(['id'=>$model_id]);
			if($datas[0]['copon_num'] > 0) return $this->modify(['status'=>'deleted']) > 0 ? 2 : -1;
			else return $this->delete() > 0 ? 1 : -1;
		}
		return -1;
	}
	
	/**
	 * public boolean edit_model(integer $model_id, array $datas)
	 * @$datas = [string $code, string $name]
	 */
	public function edit_model(int $model_id, array $datas): bool {
		$end = $this->table(['coupon_models'])->where(['id'=>$model_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public ?integer add_model(array $datas)
	 * @$datas = [integer $category_id, string $code, string $name, float $discount_money, float $min_charge_money, integer $begin_time, integer $end_time]
	 */
	public function add_model(array $datas): ?int {
		$end = $this->table(['coupon_models'])->add($datas);
		return $end > 0 ? $this->get_last_id() : $end;
	}
	
	/**
	 * public integer num(void)
	 */
	public function num(): int {
		$ends = $this->field(['coupon_num'=>'COUNT(*)'])->table(['coupons'])->select();
		return $ends[0]['coupon_num'] ?? -1;
	}
	
	/**
	 * public integer get_unused_num(void)
	 */
	public function get_unused_num(): int {
		$ends = $this->field(['coupon_num'=>'COUNT(*)'])->table(['coupons'])->where(['status'=>'unused'])->select();
		return $ends[0]['coupon_num'] ?? -1;
	}
	
	/**
	 * public array get_unused_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_unused_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_view()->where(['status'=>'unused'])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_used_num(void)
	 */
	public function get_used_num(): int {
		$ends = $this->field(['coupon_num'=>'COUNT(*)'])->table(['coupons'])->where(['status'=>'used'])->select();
		return $ends[0]['coupon_num'] ?? -1;
	}
	
	/**
	 * public array get_used_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_used_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_view()->where(['status'=>'used'])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_expired_num(void)
	 */
	public function get_expired_num(): int {
		$ends = $this->field(['coupon_num'=>'COUNT(*)'])->table(['coupons'])->where(['status'=>'expired'])->select();
		return $ends[0]['coupon_num'] ?? -1;
	}
	
	/**
	 * public array get_expired_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_expired_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_view()->where(['status'=>'expired'])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array record(integer $coupon_id)
	 */
	public function record(int $coupon_id): array {
		$ends = $this->_view()->where(['c.id'=>$coupon_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array find(string $coupon_unique_id)
	 */
	public function find(string $unique_id): array {
		$ends = $this->_view()->where(['c.unique_id'=>$coupon_unique_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * protected array get_model_defined_datas(array $datas)
	 */
	protected function get_model_defined_datas(array $datas): array {
		$nums = $this->get_model_coupon_nums();
		$used_nums = $this->get_model_used_coupon_nums();
		foreach($datas as &$data){
			$model_id = $data['id'];
			$end['coupon_num'] = $nums[$model_id] ?? 0;
			$end['used_coupon_num'] = $used_nums[$model_id] ?? 0;
		}
		return $datas;
	}
	
	/**
	 * protected array get_model_coupon_nums(void)
	 */
	protected function get_model_coupon_nums(): array {
		$this->field(['model_id', 'coupon_num'=>'count(*)'])->table(['coupons']);
		$datas = $this->group(['model_id'])->order(['model_id'=>'asc'])->select();
		foreach($datas as $data){
			$ends[$data['model_id']] = $data['coupon_num'];
		}
		return $ends ?? [];
	}
	
	/**
	 * protected array get_model_used_coupon_nums(void)
	 */
	protected function get_model_used_coupon_nums(): array {
		$this->field(['model_id', 'coupon_num'=>'count(*)'])->table(['coupons']);
		$datas = $this->where(['status'=>'used'])->group(['model_id'])->order(['model_id'=>'asc'])->select();
		foreach($datas as $data){
			$ends[$data['model_id']] = $data['coupon_num'];
		}
		return $ends ?? [];
	}
	
	/**
	 * protected Coupon _category_view(void)
	 */
	protected function _category_view(): Coupon {
		$cc_cols = ['cc.id', 'cc.code', 'cc.name', 'cc.add_time'];
		$func_cols = ['model_num'=>'COUNT(DISTINCT `cm`.`id`)', 'coupon_num'=>'COUNT(`c`.`id`)'];
		$this->field(array_merge($cc_cols, $func_cols))->table(['cc'=>'coupon_categories']);
		$this->join(['cm'=>'coupon_models', 'cc.id'=>'cm.category_id'], 'left');
		$this->join(['c'=>'coupons', 'cm.id'=>'c.model_id'], 'left');
		return $this;
	}
	
	/**
	 * protected Coupon _model_view(void)
	 */
	protected function _model_view(): Coupon {
		$cm_cols = ['cm.id', 'cm.code', 'cm.name', 'cm.discount_money', 'cm.min_charge_money', 'cm.begin_time', 'cm.end_time', 'cm.add_time'];
		$cc_cols = ['category_id=>cc.id', 'category_code=>cc.code'];
		$define_cols = ['coupon_num'=>-1, 'used_coupon_num'=>-1];
		$this->field(array_merge($cm_cols, $cc_cols, $define_cols))->table(['cm'=>'coupon_models']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id']);
		return $this;
	}
	
	/**
	 * protected Coupon _view(void)
	 */
	protected function _view(): Coupon {
		$c_cols = ['c.id', 'c.unique_id', 'c.use_time', 'c.add_time'];
		$cm_cols = ['model_id'=>'cm.id', 'model_code'=>'cm.code', 'cm.discount_money', 'cm.min_charge_money', 'cm.begin_time', 'cm.end_time'];
		$cc_cols = ['category_id'=>'cc.id', 'category_code'=>'cc.code'];
		$u_cols = ['customer_id'=>'u.id', 'customer_unique_id'=>'u.unique_id'];
		$o_cols = ['order_id'=>'o.id', 'order_unique_id'=>'o.unique_id'];
		$this->field(array_merge($c_cols, $cm_cols, $cc_cols, $u_cols, $o_cols))->table(['c'=>'coupons']);
		$this->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id'])->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id']);
		$this->join(['u'=>'customers', 'c.customer_id'=>'u.id'])->join(['o'=>'orders', 'c.order_id'=>'o.id']);
		return $this;
	}
	//
}










