<?php
// declare(strict_types = 1);
namespace Nooper;

class Administrator extends Mysql {
	
	/**
	 * public integer function get_permission_num(void)
	 */
	public function get_permission_num(): int {
		$ends = $this->field(['permission_num'=>'count(*)'])->table(['administrator_permissions'])->select();
		return isset($ends[0]) ? $ends[0]['permission_num'] : -1;
	}
	
	/**
	 * public array function get_permission_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_permission_page(int $page_num = 1, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['ap.id', 'ap.code', 'ap.name', 'role_num'=>'count(distinct `arrp`.`role_id`)', 'admin_num'=>'count(`a`.`id`)', 'ap.add_time']);
		$this->table(['ap'=>'administrator_permissions']);
		$this->join(['arrp'=>'administrator_role_rel_permissions', 'ap.id'=>'arrp.permission_id'], 'left');
		$this->join(['a'=>'administrators', 'arrp.role_id'=>'a.role_id'], 'left');
		$this->order(['ap.id'=>'asc'])->group(['ap.id'])->limit($page_length, $offset);
		return $this->select();
	}
	
	/**
	 * public array get_permission_item(integer $permission_id)
	 */
	public function get_permission_item(int $permission_id): array {
		$this->field(['ap.id', 'ap.code', 'ap.name', 'role_num'=>'count(distinct `arrp`.`role_id`)', 'admin_num'=>'count(`a`.`id`)', 'ap.add_time']);
		$this->table(['ap'=>'administrator_permissions']);
		$this->join(['arrp'=>'administrator_role_rel_permissions', 'ap.id'=>'arrp.permission_id'], 'left');
		$this->join(['a'=>'administrators', 'arrp.role_id'=>'a.role_id'], 'left');
		$ends = $this->where(['ap.id'=>(string)$permission_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array function page(integer $page_num, integer $page_length = 20)
	 */
	public function page(int $page_num, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		return $this->field(['id', 'code', 'name'])->order(['id'=>'asc'])->limit($page_length, $offset)->select();
	}
	
	/**
	 */
	public function get_role_page(int $permission_id, int $page_num, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		return $this->field()->table(['administrator_roles'=>'r'])->join()->where()->order()->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_admin_page(integer $permission_id, integer $page_num, integer $page_length = 20)
	 */
	public function get_admin_page(int $permission_id, int $page_num, int $page_length = 20): array {
	}
	//
}