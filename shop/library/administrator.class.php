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
	 * public array function get_permissions(void)
	 */
	public function get_permissions(): array {
		return $this->field(['id', 'code', 'name'])->table(['administrator_permissions'])->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array function get_permission_page(integer $page_num = 1, integer $page_length = 20)d
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
	 * public integer function get_role_num(void)
	 */
	public function get_role_num(): int {
		$ends = $this->field(['role_num'=>'count(*)'])->table(['administrator_roles'])->select();
		return isset($ends[0]) ? $ends[0]['role_num'] : -1;
	}
	
	/**
	 * public array function get_role_page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function get_role_page(int $page_num = 1, int $page_length = 20): array {
		$offset = ($page_num - 1) * $page_length;
		$this->field(['ar.id', 'ar.code', 'ar.name', 'admin_num'=>'count(`a`.`id`)', 'ar.add_time']);
		$this->table(['ar'=>'administrator_roles'])->join(['a'=>'administrators', 'ar.id'=>'a.role_id'], 'left');
		$this->order(['ar.id'=>'asc'])->group(['ar.id'])->limit($page_length, $offset);
		return $this->select();
	}
	
	/**
	 * public array function get_role_item(integer $role_id)
	 */
	public function get_role_item(int $role_id): array {
		$this->field(['ar.id', 'ar.code', 'ar.name', 'admin_num'=>'count(`a`.`id`)', 'ar.add_time']);
		$this->table(['ar'=>'administrator_roles'])->join(['a'=>'administrators', 'ar.id'=>'a.role_id'], 'left');
		$ends = $this->where(['ar.id'=>(string)$role_id])->select();
		$ends['permissions'] = $this->get_role_permissions($role_id);
		return $ends;
	}
	
	/**
	 * public array function get_role_permissions(integer $role_id)
	 */
	public function get_role_permissions(int $role_id): array {
		$this->field(['ap.id', 'ap.code', 'ap.name'])->table(['arrp'=>'administrator_role_rel_permissions']);
		$this->join(['ap'=>'administrator_permissions', 'arrp.permission_id'=>'ap.id']);
		$this->where(['arrp.role_id'=>(string)$role_id])->order(['ap.id'=>'asc']);
		return $this->select();
	}
	
	/**
	 * public boolean function set_role_permissions(integer $role_id, array $permission_ids)
	 * @$permission_ids=[integer $permission_id,...]
	 */
	public function set_role_permissions(int $role_id, array $permission_ids): bool {
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
	 * public bool function add_role(string $code, string $name)
	 */
	public function add_role(string $code, string $name): bool {
		$datas = ['code'=>$code, 'name'=>$name];
		$end = $this->table(['administrator_roles'])->add($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public bool function modify_role(integer $role_id, string $code, string $name)
	 */
	public function modify_role(int $role_id, string $code, string $name): bool {
		if(1 == $role_id) return false; // $role_id=1, $code='super_admin', no modify!
		$datas = ['code'=>$code, 'name'=>$name];
		$end = $this->table(['administrator_roles'])->where(['id'=>(string)$role_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer function delete_role(int $role_id)
	 */
	public function delete_role(int $role_id): int {
		if(1 == $role_id) return -2; // $role_id=1, $code='super_admin', no delete!
		$datas = $this->field(['admin_num'=>'count(*)'])->table(['administrators'])->where(['role_id'=>(string)$role_id])->select();
		if(isset($datas[0]) && $datas[0]['admin_num'] > 0) return -3; // role including admins!
		return $this->table(['administrator_roles'])->where(['id'=>(string)$role_id])->delete(); // -1: database error!
	}
	
	//
}