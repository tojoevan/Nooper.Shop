<?php
// declare(strict_types = 1);
namespace Nooper;

class Express extends Mysql {
	
	/**
	 * public integer function get_corporation_num(void)
	 */
	public function get_corporation_num(): int {
		$ends = $this->field(['corporation_num'=>'count(*)'])->table(['express_corporations'])->where_cmd("`status`!='deleted'")->select();
		return isset($ends[0]) ? $ends[0]['corporation_num'] : -1;
	}
	
	/**
	 * public integer function get_deleted_corporation_num(void)
	 */
	public function get_deleted_corporation_num(): int {
		$ends = $this->field(['deleted_corporation_num'=>'count(*)'])->table(['express_corporations'])->where(['status'=>"'deleted'"])->select();
		return isset($ends[0]) ? $ends[0]['deleted_corporation_num'] : -1;
	}
	
	/**
	 * public array function get_corporation_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_corporation_page(int $page_num = 1, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['ec.id', 'ec.code', 'ec.name', 'ec.query_api', 'express_num'=>'count(`e`.`id`)', 'ec.is_default', 'ec.position', 'ec.add_time', 'ec.status']);
		$this->table(['express_corporations'])->join(['e'=>'expresses', 'ec.id'=>'e.corporation_id'], 'left')->where_cmd("`status`!='deleted'");
		$this->group(['ec.id'=>'asc'])->order(['ec.is_default'=>'desc', 'ec.position'=>'desc', 'ec.id'=>'desc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_deleted_corporation_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_deleted_corporation_page(int $page_num = 1, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['ec.id', 'ec.code', 'ec.name', 'ec.query_api', 'express_num'=>'count(`e`.`id`)', 'ec.is_default', 'ec.position', 'ec.add_time', 'ec.status']);
		$this->table(['express_corporations'])->join(['e'=>'expresses', 'ec.id'=>'e.corporation_id'], 'left')->where(['ec.status'=>"'deleted'"]);
		$this->group(['ec.id'=>'asc'])->order(['ec.is_default'=>'desc', 'ec.position'=>'desc', 'ec.id'=>'desc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_corporation_list(void)
	 */
	public function get_corporation_list(): array {
		$this - field(['id', 'code', 'name'])->table(['express_corporations'])->where_cmd("`status`!='deleted'");
		$this->order(['is_default'=>'desc', 'position'=>'desc', 'id'=>'desc']);
		return $this->select();
	}
	
	/**
	 * public array function get_corporation_record(integer $corporation_id)
	 */
	public function get_corporation_record(int $corporation_id): array {
		$this->field(['ec.id', 'ec.code', 'ec.name', 'ec.query_api', 'express_num'=>'count(`e`.`id`)', 'ec.is_default', 'ec.position', 'ec.add_time', 'ec.status']);
		$this->table(['express_corporations'])->join(['e'=>'expresses', 'ec.id'=>'e.corporation_id'], 'left');
		$this->where(['ec.id'=>(string)$corporation_id])->group(['ec.id'=>'asc']);
		return $this->select();
	}
	
	/**
	 * public boolean function modify_corporation(integer $corporation_id, array $datas)
	 */
	public function modify_corporation(int $corporation_id, array $datas): bool {
		$end = $this->table(['express_corporations'])->where(['id'=>(string)$corporation_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean function delete_corporation(integer $corporation_id)
	 */
	public function delete_corporation(int $corporation_id): bool {
		$datas = $this->field(['express_num'=>'count(*)'])->table(['expresses'])->where(['corporation_id'=>(string)$corporation_id]);
		if(isset($datas[0])){
			$express_num = $datas[0]['express_num'];
			if($express_num > 0){
				$datas = ['status'=>'deleted'];
				$end = $this->table(['express_corporations'])->where(['id'=>(string)$corporation_id])->modify($datas);
			}else
				$end = $this->table(['express_corporations'])->where(['id'=>(string)$corporation_id])->delete();
			return $end > 0 ? true : false;
		}
		return false;
	}
	
	/**
	 * public boolean function recover_corporation(integer $corporation_id)
	 */
	public function recover_corporation(int $corporation_id): bool {
		$datas = ['status'=>'normal'];
		$end = $this->table(['express_corporations'])->where(['id'=>(string)$corporation_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer function add_corporation(array $datas)
	 */
	public function add_corporation(array $datas): int {
		$end = $this->table(['express_corporations'])->add($datas);
		return $end > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * public array function get_page_by_corporation_id(integer $corporation_id)
	 */
	public function get_page_by_corporation_id(int $corporation_id): array {
		//
	}
	//
}










