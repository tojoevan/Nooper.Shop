<?php

// declare(strict_types = 1);
namespace NooperShop;

use Nooper\Mysql;

class Manager extends Mysql {
	
	/**
	 * Const
	 */
	const page_record_num = 20;
	
	/**
	 * public integer get_permission_num(void)
	 */
	public function get_permission_num(): int {
		$ends = $this->field(['permission_num'=>'COUNT(*)'])->table(['manager_permissions'])->select();
		return $ends[0]['permission_num'] ?? -1;
	}
	
	/**
	 * public array get_permissions(void)
	 */
	public function get_permissions(): array {
		return $this->field(['id', 'code', 'name'])->table(['manager_permissions'])->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array get_permission_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_permission_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_permission_view()->group(['mp.id'])->order(['mp.id'=>'asc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_permission_record(integer $permission_id)
	 */
	public function get_permission_record(int $permission_id): array {
		$ends = $this->_permission_view()->where(['mp.id'=>$permission_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public integer get_role_num(void)
	 */
	public function get_role_num(): int {
		$ends = $this->field(['role_num'=>'COUNT(*)'])->table(['manager_roles'])->select();
		return $ends[0]['role_num'] ?? -1;
	}
	
	/**
	 * public array get_roles(void)
	 */
	public function get_roles(): array {
		return $this->field(['id', 'code', 'name'])->table(['manager_roles'])->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array get_role_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_role_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_role_view()->group(['mr.id'])->order(['mr.id'=>'asc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_role_record(integer $role_id)
	 */
	public function get_role_record(int $role_id): array {
		$ends = $this->_role_view()->where(['mr.id'=>$role_id])->select();
		foreach($ends as &$end){
			$end['permission'] = $this->get_role_permissions($end['id']);
		}
		return $ends[0] ?? [];
	}
	
	/**
	 * public array get_role_permission(integer $role_id)
	 */
	public function get_role_permission(int $role_id): array {
		$this->field(['mp.id', 'mp.code', 'mp.name'])->table(['mp'=>'manager_permissions']);
		$this->join(['mrrp'=>'manager_role_rel_permissions', 'mp.id'=>'mrrp.permission_id']);
		$this->where(['mrrp.role_id'=>$role_id])->order(['mp.id'=>'asc']);
		return $this->select();
	}
	
	/**
	 * public boolean set_role_permission(integer $role_id, array $permissions)
	 * @$permissions = [integer $permission,...]
	 */
	public function set_role_permission(int $role_id, array $permissions): bool {
		if(in_array(1, $permissions, true)) $permissions = [1]; // 1=all_permissions, @@
		if($this->begin()){
			$end1 = $this->table(['manager_role_rel_permissions'])->where(['role_id'=>$role_id])->delete();
			$end2 = true;
			foreach($permissions as $permission){
				$datas = ['role_id'=>$role_id, 'permission_id'=>$permission];
				$end_permission = $this->table(['manager_role_rel_permissions'])->add($datas);
				if($end_permission < 0){
					$end2 = false;
					break;
				}
			}
			if($end1 > 0 && $end2 && $this->end()) return true;
			$this->rollback();
		}
		return false;
	}
	
	/**
	 * public integer delete_role(int $role_id)
	 */
	public function delete_role(int $role_id): int {
		if(1 == $role_id) return -2; // error for system_admin, @@
		$datas = $this->field(['manager_num'=>'COUNT(*)'])->table(['managers'])->where(['role_id'=>$role_id])->select();
		if(isset($datas[0]) && $datas[0]['manager_num'] > 0) return -3; // error for include manager, @@
		return $this->table(['manager_roles'])->where(['id'=>$role_id])->delete();
	}
	
	/**
	 * public integer edit_role(integer $role_id, array $datas)
	 * @$datas = [string $code, string $name]
	 */
	public function edit_role(int $role_id, array $datas): int {
		if(1 == $role_id) return -2; // error for system_admin, @@
		return $this->table(['manager_roles'])->where(['id'=>$role_id])->modify($datas);
	}
	
	/**
	 * public ?integer add_role(array $datas)
	 * @$datas = [string $code, string $name]
	 */
	public function add_role(array $datas): ?int {
		return $this->table(['manager_roles'])->add($datas) > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * public integer num(void)
	 */
	public function num(): int {
		$ends = $this->field(['manager_num'=>'COUNT(*)'])->table(['managers'])->select();
		return $ends[0]['manager_num'] ?? -1;
	}
	
	/**
	 * public array page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->_view()->order(['m.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array record(integer $manager_id)
	 */
	public function record(int $manager_id): array {
		$ends = $this->where(['m.id'=>$manager_id])->select();
		foreach($ends as &$end){
			$end['permission'] = $this->get_role_permissions($end['role_id']);
		}
		return $ends[0] ?? [];
	}
	
	/**
	 * public array login(string $email, string $pwd)
	 */
	public function login(string $email, string $pwd): array {
		$pwd = ["PASSWORD('" . $pwd . "')"];
		$ends = $this->field(['id', 'email', 'name'])->table(['managers'])->where(['email'=>$email, 'pwd'=>$pwd])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean password(integer $manager_id, string $pwd)
	 */
	public function password(int $manager_id, string $pwd): bool {
		$pwd = ["PASSWORD('" . $pwd . "')"];
		$end = $this->table(['managers'])->where(['id'=>$manager_id])->modify(['pwd'=>$pwd]);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer remove(integer $manager_id)
	 */
	public function remove(int $manager_id): int {
		if(1 == $manager_id) return -2; // error for system_admin, @@
		return $this->table(['managers'])->where(['id'=>$manager_id])->delete();
	}
	
	/**
	 * public integer edit(integer $manager_id, array $datas)
	 * @$datas = [string $name, string $email]
	 */
	public function edit(int $manager_id, array $datas): int {
		if(1 == $manager_id) return -2; // error for system_admin, @@
		return $this->table(['managers'])->where(['id'=>$manager_id])->modify($datas);
	}
	
	/**
	 * public ?integer create(array $datas)
	 * @$datas = [integer $role_id, string $name, string $email, string $pwd]
	 */
	public function create(array $datas): ?int {
		if(isset($datas['role_id']) && 1 == $datas['role_id']) return -2; // error for system_admin, @@
		elseif(isset($datas['pwd'])) $datas['pwd'] = ["PASSWORD('" . $datas['pwd'] . "')"];
		return $this->table(['managers'])->add($datas) > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * public string create_default_password(void)
	 */
	public function create_default_password(): string {
		$unique = new Unique();
		return $unique->password();
	}
	
	/**
	 * protected Manager _permission_view(void)
	 */
	protected function _permission_view(): Manager {
		$mp_cols = ['mp.id', 'mp.code', 'mp.name', 'mp.add_time'];
		$func_cols = ['role_num'=>'COUNT(DISTINCT `mrrp`.`role_id`)', 'manager_num'=>'COUNT(`m`.`id`)'];
		$this->field(array_merge($mp_cols, $func_cols))->table(['mp'=>'manager_permissions']);
		$this->join(['mrrp'=>'manager_role_rel_permissions', 'mp.id'=>'mrrp.permission_id'], 'left');
		$this->join(['m'=>'managers', 'mrrp.role_id'=>'m.role_id'], 'left');
		return $this;
	}
	
	/**
	 * protected Manager _role_view(void)
	 */
	protected function _role_view(): Manager {
		$mr_cols = ['mr.id', 'mr.code', 'mr.name', 'mr.add_time'];
		$func_cols = ['manager_num'=>'COUNT(`m`.`id`)'];
		$define_cols = ['permission'=>null];
		$this->field(array_merge($mr_cols, $func_cols, $define_cols))->table(['mr'=>'manager_roles']);
		$this->join(['m'=>'managers', 'mr.id'=>'m.role_id'], 'left');
		return $this;
	}
	
	/**
	 * protected Manager _view(void)
	 */
	protected function _view(): Manager {
		$m_cols = ['m.id', 'm.name', 'm.email', 'm.add_time'];
		$mr_cols = ['role_id'=>'mr.id', 'role_code'=>'mr.code'];
		$define_cols = ['permission'=>null];
		$this->field(array_merge($m_cols, $mr_cols, $define_cols))->table(['m'=>'managers']);
		$this->join(['mr'=>'manager_roles', 'm.role_id'=>'mr.id']);
	}
	//
}






