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
	 * public string unique_id(void)
	 */
	public function unique_id(): string {
		//
	}
	
	/**
	 * public string rand_password(void)
	 */
	public function rand_password(): string {
		//
	}
	
	/**
	 * public integer function get_permission_num(void)
	 */
	public function get_permission_num(): int {
		$ends = $this->field(['permission_num'=>'count(*)'])->table(['manager_permissions'])->select();
		return $ends[0]['permission_num'] ?? -1;
	}
	
	/**
	 * public array function get_permission_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_permission_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['mp.id', 'mp.code', 'mp.name', 'role_num'=>'count(distinct `mrrp`.`role_id`)', 'manager_num'=>'count(`m`.`id`)', 'mp.add_time']);
		$this->table(['mp'=>'manager_permissions'])->join(['mrrp'=>'manager_role_rel_permissions', 'mp.id'=>'mrrp.permission_id'], 'left');
		$this->join(['m'=>'managers', 'mrrp.role_id'=>'m.role_id'], 'left');
		$this->group(['mp.id'])->order(['mp.id'=>'asc'])->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array function get_permissions(void)
	 */
	public function get_permissions(): array {
		return $this->field(['id', 'code', 'name'])->table(['manager_permissions'])->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array get_permission_record(integer $permission_id)
	 */
	public function get_permission_record(int $permission_id): array {
		$this->field(['mp.id', 'mp.code', 'mp.name', 'role_num'=>'count(distinct `mrrp`.`role_id`)', 'manager_num'=>'count(`m`.`id`)', 'mp.add_time']);
		$this->table(['mp'=>'manager_permissions'])->join(['mrrp'=>'manager_role_rel_permissions', 'mp.id'=>'mrrp.permission_id'], 'left');
		$this->join(['m'=>'managers', 'mrrp.role_id'=>'m.role_id'], 'left');
		$ends = $this->where(['mp.id'=>$permission_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public integer function get_role_num(void)
	 */
	public function get_role_num(): int {
		$ends = $this->field(['role_num'=>'count(*)'])->table(['manager_roles'])->select();
		return $ends[0]['role_num'] ?? -1;
	}
	
	/**
	 * public array function get_role_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_role_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['mr.id', 'mr.code', 'mr.name', 'manager_num'=>'count(`m`.`id`)', 'mr.add_time']);
		$this->table(['mr'=>'manager_roles'])->join(['m'=>'managers', 'mr.id'=>'m.role_id'], 'left');
		$this->group(['mr.id'])->order(['mr.id'=>'asc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array function get_role_record(integer $role_id)
	 */
	public function get_role_record(int $role_id): array {
		$this->field(['mr.id', 'mr.code', 'mr.name', 'permissions'=>null, 'manager_num'=>'count(`m`.`id`)', 'mr.add_time']);
		$this->table(['mr'=>'manager_roles'])->join(['m'=>'managers', 'mr.id'=>'m.role_id'], 'left');
		$datas = $this->where(['mr.id'=>$role_id])->select();
		$ends = $datas[0] ?? [];
		if($ends) $ends['permissions'] = $this->get_role_permissions($role_id);
		return $ends;
	}
	
	/**
	 * public array function get_role_permissions(integer $role_id)
	 */
	public function get_role_permissions(int $role_id): array {
		$this->field(['mp.id', 'mp.code', 'mp.name'])->table(['mp'=>'manager_permissions']);
		$this->join(['mrrp'=>'manager_role_rel_permissions', 'mp.id'=>'mrrp.permission_id']);
		$this->where(['mrrp.role_id'=>$role_id])->order(['mp.id'=>'asc']);
		return $this->select();
	}
	
	/**
	 * public boolean function set_role_permissions(integer $role_id, array $permission_ids)
	 * @$permission_ids = [integer $permission_id,...]
	 */
	public function set_role_permissions(int $role_id, array $permission_ids): bool {
		$this->begin();
		$end1 = $this->table(['manager_role_rel_permissions'])->where(['role_id'=>$role_id])->delete();
		if($end1 < 0){
			$this->rollback();
			return false;
		}
		foreach($permission_ids as $pid){
			$datas = ['role_id'=>$role_id, 'permission_id'=>$pid];
			$end2 = $this->table(['manager_role_rel_permissions'])->add($datas);
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
		if(1 == $role_id) return -2; // system_admin
		$datas = $this->field(['manager_num'=>'count(*)'])->table(['managers'])->where(['role_id'=>$role_id])->select();
		if(isset($datas[0]) && $datas[0]['manager_num'] > 0) return -3;
		return $this->table(['manager_roles'])->where(['id'=>$role_id])->delete();
	}
	
	/**
	 * public integer function edit_role(integer $role_id, array $datas)
	 */
	public function edit_role(int $role_id, array $datas): int {
		if(1 == $role_id) return -2; // system_admin
		return $this->table(['manager_roles'])->where(['id'=>$role_id])->modify($datas);
	}
	
	/**
	 * public bool function add_role(array $datas)
	 */
	public function add_role(array $datas): bool {
		$end = $this->table(['manager_roles'])->add($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer function num(void)
	 */
	public function num(): int {
		$ends = $this->field(['manager_num'=>'count(*)'])->table(['managers'])->select();
		return $ends[0]['manager_num'] ?? -1;
	}
	
	/**
	 * public array function page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['m.id', 'role_id'=>'mr.id', 'role_code'=>'mr.code', 'm.name', 'm.email', 'm.add_time']);
		$this->table(['m'=>'managers'])->join(['mr'=>'manager_roles', 'm.role_id'=>'mr.id']);
		$this->order(['m.id'=>'desc'])->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array function record(integer $manager_id)
	 */
	public function record(int $manager_id): array {
		$this->field(['m.id', 'role_id'=>'mr.id', 'role_code'=>'mr.code', 'm.name', 'm.email', 'permissions'=>null, 'm.add_time']);
		$this->table(['m'=>'managers'])->join(['mr'=>'manager_roles', 'm.role_id'=>'mr.id']);
		$datas = $this->where(['m.id'=>$manager_id])->select();
		$ends = $datas[0] ?? [];
		if($ends) $ends['permissions'] = $this->get_role_permissions($ends['role_id']);
		return $ends;
	}
	
	/**
	 * public boolean function set_password(integer $manager_id, string $new_pwd)
	 */
	public function set_password(int $manager_id, string $new_pwd): bool {
		$datas = ['pwd'=>["password('" . $new_pwd . "')"]];
		$end = $this->table(['managers'])->where(['id'=>$manager_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer function remove(integer $manager_id)
	 */
	public function remove(int $manager_id): int {
		if(1 == $manager_id) return -2; // system_admin
		return $this->table(['managers'])->where(['id'=>$manager_id])->delete();
	}
	
	/**
	 * public integer function edit(integer $manager_id, array $datas)
	 */
	public function edit(int $manager_id, array $datas): int {
		if(1 == $manager_id) return -2; // system_admin
		return $this->table(['managers'])->where(['id'=>$manager_id])->modify($datas);
	}
	
	/**
	 * public integer function create(array $datas)
	 */
	public function create(array $datas): int {
		if(isset($datas['role_id']) && 1 == $datas['role_id']) return -2; // system_admin
		elseif(isset($datas['pwd'])) $datas['pwd'] = ["password('" . $datas['pwd'] . "')"];
		$end = $this->table(['managers'])->add($datas);
		return $end > 0 ? $this->get_last_id() : $end;
	}
	//
}