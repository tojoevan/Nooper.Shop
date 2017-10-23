<?php
// declare(strict_types = 1);
namespace Nooper;

class GiftCard extends Mysql {
	
	/**
	 * public integer function get_model_num(void)
	 */
	public function get_model_num(): int {
		$ends = $this->field(['model_num'=>'count(*)'])->table(['gift_card_models'])->where(['status'=>"'deleted'"],'neq')->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public integer function get_deleted_model_num(void)
	 */
	public function get_deleted_model_num(): int {
		$ends = $this->field(['deleted_model_num'=>'count(*)'])->table(['gift_card_models'])->where(['status'=>"'deleted'"])->select();
		return $ends[0]['deleted_model_num'] ?? -1;
	}
	
	/**
	 * public array function get_model_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_model_page(int $page_num = 1, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['gcm.id', 'gcm.code', 'gcm.name', 'gcm.recharge_money', 'gcm.sale_price', 'gift_card_num'=>'count(gc.id)', 'recharged_gift_card_num'=>'count(gcrr.id)', 'gcm.add_time', 'gcm.status']);
		$this->table(['gcm'=>'gift_card_models'])->join(['gc'=>'gift_cards', 'gcm.id'=>'gc.model_id'], 'left');
		$this->join(['gcrr'=>'gift_card_recharge_records', 'gc.id'=>'gcrr.gift_card_id'], 'left');
		$this->where(['gc.status'=>"'deleted'"], 'neq')->group(['gcm.id'])->order(['gcm.id'=>'desc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_deleted_model_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_deleted_model_page(int $page_num = 1, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['gcm.id', 'gcm.code', 'gcm.name', 'gcm.recharge_money', 'gcm.sale_price', 'gift_card_num'=>'count(gc.id)', 'recharged_gift_card_num'=>'count(gcrr.id)', 'gcm.add_time', 'gcm.status']);
		$this->table(['gcm'=>'gift_card_models'])->join(['gc'=>'gift_cards', 'gcm.id'=>'gc.model_id'], 'left');
		$this->join(['gcrr'=>'gift_card_recharge_records', 'gc.id'=>'gcrr.gift_card_id'], 'left');
		$this->where(['gcm.status'=>"'deleted'"])->group(['gcm.id'])->order(['gcm.id'=>'desc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_model_record(integer $model_id)
	 */
	public function get_model_record(int $model_id): array {
		$this->field(['gcm.id', 'gcm.code', 'gcm.name', 'gcm.recharge_money', 'gcm.sale_price', 'gift_card_num'=>'count(gc.id)', 'recharged_gift_card_num'=>'count(gcrr.id)', 'gcm.add_time', 'gcm.status']);
		$this->table(['gcm'=>'gift_card_models'])->join(['gc'=>'gift_cards', 'gcm.id'=>'gc.model_id'], 'left');
		$this->join(['gcrr'=>'gift_card_recharge_records', 'gc.id'=>'gcrr.gift_card_id'], 'left');
		$this->where(['gcm.id'=>(string)$model_id]);
		return $this->select();
	}
	
	/**
	 * public boolean function modify_model(integer $model_id, array $datas)
	 */
	public function modify_model(int $model_id, array $datas): bool {
		$end = $this->table(['gift_card_models'])->where(['id'=>(string)$model_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean function delete_model(integer $model_id)
	 */
	public function delete_model(int $model_id): bool {
		$ends = $this->field(['gift_card_num'=>'count(*)'])->table(['gift_cards'])->where(['id'=>(string)$model_id])->select();
		if(isset($ends[0]) && $ends[0]['giftcard_num'] > 0){
			$datas = ['status'=>'deleted'];
			$end = $this->table(['gift_card_models'])->where(['id'=>(string)$model_id])->modify($datas);
		}else
			$end = $this->table(['gift_card_models'])->where(['id'=>(string)$model_id])->delete();
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean function recover_model(integer $model_id)
	 */
	public function recover_model(int $model_id): bool {
		$status = ['status'=>'normal'];
		$end = $this->table(['gift_card_models'])->where(['id'=>(string)$model_id])->modify($status);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer function add_model(array $datas)
	 */
	public function add_model(array $datas): int {
		$end = $this->table(['gift_card_models'])->add($datas);
		return $end > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * public integer function num(void)
	 */
	public function num(): int {
		$ends = $this->field(['num'=>'count(*)'])->table(['gift_cards'])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public integer function get_unpaid_num(void)
	 */
	public function get_unpaid_num(): int {
		$ends = $this->field(['unpaid_num'=>'count(*)'])->table(['gift_cards'])->where(['status'=>"'unpaid'"])->select();
		return $ends[0]['unpaid_num'] ?? -1;
	}
	
	/**
	 * public integer function get_paid_num(void)
	 */
	public function get_paid_num(): int {
		$ends = $this->field(['unpaid_num'=>'count(*)'])->table(['gift_cards'])->where(['status'=>"'paid'"])->select();
		return $ends[0]['paid_num'] ?? -1;
	}
	
	/**
	 * public integer function get_recharged_num(void)
	 */
	public function get_recharged_num(): int {
		$ends = $this->field(['recharged_num'=>'count(*)'])->table(['gift_cards'])->where(['status'=>"'recharged'"])->select();
		return $ends[0]['recharged_num'] ?? -1;
	}
	
	/**
	 * public integer function get_num_by_model_id(int $model_id)
	 */
	public function get_num_by_model_id(int $model_id): int {
		$ends = $this->field(['gift_card_num'=>'count(*)'])->table(['gift_cards'])->where(['model_id'=>(string)$model_id])->select();
		return $ends[0]['gift_card_num'] ?? -1;
	}
	
	/**
	 * public array function page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function page(int $page_num = 1, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'customer_id'=>'c.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.code', 'gc.wx_transaction_id', 'c.wx_open_id', 'gcm.recharge_money', 'gcm.sale_price', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->join(['c'=>'customers', 'gc.customer_id'=>'c.id'])->order(['gc.id'=>'desc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_unpaid_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_unpaid_page(int $page_num = 1, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'customer_id'=>'c.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.code', 'gc.wx_transaction_id', 'c.wx_open_id', 'gcm.recharge_money', 'gcm.sale_price', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->join(['c'=>'customers', 'gc.customer_id'=>'c.id'])->where(['gc.status'=>"'unpaid'"])->order(['gc.id'=>'desc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_paid_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_paid_page(int $page_num = 1, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'customer_id'=>'c.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.code', 'gc.wx_transaction_id', 'c.wx_open_id', 'gcm.recharge_money', 'gcm.sale_price', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->join(['c'=>'customers', 'gc.customer_id'=>'c.id'])->where(['gc.status'=>"'paid'"])->order(['gc.id'=>'desc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_recharged_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_recharged_page(int $page_num = 1, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'customer_id'=>'c.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.code', 'gc.wx_transaction_id', 'c.wx_open_id', 'gcm.recharge_money', 'gcm.sale_price', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->join(['c'=>'customers', 'gc.customer_id'=>'c.id'])->where(['gc.status'=>"'rercharged'"])->order(['gc.id'=>'desc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function record(integer $gift_card_id)
	 */
	public function record(int $gift_card_id): array {
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'customer_id'=>'c.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.code', 'gc.wx_transaction_id', 'c.wx_open_id', 'gcm.recharge_money', 'gcm.sale_price', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->join(['c'=>'customers', 'gc.customer_id'=>'c.id'])->where(['gc.id'=>(string)$gift_card_id]);
		return $this->select();
	}
	
	/**
	 * public array function find(string $gift_card_unique_id)
	 */
	public function find(string $gift_card_unique_id): array {
		$this->field(['gc.id', 'model_id'=>'gcm.id', 'customer_id'=>'c.id', 'model_code'=>'gcm.code', 'gc.unique_id', 'gc.code', 'gc.wx_transaction_id', 'c.wx_open_id', 'gcm.recharge_money', 'gcm.sale_price', 'gc.add_time', 'gc.status']);
		$this->table(['gc'=>'gift_cards'])->join(['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id']);
		$this->join(['c'=>'customers', 'gc.customer_id'=>'c.id'])->where(['gc.unique_id'=>"'$gift_card_unique_id'"]);
		return $this->select();
	}
	
	//
}











