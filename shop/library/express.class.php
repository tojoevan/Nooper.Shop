<?php
// declare(strict_types = 1);
namespace Nooper;

class Express extends Mysql {
	
	/**
	 * public ?integer function get_corporations_num(void)
	 */
	public function get_corporations_num(): int {
		$datas = $this->field(['corporation_num'=>'count(*)'])->table(['express_corporations'])->select();
		return $datas[0]['corporation_num'] ?? null;
	}
	
	/**
	 * public array function get_corporations(integer $page_num, integer $page_length = 20)
	 */
	public function get_corporations(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$field_datas = ['ec.id', 'ec.name', 'ec.home_page', 'ec.query_api', 'ec.is_default', 'ec.position', 'express_num'=>'count(`e`.`code`)'];
		$table_datas = ['ec'=>'express_corporations'];
		$join_datas = ['e'=>'expresses', 'ec.id'=>'e.corporation_id'];
		$group_datas = ['ec.id'];
		$order_datas = ['ec.is_default'=>'desc', 'ec.position'=>'desc', 'ec.id'=>'desc'];
		return $this->field($field_datas)->table($table_datas)->join($join_datas, 'left')->group($group_datas)->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	
	/**
	 */
	public function get_expresses_by_corporation_id(int $corporation_id): array {
	}
	
	/**
	 * public array function get_carriage_templates(integer $page_num, $integer $page_length = 20)
	 */
	public function get_carriage_templates(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$field_datas = ['ect.id', 'ect.name', 'ect.basic_carriage', 'ect.each_plus_carriage', 'ect.ceil_carriage', 'ect.is_default', 'product_num'=>'count(prect.product_id)'];
		$table_datas = ['ect'=>'express_carriage_templates'];
		$join_datas = ['prect'=>'product_rel_express_carriage_templates', 'ect.id'=>'prect.carriage_template_id'];
		$group_datas = ['ect.id'];
		$order_datas = ['ect.is_default'=>'desc', 'ect.name'=>'asc'];
		return $this->field($field_datas)->table($table_datas)->join($join_datas, 'left')->group($group_datas)->order($order_datas)->limit($page_length, $offset_num)->select();
	}
	
	/**
	 * public array function get_carriage_template_by_id(integer $template_id)
	 */
	public function get_carriage_template_by_id(int $template_id): array {
		$field_datas = ['id', 'name', 'basic_carriage', 'each_plus_carriage', 'ceil_carriage', 'is_default'];
		$ends = $this->field($field_datas)->table(['express_carriage_templates'])->where(['id'=>(string)$template_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array function get_carriage_template_detail_by_id(integer $template_id)
	 */
	public function get_carriage_template_detail_by_id(int $template_id): array {
		$field_datas = ['ectd.id', 'eap.name', 'ectd.basic_carriage', 'ectd.each_plus_carriage'];
		$table_datas = ['eap'=>'express_address_provinces'];
		$join_datas = ['ectd'=>'express_carriage_template_details', 'eap.id'=>'ectd.province_id'];
		$where_datas = ['ectd.template_id'=>(string)$template_id];
		$order_datas = ['eap.id'=>'asc'];
		return $this->field($field_datas)->table($table_datas)->join($join_datas)->where($where_datas)->order($order_datas)->select();
	}
	//
}










