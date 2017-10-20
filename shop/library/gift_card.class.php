<?php
// declare(strict_types = 1);
namespace Nooper;

class GiftCard extends Mysql {
	
	/**
	 * public integer function get_model_num(void)
	 */
	public function get_model_num(): int {
		// status!='deleted'
	}
	
	/**
	 * public integer function get_deleted_model_num(void)
	 */
	public function get_deleted_model_num(): int {
		//
	}
	
	/**
	 * public array function get_model_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_model_page(int $page_num = 1, int $page_length = 20): array {
		//gfm.id
		// gfm.code
		// gfm.name
		// gfm.recharge_money
		// gfm.sale_price
		// gift_card_num=>?
		// recharged_gift_card_num=>?
		// gfm.add_time
		// gfm.status
		//
		// order(gfm.id)=>'desc'
	}
	
	/**
	 * public array function get_deleted_model_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_deleted_model_page(int $page_num = 1, int $page_length = 20): array {
		//
	}
	
	/**
	 * public array function get_model_record(integer $model_id)
	 */
	public function get_model_record(int $model_id): array {
		//
	}
	
	/**
	 * public boolean function modify_model(integer $model_id, array $datas)
	 */
	public function modify_model(int $model_id, array $datas): bool {
		//
	}
	
	/**
	 * public boolean function delete_model(integer $model_id)
	 */
	public function delete_model(int $model_id): bool {
		//
	}
	
	/**
	 * public boolean function recover_model(integer $model_id)
	 */
	public function recover_model(int $model_id): bool {
		//
	}
	
	/**
	 * public integer function add_model(array $datas)
	 */
	public function add_model(array $datas): int {
		// error, return -1
	}
	
	/**
	 * public integer function num(void)
	 */
	public function num(): int {
		//
	}
	
	/**
	 * public integer function get_normal_num(void)
	 */
	public function get_normal_num(): int {
		//
	}
	
	/**
	 * public integer function get_recharged_num(void)
	 */
	public function get_recharged_num(): int {
		//
	}
	
	/**
	 * public array page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function page(int $page_num = 1, int $page_length = 20): array {
		//
	}
	
	/**
	 * public array get_normal_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_normal_page(int $page_num = 1, int $page_length = 20): array {
		//
	}
	
	/**
	 * public array get_recharged_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_recharged_page(int $page_num = 1, int $page_length = 20): array {
		//
	}
	
	/**
	 * public array record(integer $gift_card_id)
	 */
	public function record(int $gift_card_id): array {
		//
	}
	
	/**
	 * public array find(string $gift_card_unique_id)
	 */
	public function find(string $gift_card_unique_id): array {
		//
	}
	
	/* ********************************************************************* */
	
	/**
	 * public integer function get_models_num(void)
	 */
	public function get_models_num(): int {
		$ends = $this->field(['model_num'=>'count(*)'])->table(['gift_card_models'])->select();
		return $ends[0]['model_num'] ?? -1;
	}
	
	/**
	 * public array function get_models(integer $page_num, integer $page_length = 20)
	 */
	public function get_models(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$field_datas = ['id', 'code', 'name', 'recharge_price', 'sale_price', 'quantity'];
		$ends = $this->field($field_datas)->table(['gift_card_models'])->order(['id'=>'desc'])->limit($page_length, $offset_num)->select();
		
		if($ends){
			$all_nums = $this->get_nums_by_model_id();
			$sold_nums = $this->get_sold_nums_by_model_id();
			$recharged_nums = $this->get_recharged_nums_by_model_id();
			$normal_nums = $this->get_normal_nums_by_model_id();
			foreach($ends as &$data){
				$id = $data['id'];
				$data['all_qty'] = $all_nums[$id] ?? 0;
				$data['sold_qty'] = $sold_nums[$id] ?? 0;
				$data['recharged_qty'] = $recharged_nums[$id] ?? 0;
				$data['normal_qty'] = $normal_nums[$id] ?? 0;
			}
		}
		
		return $ends;
	}
	
	/**
	 * public boolean function delete_model(integer $model_id)
	 */
	public function delete_model(int $model_id): bool {
		$datas = $this->field(['num'=>'count(*)'])->table(['gift_cards'])->where_cmd('`model_id`=' . $model_id . " and `status` in('sold','recharged')")->select();
		if(isset($datas[0])){
			$num = $datas[0]['num'];
			if($num > 0){
				$end = $this->table(['gift_cards'])->where(['model_id'=>$model_id, 'status'=>"'normal'"])->delete();
				echo $end;
				die();
				return $end >= 0 ? true : false;
			}else{
				$this->begin();
				$end1 = $this->table(['gift_cards'])->where(['model_id'=>$model_id])->delete();
				$end2 = $this->table(['gift_card_models'])->where(['id'=>$model_id])->delete();
				if($end1 > 0 && $end2 > 0){
					$this->end();
					return true;
				}else{
					$this->rollback();
					return false;
				}
			}
		}
		return false;
	}
	
	/**
	 * public integer function get_num(void)
	 */
	public function get_num(): int {
		$ends = $this->field(['num'=>'count(*)'])->table(['gift_cards'])->select();
		return $ends[0]['num'] ?? 0;
	}
	
	/**
	 * public array function page(integer $page_num, integer $page_length = 20)
	 */
	public function page(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$field_datas = ['gc.id', 'gcm_id'=>'gcm.id', 'gcsr_id'=>'gcsr.id', 'gcrr_id'=>'gcrr.id', 'gcm_code'=>'gcm.code', 'gc.code', 'gcm.recharge_price', 'gcm.sale_price', 'gc.add_time', 'gcsr_add_time'=>'gcsr.add_time', 'gcrr_add_time'=>'gcrr.add_time', 'gc.status'];
		$memory_datas = ['gc'=>'gift_cards'];
		$plus1_datas = ['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id'];
		$plus2_datas = ['gcsr'=>'gift_card_sale_records', 'gc.id'=>'gcsr.gift_card_id'];
		$plus3_datas = ['gcrr'=>'gift_card_recharge_records', 'gc.id'=>'gcrr.gift_card_id'];
		$order_datas = ['gc.id'=>'desc'];
		return $this->field($field_datas)->table($memory_datas)->join($plus1_datas)->join($plus2_datas, 'left')->join($plus3_datas, 'left')->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	
	/**
	 * public integer function get_sold_num(void)
	 */
	public function get_sold_num(): int {
		$ends = $this->field(['sold_num'=>'count(*)'])->table(['gift_cards'])->where(['status'=>"'sold'"])->select();
		return $ends[0]['num'] ?? 0;
	}
	
	/**
	 * public array function get_sale_records(integer $page_num, integer $page_length = 20)
	 */
	public function get_sale_records(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$field_datas = ['gcsr_id'=>'gcsr.id', 'gc_id'=>'gc.id', 'gcm_id'=>'gcm.id', 'u_id'=>'u.id', 'gcm_code'=>'gcm.code', 'gc.code', 'gcm.recharge_price', 'gcm.sale_price', 'gc.add_time', 'u_unique_id'=>'u.unique_id', 'u_nickname'=>'u.nickname', 'gcsr_add_time'=>'gcsr.add_time'];
		$memory_datas = ['gcsr'=>'gift_card_sale_records'];
		$plus_datas = ['gc'=>'gift_cards', 'gcsr.gift_card_id'=>'gc.id'];
		$plus2_datas = ['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id'];
		$plus3_datas = ['u'=>'customers', 'gcsr.customer_id'=>'u.id'];
		$order_datas = ['gcrr.id'=>'desc'];
		return $this->field($field_datas)->table($memory_datas)->join($plus_datas)->join($plus2_datas)->join($plus3_datas)->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	
	/**
	 * public integer function get_recharged_num(void)
	 */
	public function get_recharged_num(): int {
		$ends = $this->field(['recharged_num'=>'count(*)'])->table(['gift_cards'])->where(['status'=>"'recharged'"])->select();
		return $ends[0]['num'] ?? 0;
	}
	
	/**
	 * public array function get_recharge_records(integer $page_num, integer $page_length = 20)
	 */
	public function get_recharge_records(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$field_datas = ['gcrr_id'=>'gcrr.id', 'gc_id'=>'gc.id', 'gcm_id'=>'gcm.id', 'u_id'=>'u.id', 'gcm_code'=>'gcm.code', 'gc.code', 'gcm.recharge_price', 'gcm.sale_price', 'gc.add_time', 'u_unique_id'=>'u.unique_id', 'u_nickname'=>'u.nickname', 'gcrr_add_time'=>'gcrr.add_time'];
		$memory_datas = ['gcrr'=>'gift_card_recharge_records'];
		$plus_datas = ['gc'=>'gift_cards', 'gcrr.gift_card_id'=>'gc.id'];
		$plus2_datas = ['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id'];
		$plus3_datas = ['u'=>'customers', 'gcrr.customer_id'=>'u.id'];
		$order_datas = ['gcrr.id'=>'desc'];
		return $this->field($field_datas)->table($memory_datas)->join($plus_datas)->join($plus2_datas)->join($plus3_datas)->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	
	/**
	 * public integer function get_normal_num(void)
	 */
	public function get_normal_num(): array {
		$ends = $this->field(['normal_num'=>'count(*)'])->table(['gift_cards'])->where(['status'=>"'normal'"])->select();
		return $ends[0]['num'] ?? 0;
	}
	
	/**
	 * public array function find(string $keyword, integer $page_num, integer $page_length = 20)
	 */
	public function find(string $keyword, int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$field_datas = ['gc.id', 'gcm_id'=>'gcm.id', 'gcm_code'=>'gcm.code', 'gc.code', 'gcm.recharge_price', 'gcm.sale_price', 'gc.add_time', 'gcsr_id'=>'gcsr.id', 'gcsr_add_time'=>'gcsr.add_time', 'gcrr_id'=>'gcrr.id', 'gcrr_add_time'=>'gcrr.add_time', 'gc.status'];
		$memory_datas = ['gc'=>'gift_cards'];
		$plus1_datas = ['gcm'=>'gift_card_models', 'gc.model_id'=>'gcm.id'];
		$plus2_datas = ['gcsr'=>'gift_card_sale_records', 'gc.id'=>'gcsr.gift_card_id'];
		$plus3_datas = ['gcrr'=>'gift_card_recharge_records', 'gc.id'=>'gcrr.gift_card_id'];
		$where_str = "`gc`.`code` regexp '" . $keyword . "'  and `gc`.`status`!='deleted'";
		$order_datas = ['gc.id'=>'desc'];
		return $this->field($field_datas)->table($memory_datas)->join($plus1_datas)->join($plus2_datas, 'left')->join($plus3_datas, 'left')->where_cmd($where_str)->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	
	/**
	 * protected array function get_nums_by_model_id(void)
	 */
	protected function get_nums_by_model_id(): array {
		$datas = $this->field(['model_id', 'num'=>'count(*)'])->table(['gift_cards'])->group(['model_id'])->order(['model_id'=>'desc'])->select();
		foreach($datas as $data){
			list('model_id'=>$id, 'num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	/**
	 * protected array function get_sold_nums_by_model_id(void)
	 */
	protected function get_sold_nums_by_model_id(): array {
		$datas = $this->field(['model_id', 'sold_num'=>'count(*)'])->table(['gift_cards'])->where_cmd("`status` in('sold','recharged')")->group(['model_id'])->order(['model_id'=>'desc'])->select();
		foreach($datas as $data){
			list('model_id'=>$id, 'sold_num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	/**
	 * protected array function get_recharged_nums_by_model_id(void)
	 */
	protected function get_recharged_nums_by_model_id(): array {
		$datas = $this->field(['model_id', 'recharged_num'=>'count(*)'])->table(['gift_cards'])->where(['status'=>"'recharged'"])->group(['model_id'])->order(['model_id'=>'desc'])->select();
		foreach($datas as $data){
			list('model_id'=>$id, 'recharged_num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	/**
	 * protected array function get_normal_nums_by_model_id(void)
	 */
	public function get_normal_nums_by_model_id(): array {
		$datas = $this->field(['model_id', 'normal_num'=>'count(*)'])->table(['gift_cards'])->where(['status'=>"'normal'"])->group(['model_id'])->order(['model_id'=>'desc'])->select();
		foreach($datas as $data){
			list('model_id'=>$id, 'normal_num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	//
}











