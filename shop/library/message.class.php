<?php
// declare(strict_types = 1);
namespace NooperShop;

use Nooper\Mysql;

class Message extends Mysql {
	
	/**
	 * Const
	 */
	const page_record_num = 20;
	
	/**
	 * public array function get_default_params(void)
	 */
	public function get_default_params(): array {
		$ends = $this->field(['auto_clear_switch'])->table(['message_default_params'])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public void function open_system_clear(void)
	 */
	public function open_system_clear(): void {
		$sql = 'alter event delete_expired_message on completion preserve enable';
		$end = $this->cmd($sql);
	}
	
	/**
	 * public void function close_system_clear(void)
	 */
	public function close_system_clear(): void {
		$sql = 'alter event delete_expired_message on completion preserve disable';
		$end = $this->cmd($sql);
	}
	
	/**
	 * public integer function get_category_num(void)
	 */
	public function get_category_num(): int {
		$ends = $this->field(['category_num'=>'count(*)'])->table(['message_categories'])->select();
		return $ends[0]['category_num'] ?? -1;
	}
	
	/**
	 * public array function get_category_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_category_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['mc.id', 'mc.code', 'mc.name', 'message_num'=>'count(`m`.`id`)', 'mc.add_time']);
		$this->table(['mc'=>'message_categories'])->join(['m'=>'messages', 'mc.id'=>'m.category_id'], 'left')->group(['mc.id'])->order(['mc.id'=>'asc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array function get_category_list(void)
	 */
	public function get_category_list(): array {
		return $this->field(['id', 'code', 'name'])->table(['message_categories'])->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array function get_category_record(integer $category_id)
	 */
	public function get_category_record(int $category_id): array {
		$this->field(['mc.id', 'mc.code', 'mc.name', 'message_num'=>'count(`m`.`id`)', 'mc.add_time']);
		$this->table(['mc'=>'message_categories'])->join(['m'=>'messages', 'mc.id'=>'m.category_id'], 'left');
		$ends = $this->where(['mc.id'=>$category_id])->group(['mc.id'])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public integer function num(void)
	 */
	public function num(): int {
		$ends = $this->field(['num'=>'count(*)'])->table(['messages'])->select();
		return $ends[0]['num'] ?? -1;
	}
	
	/**
	 * public array function page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['m.id', 'category_id'=>'mc.id', 'category_code'=>'mc.code', 'm.unique_id', 'm.title', 'm.add_time', 'm.status']);
		$this->table(['m'=>'messages'])->join(['mc'=>'message_categories', 'm.category_id'=>'mc.id']);
		$this->order(['m.id'=>'desc'])->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array function record(integer $message_id)
	 */
	public function record(int $message_id): array {
		$this->field(['m.id', 'category_id'=>'mc.id', 'category_code'=>'mc.code', 'category_name'=>'mc.name', 'm.unique_id', 'm.title', 'm.description', 'm.add_time', 'm.status']);
		$this->table(['m'=>'messages'])->join(['mc'=>'message_categories', 'm.category_id'=>'mc.id']);
		$ends = $this->where(['m.id'=>$message_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean function remove(integer $message_id)
	 */
	public function remove(int $message_id): bool {
		$end = $this->table(['messages'])->where(['id'=>$message_id])->delete();
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean function edit(integer $message_id, array $datas)
	 */
	public function edit(int $message_id, array $datas): bool {
		$end = $this->table(['messages'])->where(['id'=>$message_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer function create(array $datas)
	 */
	public function create(array $datas): int {
		$end = $this->table(['messages'])->add($datas);
		return $end > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * public array function find(string $unique_id)
	 */
	public function find(string $unique_id): array {
		$ends = $this->table(['messages'])->where(['unique_id'=>$unique_id])->select();
		return $ends[0] ?? [];
	}
	//
}











