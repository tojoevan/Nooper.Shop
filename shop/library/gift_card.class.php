<?php

// declare(strict_types = 1);
namespace NooperShop;

use Nooper\Mysql;

class GiftCard extends Mysql {
	
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
			$unique_id = $unique->gift_card();
			$datas = $this->field(['num'=>'COUNT(*)'])->table(['gift_cards'])->where(['unique_id'=>$unique_id])->select();
			if(isset($datas[0]) && $datas[0]['num'] > 0) continue;
			break;
		}while(true);
		return $unique_id;
	}
	
	/**
	 * public integer get_model_num(void)
	 */
	public function get_model_num(): int {
		$ends = $this->field(['model_num'=>'COUNT(*)'])->table(['gift_card_models'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public integer get_normal_model_num(void)
	 */
	public function get_normal_model_num(): int {
		$ends = $this->field(['model_num'=>'COUNT(*)'])->table(['gift_card_models'])->where(['status'=>'normal'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public array get_normal_model_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_normal_model_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_model_view()->where(['gcm.status'=>'normal'])->group(['gcm.id'])->order(['gcm.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_deleted_model_num(void)
	 */
	public function get_deleted_model_num(): int {
		$ends = $this->field(['model_num'=>'COUNT(*)'])->table(['gift_card_models'])->where(['status'=>'deleted'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public array get_deleted_model_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_deleted_model_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_model_view()->where(['gcm.status'=>'deleted'])->group(['gcm.id'])->order(['gcm.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_model_record(integer $model_id)
	 */
	public function get_model_record(int $model_id): array {
		$ends = $this->_model_view()->where(['gcm.id'=>$model_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean recover_model(integer $model_id)
	 */
	public function recover_model(int $model_id): bool {
		$end = $this->table(['gift_card_models'])->where(['id'=>$model_id])->modify(['status'=>'normal']);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer delete_model(integer $model_id)
	 */
	public function delete_model(int $model_id): int {
		$datas = $this->field(['gift_card_num'=>'COUNT(*)'])->table(['gift_cards'])->where(['id'=>$model_id])->select();
		if(isset($datas[0])){
			$this->table(['gift_card_models'])->where(['id'=>$model_id]);
			if($datas[0]['gift_card_num'] > 0) return $this->modify(['status'=>'deleted']) > 0 ? 2 : -1;
			else return $this->delete() > 0 ? 1 : -1;
		}
		return -1;
	}
	
	/**
	 * public boolean edit_model(integer $model_id, array $datas)
	 * @$datas = [string $code, string $name]
	 */
	public function edit_model(int $model_id, array $datas): bool {
		$end = $this->table(['gift_card_models'])->where(['id'=>$model_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public ?integer add_model(array $datas)
	 * @$datas = [string $code, string $name, float $recharge_money, float $sale_price]
	 */
	public function add_model(array $datas): ?int {
		$end = $this->table(['gift_card_models'])->add($datas);
		return $end > 0 ? $this->get_last_id() : $end;
	}
	
	/**
	 * public integer num(void)
	 */
	public function num(): int {
		$ends = $this->field(['gift_card_num'=>'COUNT(*)'])->table(['gift_cards'])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public integer get_unpaid_num(void)
	 */
	public function get_unpaid_num(): int {
		$ends = $this->field(['gift_card_num'=>'COUNT(*)'])->table(['gift_cards'])->where(['status'=>'unpaid'])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public array get_unpaid_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_unpaid_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_view()->where(['gc.status'=>'unpaid'])->order(['gc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_paid_num(void)
	 */
	public function get_paid_num(): int {
		$ends = $this->field(['gift_card_num'=>'COUNT(*)'])->table(['gift_cards'])->where(['status'=>'paid'])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public array get_paid_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_paid_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_view()->where(['gc.status'=>'paid'])->order(['gc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_charged_num(void)
	 */
	public function get_charged_num(): int {
		$ends = $this->field(['gift_card_num'=>'COUNT(*)'])->table(['gift_cards'])->where(['status'=>'recharged'])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public array get_charged_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_charged_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_view()->where(['gc.status'=>'charged'])->order(['gc.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array record(integer $gift_card_id)
	 */
	public function record(int $gift_card_id): array {
		$ends = $this->_view()->where(['gc.id'=>$gift_card_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array find(string $gift_card_unique_id)
	 */
	public function find(string $gift_card_unique_id): array {
		$ends = $this->_view()->where(['gc.unique_id'=>$gift_card_unique_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean pay(integer $gift_card_id, string $transaction_id, integer $pay_time)
	 */
	public function pay(int $gift_card_id, string $transaction_id, int $pay_time): bool {
		$datas = ['transaction_id'=>$transaction_id, 'pay_time'=>$pay_time, 'status'=>'paid'];
		$end = $this->table(['gift_cards'])->where(['id'=>$gift_card_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean charge(integer $gift_card_id)
	 */
	public function charge(int $gift_card_id): bool {
		$this->field(['gc.customer_id', 'gcm.charge_money'])->table(['gc'=>'gift_cards']);
		$this->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$datas = $this->where(['gc.id'=>$id])->select();
		if(isset($datas[0]) && $this->begin()){
			list('customer_id'=>$customer_id, 'charge_money'=>$charge_money) = $datas[0];
			$end1 = $this->table(['gift_cards'])->where(['id'=>$gift_card_id])->modify(['charge_time'=>get_now_timestamp(), 'status'=>'charged']);
			$end2 = $this->table(['customers'])->where(['id'=>$customer_id])->modify(['balance'=>['balance+' . $charge_money]]);
			if($end1 > 0 && $end2 > 0 && $this->end()) return true;
			$this->rollback();
			return false;
		}
		return false;
	}
	
	/**
	 * public integer remove(integer $gift_card_id)
	 */
	public function remove(int $gift_card_id): int {
		$datas = $this->field(['status'])->table(['gift_cards'])->where(['id'=>$gift_card_id])->select();
		if(isset($datas[0])){
			if('unpaid' == $datas[0]['status']) return $this->table(['gift_cards'])->where(['id'=>$gift_card_id])->delete();
			return -2; // error for status, @@
		}
		return -1;
	}
	
	/**
	 * public ?integer create(array $datas)
	 * @$datas = [string $unique_id, integer $model_id, integer $customer_id, string $code]
	 */
	public function create(array $datas): ?int {
		return $this->table(['gift_cards'])->add($datas) > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * protected GiftCard _model_view(void)
	 */
	protected function _model_view(): GiftCard {
		$gcm_cols = ['gcm.id', 'gcm.code', 'gcm.name', 'gcm.charge_money', 'gcm.sale_price', 'gcm.add_time', 'gcm.status'];
		$func_cols = ['gift_card_num'=>'COUNT(`gc`.`id`)'];
		$this->field(array_merge($gcm_cols, $func_cols))->table(['gcm'=>'gift_card_models']);
		$this->join(['gc'=>'gift_cards', 'gcm.id'=>'gc.model_id'], 'left');
		return $this;
	}
	
	/**
	 * protected GiftCard _view(void)
	 */
	protected function _view(): GiftCard {
		$gc_cols = ['gc.id', 'gc.unique_id', 'gc.code', 'gc.transaction_id', 'gc.pay_time', 'gc.charge_time', 'gc.add_time', 'gc.status'];
		$gcm_cols = ['model_id'=>'gcm.id', 'model_code'=>'gcm.code', 'gcm.charge_money', 'gcm.sale_price'];
		$c_cols = ['customer_id'=>'c.id', 'customer_unique_id'=>'c.unique_id'];
		$this->field(array_merge($gc_cols, $gcm_cols, $c_cols))->table(['gc'=>'gift_cards']);
		$this->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->join(['c'=>'customers', 'gc.customer_id'=>'c.id']);
		return $this;
	}
	
	//
}











