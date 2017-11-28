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
		//
	}
	
	/**
	 * public integer function get_category_num(void)
	 */
	public function get_category_num(): int {
		$ends = $this->field(['category_num'=>'count(*)'])->table(['coupon_categories'])->select();
		return $ends[0]['category_num'] ?? -1;
	}
	
	/**
	 * public array function get_category_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_category_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['cc.id', 'cc.code', 'cc.name', 'model_num'=>'count(distinct `cm`.`id`)', 'coupon_num'=>'count(`c`.`id`)', 'cc.add_time']);
		$this->table(['cc'=>'coupon_categories'])->join(['cm'=>'coupon_models', 'cc.id'=>'cm.category_id'], 'left');
		$this->join(['c'=>'coupons', 'cm.id'=>'c.model_id'], 'left');
		$this->group(['cc.id'])->order(['cc.id'=>'asc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array function get_category_record(integer $categroy_id)
	 */
	public function get_category_record(int $category_id): array {
		$this->field(['cc.id', 'cc.code', 'cc.name', 'model_num'=>'count(distinct `cm`.`id`)', 'coupon_num'=>'count(`c`.`id`)', 'cc.add_time']);
		$this->table(['cc'=>'coupon_categories'])->join(['cm'=>'coupon_models', 'cc.id'=>'cm.category_id'], 'left');
		$this->join(['c'=>'coupons', 'cm.id'=>'c.model_id'], 'left');
		$ends = $this->group(['cc.id'])->where(['cc.id'=>$category_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public integer function get_model_num(void)
	 */
	public function get_model_num(): int {
		$ends = $this->field(['model_num'=>'count(*)'])->table(['coupon_models'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public array function get_model_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_model_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['cm.id', 'category_id=>cc.id', 'category_code=>cc.code', 'cm.code', 'cm.name', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'coupon_num'=>-1, 'used_coupon_num'=>-1, 'cm.add_time', 'cm.status']);
		$this->table(['cm'=>'coupon_models'])->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id']);
		$ends = $this->order(['cm.id'=>'asc'])->limit($page_length, $offset_num)->select();
		if($ends){
			$nums = $this->get_model_coupon_nums();
			$used_nums = $this->get_model_used_coupon_nums();
			foreach($ends as &$data){
				$model_id = $data['id'];
				$data['coupon_num'] = $nums[$model_id] ?? 0;
				$data['used_coupon_num'] = $used_nums[$model_id] ?? 0;
			}
		}
		return $ends;
	}
	
	/**
	 * public integer function get_normal_model_num(void)
	 */
	public function get_normal_model_num(): int {
		$ends = $this->field(['model_num'=>'count(*)'])->table(['coupon_models'])->where(['status'=>'normal'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public array function get_normal_model_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_normal_model_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['cm.id', 'category_id=>cc.id', 'category_code=>cc.code', 'cm.code', 'cm.name', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'coupon_num'=>-1, 'used_coupon_num'=>-1, 'cm.add_time', 'cm.status']);
		$this->table(['cm'=>'coupon_models'])->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id']);
		$this->where(['cm.status'=>'normal'])->order(['cm.id'=>'asc']);
		$ends = $this->limit($page_length, $offset_num)->select();
		if($ends){
			$nums = $this->get_model_coupon_nums();
			$used_nums = $this->get_model_used_coupon_nums();
			foreach($ends as &$data){
				$model_id = $data['id'];
				$data['coupon_num'] = $nums[$model_id] ?? 0;
				$data['used_coupon_num'] = $used_nums[$model_id] ?? 0;
			}
		}
		return $ends;
	}
	
	/**
	 * public integer function get_expired_model_num(void)
	 */
	public function get_expired_model_num(): int {
		$ends = $this->field(['model_num'=>'count(*)'])->table(['coupon_models'])->where(['status'=>'expired'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public array function get_expired_model_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_expired_model_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['cm.id', 'category_id=>cc.id', 'category_code=>cc.code', 'cm.code', 'cm.name', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'coupon_num'=>-1, 'used_coupon_num'=>-1, 'cm.add_time', 'cm.status']);
		$this->table(['cm'=>'coupon_models'])->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id']);
		$this->where(['cm.status'=>'expired'])->order(['cm.id'=>'asc']);
		$ends = $this->limit($page_length, $offset_num)->select();
		if($ends){
			$nums = $this->get_model_coupon_nums();
			$used_nums = $this->get_model_used_coupon_nums();
			foreach($ends as &$data){
				$model_id = $data['id'];
				$data['coupon_num'] = $nums[$model_id] ?? 0;
				$data['used_coupon_num'] = $used_nums[$model_id] ?? 0;
			}
		}
		return $ends;
	}
	
	/**
	 * public integer function get_deleted_model_num(void)
	 */
	public function get_deleted_model_num(): int {
		$ends = $this->field(['model_num'=>'count(*)'])->table(['coupon_models'])->where(['status'=>'deleted'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public array function get_deleted_model_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_deleted_model_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['cm.id', 'category_id=>cc.id', 'category_code=>cc.code', 'cm.code', 'cm.name', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'coupon_num'=>-1, 'used_coupon_num'=>-1, 'cm.add_time', 'cm.status']);
		$this->table(['cm'=>'coupon_models'])->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id']);
		$this->where(['cm.status'=>'deleted'])->order(['cm.id'=>'asc']);
		$ends = $this->limit($page_length, $offset_num)->select();
		if($ends){
			$nums = $this->get_model_coupon_nums();
			$used_nums = $this->get_model_used_coupon_nums();
			foreach($ends as &$data){
				$model_id = $data['id'];
				$data['coupon_num'] = $nums[$model_id] ?? 0;
				$data['used_coupon_num'] = $used_nums[$model_id] ?? 0;
			}
		}
		return $ends;
	}
	
	/**
	 * public array function get_model_record(integer $model_id)
	 */
	public function get_model_record(int $model_id): array {
		$this->field(['cm.id', 'category_id=>cc.id', 'category_code=>cc.code', 'cm.code', 'cm.name', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'coupon_num'=>-1, 'used_coupon_num'=>-1, 'cm.add_time', 'cm.status']);
		$this->table(['cm'=>'coupon_models'])->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id']);
		$datas = $this->where(['cm.id'=>$model_id])->select();
		if($datas[0]){
			$ends = $datas[0];
			$nums = $this->get_model_coupon_nums();
			$used_nums = $this->get_model_used_coupon_nums();
			$id = $ends['id'];
			$ends['coupon_num'] = $nums[$id] ?? 0;
			$ends['used_coupon_num'] = $used_nums[$id] ?? 0;
		}
		return $ends ?? [];
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
	 * public integer function delete_model(integer $model_id)
	 */
	public function delete_model(int $model_id): int {
		$datas = $this->field(['coupon_num'=>'count(*)'])->table(['coupons'])->where(['id'=>$model_id])->select();
		if(isset($datas[0])){
			$this->$this->table(['coupon_models'])->where(['id'=>$model_id]);
			$end = $datas[0]['coupon_num'] > 0 ? $this->modify(['status'=>'deleted']) : $this->delete();
			return $end > 0 ? true : false;
		}
		return false;
	}
	
	/**
	 * public boolean function modify_model(integer $model_id, array $datas)
	 */
	public function modify_model(int $model_id, array $datas): bool {
	}
	
	/**
	 * public integer function add_model(array $datas)
	 */
	public function add_model(array $datas): bool {
		$end = $this->table(['coupon_models'])->add($datas);
		return $end > 0 ? $this->get_last_id() : $end;
	}
	
	/**
	 * public integer function num(void)
	 */
	public function num(): int {
		$ends = $this->field(['coupon_num'=>'count(*)'])->table(['coupons'])->select();
		return $ends[0]['coupon_num'] ?? -1;
	}
	
	/**
	 * public array function page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['c.id', 'category_id'=>'cc.id', 'category_code'=>'cc.code', 'model_id'=>'cm.id', 'model_code'=>'cm.code', 'c.unique_id', 'cm.down_money', 'cm.min_charge_money', 'cm.begin_time', 'cm.end_time', 'customer_id'=>'u.id', 'customer_unique_id'=>'u.unique_id', 'customer_nickname'=>'u.nickname', 'c.add_time', 'c.status']);
		$this->table(['c'=>'coupons'])->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id'])->join(['u'=>'customers', 'c.customer_id'=>'u.id']);
		$this->order(['c.id'=>'desc'])->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_unused_num(void)
	 */
	public function get_unused_num(): int {
		$ends = $this->field(['coupon_num'=>'count(*)'])->table(['coupons'])->where(['status'=>'unused'])->select();
		return $ends[0]['coupon_num'] ?? -1;
	}
	
	/**
	 * public array function get_unused_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_unused_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['c.id', 'category_id'=>'cc.id', 'category_code'=>'cc.code', 'model_id'=>'cm.id', 'model_code'=>'cm.code', 'c.unique_id', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'customer_id'=>'u.id', 'customer_unique_id'=>'u.unique_id', 'customer_nickname'=>'u.nickname', 'c.add_time', 'c.status']);
		$this->table(['c'=>'coupons'])->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id'])->join(['u'=>'customers', 'c.customer_id'=>'u.id']);
		$this->where(['c.status'=>'unused'])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_used_num(void)
	 */
	public function get_used_num(): int {
		$ends = $this->field(['coupon_num'=>'count(*)'])->table(['coupons'])->where(['status'=>'used'])->select();
		return $ends[0]['coupon_num'] ?? -1;
	}
	
	/**
	 * public array function get_used_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_used_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['c.id', 'category_id'=>'cc.id', 'category_code'=>'cc.code', 'model_id'=>'cm.id', 'model_code'=>'cm.code', 'c.unique_id', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'customer_id'=>'u.id', 'customer_unique_id'=>'u.unique_id', 'customer_nickname'=>'u.nickname', 'order_id'=>'o.id', 'order_unique_id'=>'o.unique_id', 'c.use_time', 'c.add_time', 'c.status']);
		$this->table(['c'=>'coupons'])->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id'])->join(['u'=>'customers', 'c.customer_id'=>'u.id']);
		$this->join(['o'=>'orders', 'c.order_id'=>'o.id']);
		$this->where(['c.status'=>'used'])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_expired_num(void)
	 */
	public function get_expired_num(): int {
		$ends = $this->field(['coupon_num'=>'count(*)'])->table(['coupons'])->where(['status'=>'expired'])->select();
		return $ends[0]['coupon_num'] ?? -1;
	}
	
	/**
	 * public array function get_expired_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_expired_page(int $page_num = 1, $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['c.id', 'category_id'=>'cc.id', 'category_code'=>'cc.code', 'model_id'=>'cm.id', 'model_code'=>'cm.code', 'c.unique_id', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'customer_id'=>'u.id', 'customer_unique_id'=>'u.unique_id', 'customer_nickname'=>'u.nickname', 'c.add_time', 'c.status']);
		$this->table(['c'=>'coupons'])->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id'])->join(['u'=>'customers', 'c.customer_id'=>'u.id']);
		$this->where(['c.status'=>'expired'])->order(['c.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array function record(integer $coupon_id)
	 */
	public function record(int $coupon_id): array {
		$this->field(['c.id', 'category_id'=>'cc.id', 'category_code'=>'cc.code', 'model_id'=>'cm.id', 'mode_code'=>'cm.code', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'c.unique_id', 'customer_id'=>'u.id', 'customer_unique_id'=>'u.unique_id', 'customer_openid'=>'u.open_id', 'customer_nickname'=>'u.nickname', 'order_id'=>'o.id', 'order_unique_id'=>'o.unique_id', 'c.use_time', 'c.add_time', 'c.status']);
		$this->table(['c'=>'coupons'])->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id'])->join(['u'=>'customers', 'c.customer_id'=>'u.id']);
		$this->join(['o'=>'orders', 'c.order_id'=>'o.id'], 'left');
		$ends = $this->where(['c.id'=>$coupon_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array function find(string $unique_id)
	 */
	public function find(string $unique_id): array {
		$this->field(['c.id', 'category_id'=>'cc.id', 'category_code'=>'cc.code', 'model_id'=>'cm.id', 'model_code'=>'cm.code', 'c.unique_id', 'cm.tag_money', 'cm.min_charge', 'cm.begin_time', 'cm.end_time', 'customer_id'=>'u.id', 'customer_unique_id'=>'u.unique_id', 'customer_nickname'=>'u.nickname', 'c.add_time', 'c.status']);
		$this->table(['c'=>'coupons'])->join(['cm'=>'coupon_models', 'c.model_id'=>'cm.id']);
		$this->join(['cc'=>'coupon_categories', 'cm.category_id'=>'cc.id'])->join(['u'=>'customers', 'c.customer_id'=>'u.id']);
		$this->where(['c.unique_id'=>$unique_id]);
		return $this->select();
	}
	
	/**
	 * protected array function get_model_coupon_nums(void)
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
	 * protected array function get_model_used_coupon_nums(void)
	 */
	protected function get_model_used_coupon_nums(): array {
		$this->field(['model_id', 'coupon_num'=>'count(*)'])->table(['coupons']);
		$datas = $this->where(['status'=>'used'])->group(['model_id'])->order(['model_id'=>'asc'])->select();
		foreach($datas as $data){
			$ends[$data['model_id']] = $data['coupon_num'];
		}
		return $ends ?? [];
	}
	
	//
}










