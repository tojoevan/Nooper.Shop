<?php

// declare(strict_types = 1);
namespace NooperShop;

use Nooper\Mysql;

class Product extends Mysql {
	
	/**
	 * Const
	 */
	const page_record_num = 20;
	
	/**
	 * Property
	 */
	protected $param_max_product_num;
	
	/**
	 * public string unique_id(void)
	 */
	public function unique_id(): string {
		$unique = new Unique();
		do{
			$unique_id = $unique->gift_card();
			$datas = $this->field(['num'=>'COUNT(*)'])->table(['products'])->where(['unique_id'=>$unique_id])->select();
			if(isset($datas[0]) && $datas[0]['num'] > 0) continue;
			break;
		}while(true);
		return $unique_id;
	}
	
	/**
	 * public integer get_category_num(void)
	 */
	public function get_category_num(): int {
		$ends = $this->field(['category_num'=>'COUNT(*)'])->table(['product_categories'])->select();
		return $ends[0]['category_num'] ?? -1;
	}
	
	/**
	 * public array get_category_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_category_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset = $page_length * ($page_num - 1);
		$this->_category_view()->group(['pc.id'])->order(['pc.position'=>'desc']);
		$this->limit($page_length, $offset);
		return $this->select();
	}
	
	/**
	 * public array get_category_record(integer $category_id)
	 */
	public function get_category_record(int $category_id): array {
		$ends = $this->_category_view()->where(['pc.id'=>$category_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean delete_category(integer $category_id)
	 */
	public function delete_category(int $category_id): bool {
		$datas = $this->field(['children_num'=>'COUNT(*)'])->table(['product_children_categories'])->where(['category_id'=>$category_id])->select();
		if(isset($datas[0]) && $datas[0]['children_num']) $children_num = $datas[0]['children_num'];
		else return false;
		$datas = $this->field(['product_num'=>'COUNT(*)'])->table(['products'])->where(['category_id'=>$category_id])->select();
		if(isset($datas[0]) && $datas[0]['product_num']) $product_num = $datas[0]['product_num'];
		else return false;
		$end = $this->table(['product_categories'])->where(['id'=>$category_id])->delete();
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean edit_category(integer $category_id, array $datas)
	 * @$datas = [string $code, string $name, integer $place]
	 */
	public function edit_category(int $category_id, array $datas): bool {
		$end = $this->table(['product_categories'])->where(['id'=>$category_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public ?integer add_category(array $datas)
	 * @$datas = [string $code, string $name, integer $place]
	 */
	public function add_category(array $datas): ?int {
		$end = $this->table(['product_categories'])->add($datas);
		return $end > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * public integer get_children_category_num(integer $category_id)
	 */
	public function get_children_category_num(int $category_id): int {
		$this->field(['children_category_num'=>'COUNT(*)'])->table(['product_children_categories']);
		$ends = $this->where(['category_id'=>$category_id])->select();
		return $ends[0]['children_category_num'] ?? -1;
	}
	
	/**
	 * public array get_children_category_page(integer $category_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_children_category_page(int $category_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset = $page_length * ($page_num - 1);
		$this->_children_category_view()->where(['pcc.category_id'=>$category_id])->group(['pcc.id'])->order(['pcc.position'=>'desc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array get_children_category_record(integer $children_category_id)
	 */
	public function get_children_category_record(int $children_category_id): array {
		$ends = $this->_children_category_view()->where(['pcc.id'=>$children_category_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean delete_children_category(integer $children_category_id)
	 */
	public function delete_children_category(int $children_category_id): bool {
		$datas = $this->field(['product_num'=>'COUNT(*)'])->table(['products'])->where(['children_category_id'=>$children_category_id])->select();
		if(isset($datas[0]) && 0 == $datas[0]['product_num']){
			$end = $this->table(['product_children_categories'])->where(['id'=>$children_category_id])->delete();
			return $end > 0 ? true : false;
		}
		return false;
	}
	
	/**
	 * public boolean edit_children_category(integer $children_category_id, array $datas)
	 * @$datas = [string $code, string $name, integer $place]
	 */
	public function edit_children_category(int $children_category_id, array $datas): bool {
		$end = $this->table(['product_children_categories'])->where(['id'=>$children_category_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public ?integer add_children_category(array $datas)
	 * @$datas = [integer $category_id, string $code, string $name, boolean $is_default, integer $place]
	 */
	public function add_children_category(array $datas): ?int {
		if(isset($datas['is_default']) && $datas['is_default']){
			if($this->begin()){
				$category_id = $datas['category_id'];
				$end1 = $this->table(['product_children_categories'])->where(['category_id'=>$category_id])->modify(['is_default'=>false]);
				$end2 = $this->table(['product_children_categories'])->add($datas);
				if($end1 > 0 && $end2 > 0 && $this->end()) return $this->get_last_id();
				$this->rollback();
			}
			return -1;
		}else{
			$end = $this->table(['product_children_categories'])->add($datas);
			return $end > 0 ? $this->get_last_id() : -1;
		}
	}
	
	/**
	 * public integer function add_category_properties(integer $category_id, array $datas)
	 */
	public function add_category_properties(int $category_id, array $properties): int {
		$counter = 0;
		foreach($properties as $prop){
			$datas = ['category_id'=>$category_id, 'name'=>$prop];
			$end = $this->table(['product_category_properties'])->add($datas);
			if($end > 0) $counter++;
		}
		return $counter;
	}
	
	/**
	 * public integer get_group_num(void)
	 */
	public function get_group_num(): int {
		$ends = $this->field(['group_num'=>'COUNT(*)'])->table(['product_groups'])->select();
		return $ends[0]['group_num'] ?? -1;
	}
	
	/**
	 * public array get_group_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_group_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_group_view()->group(['pg.id'])->order(['pg.place'=>'desc', 'pg.id'=>'asc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_group_record(integer $group_id)
	 */
	public function get_group_record(int $group_id): array {
		$ends = $this->_group_view()->where(['pg.id'=>$group_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean delete_group(integer $group_id)
	 */
	public function delete_group(int $group_id): bool {
		if($this->begin()){
			$end1 = $this->table(['product_group_details'])->where(['group_id'=>$group_id])->delete();
			$end2 = $this->table(['product_groups'])->where(['id'=>$group_id])->delete();
			if($end1 > 0 && $end2 > 0 && $this->end()) return true;
			$this->rollback();
		}
		return false;
	}
	
	/**
	 * public boolean edit_group(integer $group_id, array $datas)
	 * @$datas = [string $code, string $name, integer $place]
	 */
	public function edit_group(int $group_id, array $datas): bool {
		$end = $this->table(['product_groups'])->where(['id'=>$group_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer add_group(array $datas)
	 */
	public function add_group(array $datas): bool {
		$end = $this->table(['product_groups'])->add($datas);
		return $end > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * public integer get_group_detail_num(integer $group_id)
	 */
	public function get_group_detail_num(int $group_id): int {
		$ends = $this->field(['group_detail_num'=>'COUNT(*)'])->table(['product_group_details'])->select();
		return $ends[0]['group_detail_num'] ?? -1;
	}
	
	/**
	 * public array get_group_detail_page(integer $group_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_group_detail_page(int $group_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_group_detail_view()->where(['pgd.group_id'=>$group_id])->order(['pgd.place'=>'desc', 'pgd.id'=>'asc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array get_group_detail_record(integer $group_detail_id)
	 */
	public function get_group_detail_record(int $group_detail_id): array {
		$ends = $this->_group_detail_view()->where(['pgd.id'=>$group_detail_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean delete_group_detail(integer $group_detail_id)
	 */
	public function delete_group_detail(int $group_detail_id): bool {
		$end = $this->table(['product_group_details'])->where(['id'=>$group_detail_id])->delete();
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean edit_group_detail(integer $group_detail_id, array $datas)
	 * @$datas = [integer $place]
	 */
	public function edit_group_detail(int $group_detail_id, array $datas): bool {
		$end = $this->table(['product_group_details'])->where(['id'=>$group_detail_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public ?integer add_group_detail(array $datas)
	 * @$datas = [integer $group_id, integer $product_id, integer $place]
	 */
	public function add_group_detail(array $datas): ?int {
		$end = $this->table(['product_group_details'])->add();
		return $end > 0 ? $this->get_last_id() : -1;
	}
	
	/**
	 * public integer num(void)
	 */
	public function num(): int {
		$ends = $this->field(['product_num'=>'COUNT(*)'])->table(['products'])->where(['status'=>'deleted'], 'neq')->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public integer get_prepared_num(void)
	 */
	public function get_prepared_num(): int {
		$ends = $this->field(['product_num'=>'COUNT(*)'])->table(['products'])->where(['status'=>'prepared'])->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public array get_prepared_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_prepared_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_view()->where(['p.status'=>'prepared'])->order(['p.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_online_num(void)
	 */
	public function get_online_num(): int {
		$ends = $this->field(['product_num'=>'COUNT(*)'])->table(['products'])->where(['status'=>'online'])->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public array get_online_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_online_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_view()->where(['p.status'=>'online'])->order(['p.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_offline_num(void)
	 */
	public function get_offline_num(): int {
		$ends = $this->field(['product_num'=>'COUNT(*)'])->table(['products'])->where(['status'=>'offline'])->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public array get_offline_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_offline_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_view()->where(['p.status'=>'offline'])->order(['p.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer get_deleted_num(void)
	 */
	public function get_deleted_num(): int {
		$ends = $this->field(['product_num'=>'COUNT(*)'])->table(['products'])->where(['status'=>'deleted'])->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public array get_deleted_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_deleted_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->_view()->where(['p.status'=>'deleted'])->order(['p.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array record(integer $product_id)
	 */
	public function record(int $product_id): array {
		$ends = $this->_view()->where(['p.id'=>$product_id])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public boolean online(integer $product_id)
	 */
	public function online(int $product_id): bool {
		$end = $this->table(['products'])->where(['id'=>$product_id])->modify(['status'=>'online']);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean offline(integer $product_id)
	 */
	public function offline(int $product_id): bool {
		$end = $this->table(['products'])->where(['id'=>$product_id])->modify(['status'=>'offline']);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean delete(integer $product_id)
	 */
	public function delete_item(int $product_id): bool {
		//
	}
	
	/**
	 * public boolean recover(integer $product_id)
	 */
	public function recover(int $product_id): bool {
		$end = $this->table(['products'])->where(['id'=>$product_id])->modify(['status'=>'online']);
		return $end > 0 ? true : false;
	}
	
	/**
	 * protected ?scalar get_param(string $name)
	 */
	protected function get_param(string $name) {
		$datas = $this->table(['product_default_params'])->where(['id'=>1])->select();
		return $datas[0][$name] ?? null;
	}
	
	/**
	 * protected boolean set_param(string $name, ?scalar value)
	 */
	protected function set_param(string $name, $value): bool {
		$end = $this->table(['product_default_params'])->where(['id'=>1])->modify([$name=>$value]);
		return $end > 0 ? true : false;
	}
	
	/**
	 * protected Product _category_view(void)
	 */
	protected function _category_view(): Product {
		$pc_cols = ['pc.id', 'pc.name', 'pc.position', 'pc.add_time'];
		$func_cols = ['children_num'=>'COUNT(`pcc`.`id`)', 'product_num'=>'COUNT(DISTINCT `p`.`id`)'];
		$this->field(array_merge($pc_cols, $func_cols))->table(['pc'=>'product_categories']);
		$this->join(['pcc'=>'product_children_categories', 'pc.id'=>'pcc.category_id'], 'left');
		$this->join(['p'=>'products', 'pc.id'=>'p.category_id'], 'left');
		return $this;
	}
	
	/**
	 * protected Product _children_category_view(void)
	 */
	protected function _children_category_view(): Product {
		//
	}
	
	/**
	 * protected Product _group_view(void)
	 */
	protected function _group_view(): Product {
		$this->field(['pg.id', 'pg.code', 'pg.name', 'product_num'=>'count(`pgd`.`product_id`)', 'pg.position', 'pg.add_time']);
		$this->table(['pg'=>'product_groups'])->join(['pgd'=>'product_group_details', 'pg.id'=>'pgd.group_id'], 'left');
		return $this;
	}
	
	/**
	 * protected Product _group_detail_view(void)
	 */
	protected function _group_detail_view(): Product {
		//
	}
	
	/**
	 * protected Product _view(void)
	 */
	protected function _view(): Product {
		$this->field(['category_id'=>'pc.id', 'category_code'=>'pc.code', 'children_category_id'=>'pcc.id', 'children_category_code'=>'pcc.code', 'p.id', 'p.unique_id', 'p.code', 'p.name', 'p.tag_price', 'p.discount_price', 'sold_num'=>'0', 'p.position', 'p.add_time', 'p.status']);
		$this - table(['p'=>'products'])->join(['pc'=>'product_categories', 'p.category_id'=>'pc.id']);
		$this->join(['pcc'=>'product_children_categories', 'p.children_category_id'=>'pcc.id'], 'left');
	}
	
	/**
	 * public array get_primary_pictures(void)
	 */
	public function get_primary_pictures(): array {
		$this->field(['id', 'url'])->table(['product_pictures']);
		$datas = $this->where(['is_primary'=>true])->group(['product_id'])->order(['product_id'=>'asc'])->select();
		foreach($datas as $data){
			$ends[$data['id']] = $data['url'];
		}
		return $ends ?? [];
	}
	//
}











