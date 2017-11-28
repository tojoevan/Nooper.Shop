<?php
// declare(strict_types = 1);
namespace NooperShop;

use Nooper\Mysql;

class Express extends Mysql {
	
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
	 * public array get_address_regions(void)
	 */
	public function get_address_regions(): array {
		$this->field(['id', 'code', 'name'])->table(['express_address_regions'])->where(['id'=>1], 'neq');
		return $this->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array function get_address_provinces(integer $region_id)
	 */
	public function get_address_provinces(int $region_id): array {
		$this->field(['id', 'name'])->table(['express_address_provinces'])->where(['region_id'=>$region_id]);
		return $this->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array get_address_cities(integer $province_id)
	 */
	public function get_address_cities(int $province_id): array {
		$this->field(['id', 'name'])->table(['express_address_cities'])->where(['province_id'=>$province_id]);
		return $this->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array function get_address_counties(integer $city_id)
	 */
	public function get_address_counties(int $city_id): array {
		$this->field(['id', 'name'])->table(['express_address_counties'])->where(['city_id'=>$city_id]);
		return $this->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public array function get_address_towns(integer $county_id)
	 */
	public function get_address_towns(int $county_id): array {
		$this->field(['id', 'name'])->table(['express_address_towns'])->where(['county_id'=>$county_id]);
		return $this->order(['id'=>'asc'])->select();
	}
	
	/**
	 * public scalar get_param(string $name)
	 */
	public function get_param(string $name) {
		$this->field(['carriage_mode', 'inner_free', 'inner_ceil']);
		$datas = $this->table(['express_carriage_default_params'])->where(['id'=>1])->select();
		if(isset($datas[0])){
			$params = $datas[0];
			return $params[$name] ?? null;
		}
		return null;
	}
	
	/**
	 * public boolean set_param(string $name, ?scalar $value)
	 */
	public function set_param(string $name, $value): bool {
		$end = $this->table(['express_carriage_default_params'])->where(['id'=>1])->modify([$name=>$value]);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer get_corporation_num(void)
	 */
	public function get_corporation_num(): int {
		$ends = $this->field(['corporation_num'=>'count(*)'])->table(['express_corporations'])->select();
		return $ends[0]['corporation_num'] ?? -1;
	}
	
	/**
	 * public array get_corporations(void)
	 */
	public function get_corporations(): array {
		return $this->field(['id', 'name'])->table(['express_corporations'])->order(['place'=>'desc', 'id'=>'desc'])->select();
	}
	
	/**
	 * public array get_corporation_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_corporation_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['id', 'name', 'query_api', 'place', 'add_time'])->table(['express_corporations']);
		$this->order(['place'=>'desc', 'id'=>'desc'])->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_corporation_record(integer $corporation_id)
	 */
	public function get_corporation_record(int $corporation_id): array {
		$this->field(['id', 'name', 'query_api', 'place', 'add_time'])->table(['express_corporations']);
		$ends = $this->where(['id'=>$corporation_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean delete_corporation(integer $corporation_id)
	 */
	public function delete_corporation(int $corporation_id): bool {
		$end = $this->table(['express_corporations'])->where(['id'=>$corporation_id])->delete();
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean edit_corporation(integer $corporation_id, array $datas)
	 * @datas = [string $name, string $query_api, integer $place]
	 */
	public function edit_corporation(int $corporation_id, array $datas): bool {
		$end = $this->table(['express_corporations'])->where(['id'=>$corporation_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer add_corporation(array $datas)
	 */
	public function add_corporation(array $datas): int {
		$end = $this->table(['express_corporations'])->add($datas);
		return $end > 0 ? $this->get_last_id() : $end;
	}
	
	/**
	 * public integer get_carriage_global_num(void)
	 */
	public function get_carriage_global_num(): int {
		$ends = $this->field(['global_num'=>'COUNT(*)'])->table(['express_carriage_global_params'])->select();
		return $ends[0]['global_num'] ?? -1;
	}
	
	/**
	 * public array get_carriage_global_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_address_region_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['ecgp.id', 'region_id'=>'ear.id', 'region_code'=>'ear.code', 'region_name'=>'ear.name', 'ecgp.basic_carriage', 'ecgp.each_plus_carriage', 'ecgp.tax_rate', 'ecgp.add_time']);
		$this->table(['ecgp'=>'express_carriage_global_params']);
		$this->join(['ear'=>'express_address_regions', 'ecgp.region_id'=>'ear.id']);
		$this->order(['ear.id'=>'asc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_carriage_global_record(integer $global_id)
	 */
	public function get_carriage_global_record(int $global_id): array {
		$this->field(['ecgp.id', 'region_id'=>'ear.id', 'region_code'=>'ear.code', 'region_name'=>'ear.name', 'ecgp.basic_carriage', 'ecgp.each_plus_carriage', 'ecgp.tax_rate', 'ecgp.add_time']);
		$this->table(['ecgp'=>'express_carriage_global_params']);
		$this->join(['ear'=>'express_address_regions', 'ecgp.region_id'=>'ear.id']);
		$ends = $this->where(['ecgp.id'=>$global_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public integer delete_carriage_global(integer $global_id)
	 */
	public function delete_carriage_global(int $global_id): int {
		if(1 == $global_id) return -2; // error for China!
		$this->begin();
		$end1 = $this->table(['express_address_regions'])->where(['id'=>$region_id])->delete();
		$end2 = $this->table(['express_carriage_outer_template_details'])->where(['region_id'=>$region_id])->delete();
		$end3 = $this->table(['customer_deliver_addresses'])->where(['region_id'=>$region_id])->modify(['status'=>'disabled']);
		if($end1 > 0 && $end2 >= 0 && $end3 >= 0){
			$region_num = $this->get_address_region_num();
			// if(1==$region_num) $end4=$this->set_param('');
			$this->end();
			return 1;
		}else{
			$this->rollback();
			return -1; // error!
		}
	}
	
	/**
	 * public boolean edit_carriage_global(integer $global_id, array $datas)
	 * @$datas = [float $basic_carriage, float $each_plus_carriage, float $tax_rate]
	 */
	public function edit_carriage_global(int $global_id, array $datas): bool {
		$end = $this->table(['express_carriage_global_params'])->where(['id'=>$global_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer add_carriage_global(array $datas)
	 * @$datas = [integer $region_id, float $basic_carriage, float $each_plus_carriage, float $tax_rate]
	 */
	public function add_carriage_global(array $datas): int {
		$end1 = $this->table(['express_carriage_global_params'])->add($datas);
		$end2 = true;
		if($end1 > 0) $end1 = $this->get_last_id();
		$templates = $this->field(['id'])->table(['express_carriage_outer_templates'])->select();
		foreach($templates as $template){
			$details = ['template_id'=>$template['id']] + $datas;
			$end_detail = $this->table(['express_carriage_outer_template_details'])->add($details);
			if($end_detail < 0){
				$end2 = false;
				break;
			}
		}
		if($end1 > 0 && $end2){
			$this->end();
			return $end1;
		}else{
			$this->rollback();
			return -1;
		}
	}
	
	/**
	 * public integer get_carriage_inner_template_num(void)
	 */
	public function get_carriage_inner_template_num(): int {
		$ends = $this->field(['template_num'=>'COUNT(*)'])->table(['express_carriage_inner_templates'])->select();
		return $ends[0]['template_num'] ?? -1;
	}
	
	/**
	 * public array get_carriage_inner_template_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_carriage_inner_template_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['ecit.id', 'ecit.code', 'ecit.name', 'product_num'=>'COUNT(`p`.`id`)', 'ecit.add_time']);
		$this->table(['ecit'=>'express_carriage_inner_templates']);
		$this->join(['p'=>'products', 'ecit.id'=>'p.carriage_inner_template_id'], 'left');
		$this->group(['ecit.id'])->order(['ecit.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_carriage_inner_template_record(integer $template_id)
	 */
	public function get_carriage_inner_template_record(int $template_id): array {
		$this->field(['ecit.id', 'ecit.code', 'ecit.name', 'product_num'=>'COUNT(`p`.`id`)', 'ecit.add_time']);
		$this->table(['ecit'=>'express_carriage_inner_templates']);
		$this->join(['p'=>'products', 'ecit.id'=>'p.carriage_inner_template_id'], 'left');
		$ends = $this->where(['ecit.id'=>$template_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array get_carriage_inner_template_detail_record(integer $template_id)
	 */
	public function get_carriage_inner_template_detail_record(int $template_id): array {
		$this->field(['ecitd.id', 'eap.id', 'eap.name', 'ecitd.basic_carriage', 'ecitd.each_plus_carriage', 'ecited.add_time']);
		$this->table(['ecitd'=>'express_carriage_inner_template_details']);
		$this->join(['eap'=>'express_address_provinces', 'ecitd.province_id'=>'eap.id']);
		$this->where(['ecitd.template_id'=>$template_id]);
		$this->order(['eap.id'=>'asc']);
		return $this->select();
	}
	
	/**
	 * public integer delete_carriage_inner_template(integer $template_id)
	 */
	public function delete_carriage_inner_template(int $template_id): int {
		$datas = $this->field(['product_num'=>'COUNT(*)'])->table(['products'])->where(['carriage_inner_template_id'=>$template_id])->select();
		if(isset($datas[0])){
			$product_num = $datas[0]['product_num'];
			if($product_num > 0) return -2;
			$this->begin();
			$end1 = $this->table(['express_carriage_inner_template_details'])->where(['template_id'=>$template_id])->delete();
			$end2 = $this->table(['express_carriage_inner_template'])->where(['id'=>$template_id])->delete();
			if($end1 > 0 && $end2 > 0){
				$this->end();
				return 1;
			}else{
				$this->rollback();
				return -1;
			}
		}
		return -1;
	}
	
	/**
	 * public boolean edit_carriage_inner_template(integer $template_id, array $datas, array $details)
	 * @$datas = [string $code, string $name]
	 * @$details = [integer $template_id, interger $region_id, integer $province_id, float $basic_carriage, float $each_plus_carriage]
	 */
	public function edit_carriage_inner_template(int $template_id, array $datas, array $details): bool {
		$this->begin();
		$end1 = $this->table(['express_carriage_inner_templates'])->where(['id'=>$template_id])->modify(datas);
		$end2 = $this->table(['express_carriage_inner_template_details'])->where(['template_id'=>$template_id])->delete();
		$end3 = true;
		foreach($details as $data){
			$end_detail = $this->table(['express_carriage_inner_template_details'])->add($data);
			if($end_detail < 0){
				$end3 = false;
				break;
			}
		}
		if($end1 > 0 && $end2 > 0 && $end3){
			$this->end();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	
	/**
	 * public integer add_carriage_inner_template(array $datas, array $details)
	 * @$datas = [string $code, string $name]
	 * @$details = [integer $template_id, interger $region_id, integer $province_id, float $basic_carriage, float $each_plus_carriage]
	 */
	public function add_carriage_inner_template(array $datas, array $details): int {
		$this->begin();
		$end1 = $this->table(['express_carriage_inner_templates'])->add($datas);
		$end2 = true;
		if($end1 > 0) $end1 = $this->get_last_id();
		foreach($details as $data){
			$end_detail = $this->table(['express_carriage_inner_template_details'])->add($data);
			if($end_detail < 0){
				$end2 = false;
				break;
			}
		}
		if($end1 > 0 && $end2 > 0){
			$this->end();
			return $end1;
		}else{
			$this->rollback();
			return -1;
		}
	}
	
	/**
	 * public integer get_carriage_outer_template_num(void)
	 */
	public function get_carriage_outer_template_num(): int {
		$ends = $this->field(['template_num'=>'COUNT(*)'])->table(['express_carriage_outer_templates'])->select();
		return $ends[0]['template_num'] ?? -1;
	}
	
	/**
	 * public array get_carriage_outer_template_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_carriage_outer_template_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['ecot.id', 'ecot.code', 'ecot.name', 'product_num'=>'COUNT(`p`.`id`)', 'ecot.add_time']);
		$this->table(['ecot'=>'express_carriage_outer_templates']);
		$this->join(['p'=>'products', 'ecot.id'=>'p.carriage_outer_template_id'], 'left');
		$this->group(['ecot.id'])->order(['ecot.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_carriage_outer_template_record(integer $template_id)
	 */
	public function get_carriage_outer_template_record(int $template_id): array {
		$this->field(['ecot.id', 'ecot.code', 'ecot.name', 'product_num'=>'COUNT(`p`.`id`)', 'ecot.add_time']);
		$this->table(['ecot'=>'express_carriage_outer_templates']);
		$this->join(['p'=>'products', 'ecot.id'=>'p.carriage_outer_template_id'], 'left');
		$ends = $this->where(['ecot.id'=>$template_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array get_carriage_outer_template_detail_record(integer $template_id)
	 */
	public function get_carriage_outer_template_detail_record(int $template_id): array {
		$this->field(['ecotd.id', 'ear.id', 'ear.code', 'ear.name', 'ecotd.basic_carriaage', 'ecotd.each_plus_carriage', 'ecotd.tax_rate', 'ecotd.add_time']);
		$this->table(['ecotd'=>'express_carriage_outer_template_details']);
		$this->join(['ear'=>'express_address_regions', 'ecotd.region_id'=>'ear.id']);
		$this->where(['ecotd.template_id'=>$template_id]);
		$this->order(['ear.id'=>'asc']);
		return $this->select();
	}
	
	/**
	 * public integer delete_carriage_outer_template(integer $template_id)
	 */
	public function delete_carriage_outer_template(int $template_id): int {
		$datas = $this->field(['product_num'=>'COUNT(*)'])->table(['products'])->where(['carriage_outer_template_id'=>$template_id])->select();
		if(isset($datas[0])){
			$product_num = $datas[0]['product_num'];
			if($product_num > 0) return -2;
			$this->begin();
			$end1 = $this->table(['express_carriage_outer_template_details'])->where(['template_id'=>$template_id])->delete();
			$end2 = $this->table(['express_carriage_outer_template'])->where(['id'=>$template_id])->delete();
			if($end1 > 0 && $end2 > 0){
				$this->end();
				return 1;
			}else{
				$this->rollback();
				return -1;
			}
		}
		return -1;
	}
	
	/**
	 * public boolean function edit_carriage_outer_template(integer $template_id, array $datas, array $details)
	 * @$datas = [string $code, string $name]
	 * @$details = [integer $template_id, interger $region_id, float $basic_carriage, float $each_plus_carriage, float $tax_rate]
	 */
	public function edit_carriage_outer_template(int $template_id, array $datas, array $details): bool {
		$this->begin();
		$end1 = $this->table(['express_carriage_outer_templates'])->where(['id'=>$template_id])->modify($datas);
		$end2 = $this->table(['express_carriage_outer_template_details'])->where(['template_id'=>$template_id])->delete();
		$end3 = true;
		foreach($details as $data){
			$end_detail = $this->table(['express_carriage_outer_template_details'])->add($data);
			if($end_detail < 0){
				$end3 = false;
				break;
			}
		}
		if($end1 > 0 && $end2 > 0 && $end3){
			$this->end();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	
	/**
	 * public integer function add_carriage_outer_template(array $datas, array $details)
	 * @$datas = [string $code, string $name]
	 * @$details = [integer $template_id, interger $region_id, float $basic_carriage, float $each_plus_carriage, float $tax_rate]
	 */
	public function add_carriage_outer_template(array $datas, array $details): int {
		$this->begin();
		$end1 = $this->table(['express_carriage_outer_templates'])->add($datas);
		$end2 = true;
		if($end1 > 0) $end1 = $this->get_last_id();
		foreach($details as $data){
			$end_detail = $this->table(['express_carriage_outer_template_details'])->add($data);
			if($end_detail < 0){
				$end2 = false;
				break;
			}
		}
		if($end1 > 0 && $end2 > 0){
			$this->end();
			return $end1;
		}else{
			$this->rollback();
			return -1;
		}
	}
	
	/**
	 * public interger num(void)
	 */
	public function num(): int {
		$ends = $this->field(['express_num'=>'COUNT(*)'])->table(['expresses'])->select();
		return $ends[0]['express_num'] ?? -1;
	}
	
	/**
	 * public array page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = ($page_num - 1) * $page_length;
		$this->field(['e.id', 'order_id'=>'o.id', 'order_unique_id'=>'o.unique_id', 'coporation_id'=>'ec.id', 'corporation_name'=>'ec.name', 'e.code', 'e.carriage', 'e.address', 'e.receiver', 'e.phone', 'e.add_time']);
		$this->table(['e'=>'expresses'])->join(['ec'=>'express_corporations', 'e.corporation_id'=>'ec.id']);
		$this->join(['o'=>'orders', 'e.order_id'=>'o.id'])->order(['e.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array record(integer $express_id)
	 */
	public function record(int $express_id): array {
		$this->field(['e.id', 'order_id'=>'o.id', 'order_unique_id'=>'o.unique_id', 'coporation_id'=>'ec.id', 'corporation_name'=>'ec.name', 'e.code', 'e.carriage', 'e.address', 'e.receiver', 'e.phone', 'e.add_time']);
		$this->table(['e'=>'expresses'])->join(['ec'=>'express_corporations', 'e.corporation_id'=>'ec.id']);
		$ends = $this->join(['o'=>'orders', 'e.order_id'=>'o.id'])->where(['e.id'=>$express_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean remove(integer $express_id)
	 */
	public function remove(int $express_id): bool {
		$end = $this->table(['expresses'])->where(['id'=>$express_id])->delete();
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean edit(integer $express_id, array $datas)
	 * @$datas = [integer $corporation_id, string $code]
	 */
	public function edit(int $express_id, array $datas): bool {
		$end = $this->table(['expresses'])->where(['id'=>$express_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer create(array $datas)
	 * @$datas = [integer $order_id, integer $corporation_id, string $code, float $carriage, string $address, string $receiver, string $phone]
	 */
	public function create(array $datas): int {
		$end = $this->table(['expresses'])->add($datas);
		return $end > 0 ? $this->get_last_id() : $end;
	}
	
	//
}










