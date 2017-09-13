<?php
// declare(strict_types = 1);
namespace Nooper;

class Administrator extends Mysql {
	
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