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
	 * public string unique_id(void)
	 */
	public function unique_id(): string {
		$unique = new Unique();
		do{
			$unique_id = $unique->message();
			$datas = $this->field(['num'=>'COUNT(*)'])->table(['messages'])->where(['unique_id'=>$unique_id])->select();
			if(isset($datas[0]) && $datas[0]['num'] > 0) continue;
			break;
		}while(true);
		return $unique_id;
	}
	
	/**
	 * public array function get_default_params(void)
	 */
	public function get_default_params(): array {
		$ends = $this->field(['auto_clear_trigger'])->table(['message_default_params'])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public void open_system_clear(void)
	 */
	public function open_system_clear(): void {
		$sql = 'alter event delete_expired_message on completion preserve enable';
		$this->cmd($sql);
	}
	
	/**
	 * public void close_system_clear(void)
	 */
	public function close_system_clear(): void {
		$sql = 'alter event delete_expired_message on completion preserve disable';
		$this->cmd($sql);
	}
	
	/**
	 * public integer get_category_num(void)
	 */
	public function get_category_num(): int {
		$ends = $this->field(['category_num'=>'COUNT(*)'])->table(['message_categories'])->select();
		return $ends[0]['category_num'] ?? -1;
	}
	
	/**
	 * public array get_categories(void)
	 */
	public function get_categories(): array {
		return $this->field(['id', 'code', 'name'])->table(['message_categories'])->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array get_category_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_category_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$mc_cols = ['mc.id', 'mc.code', 'mc.name', 'mc.add_time'];
		$func_cols = ['message_num'=>'COUNT(`m`.`id`)'];
		$this->field(array_merge($mc_cols, $func_cols))->table(['mc'=>'message_categories']);
		$this->join(['m'=>'messages', 'mc.id'=>'m.category_id'], 'left')->group(['mc.id'])->order(['mc.id'=>'asc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_category_record(integer $category_id)
	 */
	public function get_category_record(int $category_id): array {
		$mc_cols = ['mc.id', 'mc.code', 'mc.name', 'mc.add_time'];
		$func_cols = ['message_num'=>'COUNT(`m`.`id`)'];
		$this->field(array_merge($mc_cols, $func_cols))->table(['mc'=>'message_categories']);
		$this->join(['m'=>'messages', 'mc.id'=>'m.category_id'], 'left');
		$ends = $this->where(['mc.id'=>$category_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public integer num(void)
	 */
	public function num(): int {
		return $this->get_num();
	}
	
	/**
	 * public integer get_week_num(void)
	 */
	public function get_week_num(): int {
		return $this->get_num('<7');
	}
	
	/**
	 * public array get_week_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_week_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		return $this->get_page("<7", $page_num, $page_length);
	}
	
	/**
	 * public integer get_month_num(void)
	 */
	public function get_month_num(): int {
		return $this->_num('<30');
	}
	
	/**
	 * public array get_month_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_month_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		return $this->_page("<30", $page_num, $page_length);
	}
	
	/**
	 * public integer get_quarter_num(void)
	 */
	public function get_quarter_num(): int {
		return $this->_num('<90');
	}
	
	/**
	 * public array get_quarter_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_quarter_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		return $this->_page("<90", $page_num, $page_length);
	}
	
	/**
	 * public integer get_old_num(void)
	 */
	public function get_old_num(): int {
		return $this->_num('>=90');
	}
	
	/**
	 * public array get_old_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_old_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		return $this->_page(">=90", $page_num, $page_length);
	}
	
	/**
	 * public array record(integer $message_id)
	 */
	public function record(int $message_id): array {
		$m_cols = ['m.id', 'm.unique_id', 'm.title', 'm.description', 'm.add_time', 'm.status'];
		$mc_cols = ['category_id'=>'mc.id', 'category_code'=>'mc.code'];
		$c_cols = ['customer_id'=>'c.id', 'customer_unique_id'=>'c.unique_id'];
		$this->field(array_merge($m_cols, $mc_cols, $c_cols))->table(['m'=>'messages']);
		$this->join(['mc'=>'message_categories', 'm.category_id'=>'mc.id']);
		$this->join(['c'=>'customer', 'm.customer_id'=>'c.id']);
		$ends = $this->where(['m.id'=>$message_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array find(string $message_unique_id)
	 */
	public function find(string $message_unique_id): array {
		$m_cols = ['m.id', 'm.unique_id', 'm.title', 'm.add_time', 'm.status'];
		$mc_cols = ['category_id'=>'mc.id', 'category_code'=>'mc.code'];
		$c_cols = ['customer_id'=>'c.id', 'customer_unique_id'=>'c.unique_id'];
		$this->field(array_merge($m_cols, $mc_cols, $c_cols))->table(['m'=>'messages']);
		$this->join(['mc'=>'message_categories', 'm.category_id'=>'mc.id']);
		$this->join(['c'=>'customer', 'm.customer_id'=>'c.id']);
		return $this->where(['m.unique_id'=>$message_unique_id])->select();
	}
	
	/**
	 * public boolean read(integer $message_id)
	 */
	public function read(int $message_id): bool {
		$end = $this->table(['messages'])->where(['id'=>$message_id])->modify(['status'=>'read']);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean remove(integer $message_id)
	 */
	public function remove(int $message_id): bool {
		$end = $this->table(['messages'])->where(['id'=>$message_id])->delete();
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean edit(integer $message_id, array $datas)
	 * @$datas = [string $title, string $description]
	 */
	public function edit(int $message_id, array $datas): bool {
		$datas = array_merge($datas, ['status'=>'unread']);
		$end = $this->table(['messages'])->where(['id'=>$message_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer create(array $datas)
	 * @$datas = [integer $category_id, integer $customer_id, string $title, string $description]
	 */
	public function create(array $datas): int {
		$datas = array_merge($datas, ['unique_id'=>$this->unique_id()]);
		$end = $this->table(['messages'])->add($datas);
		return $end > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * protected integer get_num(?string $where = null)
	 */
	protected function get_num(?string $where = null): int {
		$this->field(['message_num'=>'COUNT(*)'])->table(['messages']);
		if(is_string($where)) $this->where_cmd("datediff(current_timestamp(), `add_time`)" . $where);
		$ends = $this->select();
		return $ends[0]['message_num'] ?? -1;
	}
	
	/**
	 * protected array get_page(string $where, integer $page_num, integer $page_length)
	 */
	protected function get_page(string $where, int $page_num, int $page_length): array {
		$offset_num = $page_length * ($page_num - 1);
		$m_cols = ['m.id', 'm.unique_id', 'm.title', 'm.add_time', 'm.status'];
		$mc_cols = ['category_id'=>'mc.id', 'category_code'=>'mc.code'];
		$c_cols = ['customer_id'=>'c.id', 'customer_unique_id'=>'c.unique_id'];
		$this->field(array_merge($m_cols, $mc_cols, $c_cols))->table(['m'=>'messages']);
		$this->join(['mc'=>'message_categories', 'm.category_id'=>'mc.id']);
		$this->join(['c'=>'customer', 'm.customer_id'=>'c.id']);
		$this->where_cmd("datediff(current_timestamp(), `m`.`add_time`)" . $where)->order(['m.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	//
}
