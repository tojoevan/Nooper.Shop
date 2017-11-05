<?php
// declare(strict_types = 1);
namespace Nooper;

class Administrator extends Mysql {
	
	/**
	 * Const
	 */
	const page_record_num = 20;
	
	/**
	 * public integer function get_permission_num(void)
	 */
	public function get_permission_num(): int {
		$ends = $this->field(['permission_num'=>'count(*)'])->table(['administrator_permissions'])->select();
		return $ends[0]['permission_num'] ?? -1;
	}
	
	/**
	 * public array function get_permission_list(void)
	 */
	public function get_permission_list(): array {
		return $this->field(['id', 'code', 'name'])->table(['administrator_permissions'])->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array function get_permission_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_permission_page(): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['ap.id', 'ap.code', 'ap.name', 'role_num'=>'count(distinct `arrp`.`role_id`)', 'admin_num'=>'count(`a`.`id`)', 'ap.add_time']);
		$this->table(['ap'=>'administrator_permissions']);
		$this->join(['arrp'=>'administrator_role_rel_permissions', 'ap.id'=>'arrp.permission_id'], 'left');
		$this->join(['a'=>'administrators', 'arrp.role_id'=>'a.role_id'], 'left');
		$this->group(['ap.id'])->order(['ap.id'=>'asc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array get_permission_record(integer $permission_id)
	 */
	public function get_permission_record(int $permission_id): array {
		$this->field(['ap.id', 'ap.code', 'ap.name', 'role_num'=>'count(distinct `arrp`.`role_id`)', 'admin_num'=>'count(`a`.`id`)', 'ap.add_time']);
		$this->table(['ap'=>'administrator_permissions']);
		$this->join(['arrp'=>'administrator_role_rel_permissions', 'ap.id'=>'arrp.permission_id'], 'left');
		$this->join(['a'=>'administrators', 'arrp.role_id'=>'a.role_id'], 'left');
		$ends = $this->where(['ap.id'=>(string)$permission_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array function get_role_page_by_permission_id(integer $permission_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_role_page_by_permission_id(int $permission_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['ar.id', 'ar.code', 'ar.name', 'admin_num'=>'count(`a`.`id`)', 'ar.add_time']);
		$this->table(['ar'=>'administrator_roles'])->join(['a'=>'administrators', 'ar.id'=>'a.role_id'], 'left');
		$this->join(['arrp'=>'administrator_role_rel_permissions', 'ar.id'=>'arrp.role_id']);
		$this->where(['arrp.permission_id'=>(string)$permission_id, 'arrp.permission_id'=>'1'], 'eq', 'or')->group(['ar.id'])->order(['ar.id'=>'asc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_page_by_permission_id(integer $permission_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_page_by_permission_id(int $permission_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['a.id', 'role_id'=>'ar.id', 'role_code'=>'ar.code', 'a.name', 'a.email', 'a.add_time']);
		$this->table(['a'=>'administrators'])->join(['ar'=>'administrator_roles', 'a.role_id'=>'ar.id']);
		$this->join(['arrp'=>'administrator_role_rel_permissions', 'ar.id'=>'arrp.role_id']);
		$this->where(['arrp.permission_id'=>(string)$permission_id, 'arrp.permission_id'=>'1'], 'eq', 'or')->order(['a.id'=>'asc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public integer function get_role_num(void)
	 */
	public function get_role_num(): int {
		$ends = $this->field(['role_num'=>'count(*)'])->table(['administrator_roles'])->select();
		return $ends[0]['role_num'] ?? -1;
	}
	
	/**
	 * public array function get_role_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_role_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['ar.id', 'ar.code', 'ar.name', 'admin_num'=>'count(`a`.`id`)', 'ar.add_time']);
		$this->table(['ar'=>'administrator_roles'])->join(['a'=>'administrators', 'ar.id'=>'a.role_id'], 'left');
		$this->group(['ar.id'])->order(['ar.id'=>'asc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_role_record(integer $role_id)
	 */
	public function get_role_record(int $role_id): array {
		$this->field(['ar.id', 'ar.code', 'ar.name', 'admin_num'=>'count(`a`.`id`)', 'ar.add_time']);
		$this->table(['ar'=>'administrator_roles'])->join(['a'=>'administrators', 'ar.id'=>'a.role_id'], 'left');
		$datas = $this->where(['ar.id'=>(string)$role_id])->select();
		$ends = $datas[0] ?? [];
		if($ends) $ends['permissions'] = $this->get_role_permissions($role_id);
		return $ends;
	}
	
	/**
	 * public array function get_role_permission(integer $role_id)
	 */
	public function get_role_permission(int $role_id): array {
		$this->field(['ap.id', 'ap.code', 'ap.name'])->table(['ap'=>'administrator_permissions']);
		$this->join(['arrp'=>'administrator_role_rel_permissions', 'ap.id'=>'arrp.permission_id']);
		$this->where(['arrp.role_id'=>(string)$role_id])->order(['ap.id'=>'asc']);
		return $this->select();
	}
	
	/**
	 * public boolean function set_role_permission(integer $role_id, array $permission_ids)
	 * @$permission_ids = [integer $permission_id,...]
	 */
	public function set_role_permission(int $role_id, array $permission_ids): bool {
		$this->begin();
		$end1 = $this->table(['administrator_role_rel_permissions'])->where(['role_id'=>(string)$role_id])->delete();
		if($end1 < 0){
			$this->rollback();
			return false;
		}
		foreach($permission_ids as $pid){
			$datas = ['role_id'=>$role_id, 'permission_id'=>$pid];
			$end2 = $this->table(['administrator_role_rel_permissions'])->add($datas);
			if($end2 < 0){
				$this->rollback();
				return false;
			}
		}
		$this->end();
		return true;
	}
	
	/**
	 * public integer function delete_role(int $role_id)
	 */
	public function delete_role(int $role_id): int {
		if(1 == $role_id) return -2;
		$datas = $this->field(['admin_num'=>'count(*)'])->table(['administrators'])->where(['role_id'=>(string)$role_id])->select();
		if(isset($datas[0]) && $datas[0]['admin_num'] > 0) return -3;
		return $this->table(['administrator_roles'])->where(['id'=>(string)$role_id])->delete();
	}
	
	/**
	 * public integer function edit_role(integer $role_id, array $datas)
	 */
	public function edit_role(int $role_id, array $datas): int {
		if(1 == $role_id) return -2;
		return $this->table(['administrator_roles'])->where(['id'=>(string)$role_id])->modify($datas);
	}
	
	/**
	 * public bool function add_role(array $datas)
	 */
	public function add_role(array $datas): bool {
		$end = $this->table(['administrator_roles'])->add($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer function num(void)
	 */
	public function num(): int {
		$ends = $this->field(['admin_num'=>'count(*)'])->table(['administrators'])->select();
		return $ends[0]['admin_num'] ?? -1;
	}
	
	/**
	 * public array function page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['a.id', 'role_id'=>'ar.id', 'role_code'=>'ar.code', 'a.email', 'a.name', 'a.add_time']);
		$this->table(['a'=>'administrators'])->join(['ar'=>'administrator_roles', 'a.role_id'=>'ar.id']);
		$this->order(['a.id'=>'asc'])->limit($page_length, $offset);
		return $this->select();
	}
	
	/**
	 * public array function record(integer $admin_id)
	 */
	public function record(int $admin_id): array {
		$this->field(['a.id', 'role_id'=>'ar.id', 'role_code'=>'ar.code', 'a.name', 'a.email', 'a.add_time']);
		$this->table(['a'=>'administrators'])->join(['ar'=>'administrator_roles', 'a.role_id'=>'ar.id']);
		$datas = $this->where(['a.id'=>(string)$admin_id])->select();
		$ends = $datas[0] ?? [];
		if($ends) $ends['permissions'] = $this->get_role_permissions($ends['role_id']);
		return $ends;
	}
	
	/**
	 * public integer function delete_record(integer $admin_id)
	 */
	public function delete_record(int $admin_id): int {
		if(1 == $admin_id) return -2;
		return $this->table(['administrators'])->where(['id'=>(string)$admin_id])->delete();
	}
	
	/**
	 * public integer function edit_record(integer $admin_id, array $datas)
	 */
	public function edit_record(int $admin_id, array $datas): int {
		if(1 == $admin_id) return -2;
		return $this->table(['administrators'])->where(['id'=>(string)$admin_id])->modify($datas);
	}
	
	/**
	 * public integer function add_record(array $datas)
	 */
	public function add_record(array $datas): int {
		if(isset($datas['role_id']) && 1 == $datas['role_id']) return -2;
		elseif(isset($datas['pwd'])) $datas['pwd'] = ["password('" . $datas['pwd'] . "')"];
		$end = $this->table(['administrators'])->add($datas);
		return $end > 0 ? $this->get_last_id() : $end;
	}
	
	/**
	 * public boolean function set_password(integer $admin_id, string $new_pwd)
	 */
	public function set_password(int $admin_id, string $new_pwd): bool {
		$datas = ['pwd'=>["password('" . $new_pwd . "')"]];
		$end = $this->table(['administrators'])->where(['id'=>(string)$admin_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	//
}