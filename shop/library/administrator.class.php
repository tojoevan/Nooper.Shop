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
	
	/**
	 * public integer function num(void)
	 */
	public function num(): int {
		// 查询返回管理员的个数
	}
	
	/**
	 * public array function page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function page(int $page_num = 1, int $page_lenght = 20): array {
		// 查询返回管理员列表，是一个二维数组， 按a.id=>asc
		// 返回字段包括
		// a.id
		// ar.code
		// a.email
		// a.add_time
	}
	
	/**
	 * public array function item(integer $admin_id)
	 */
	public function item(int $admin_id): array {
		/*
		 * 查询返回指定管理员的具体信息数组
		 * a.id
		 * ar.code
		 * a.email
		 * permissions=[ap.code,...]
		 * a.add_time
		 */
	}
	
	/**
	 * public integer function create(integer $role_id, string $email, string $pwd)
	 */
	public function create(int $role_id, string $email, string $pwd): int {
		/*
		 * 添加管理员记录
		 * 如果$role_id=1,即添加的是超级管理员，返回-2，禁止添加
		 * 如果添加成功，返回该新增的管理员的a.id，失败返回-1
		 */
	}
	
	/**
	 * public boolean function save(integer $admin_id, integer $role_id)
	 */
	public function save(int $admin_id, int $role_id): bool {
		/*
		 * 修改管理员记录
		 * 只能修改角色类型
		 * 如果$role_id=1， 即修改的是超级管理员，返回false
		 * 如果添加成功，返回true， 否则返回false
		 */
	}
	
	/**
	 * public boolean function remove(integer $admin_id)
	 */
	public function remove(int $admin_id): bool {
		/*
		 * 删除管理员记录
		 * 禁止删除$role_id=1的超级管理员，返回false
		 * 添加成功返回true，否则返回false
		 */
	}
	
	/**
	 * public boolean function password(string $new_pwd)
	 */
	public function password(string $new_pwd): bool {
		/*
		 * 重新设置管理员密码
		 */
	}
	
	//
}