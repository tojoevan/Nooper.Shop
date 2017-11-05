<?php
// declare(strict_types = 1);
namespace Nooper;

class Product extends Mysql {
	
	/**
	 * Const
	 */
	const page_record_num = 20;
	
	/**
	 * public integer function get_category_num(void)
	 */
	public function get_category_num(): int {
		$ends = $this->field(['category_num'=>'count(*)'])->table(['product_categories'])->select();
		return $ends[0]['category_num'] ?? -1;
	}
	
	/**
	 * public array function get_category_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_category_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset = $page_length * ($page_num - 1);
		$this->field(['pc.id', 'pc.name', 'property_num'=>'count(`pcp`.`id`)', 'children_num'=>'count(`pcc`.`id`)', 'product_num'=>'count(distinct `p`.`id`)', 'pc.position', 'pc.add_time']);
		$this->table(['pc'=>'product_categories'])->join(['pcp'=>'product_category_properties', 'pc.id'=>'pcp.category_id'], 'left');
		$this->join(['pcc'=>'product_children_categories', 'pc.id'=>'pcc.parent_id'], 'left');
		$this->join(['p'=>'products', 'pc.id'=>'p.category_id'], 'left')->group(['pc.id'])->order(['pc.position'=>'desc']);
		return $this->limit($page_length, $offset)->select();
	}
	
	/**
	 * public array function get_category_record(integer $category_id)
	 */
	public function get_category_record(int $category_id): array {
		//
	}
	
	/**
	 * public boolean function delete_category(integer $category_id)
	 */
	public function delete_category(int $category_id): bool {
		$children_num = $this->get_children_category_num_by_category_id($category_id);
		if($children_num > 0) return false;
		$product_num = $this->get_num_by_category_id($category_id);
		if($product_num > 0) return false;
		$end = $this->table(['product_categories'])->where(['id'=>(string)$category_id])->delete();
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean function edit_category(integer $category_id, array $datas)
	 */
	public function edit_category(int $category_id, array $datas): bool {
		$end = $this->table(['product_categories'])->where(['id'=>(string)$category_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean function add_category(array $datas)
	 */
	public function add_category(array $datas): bool {
		$end = $this->table(['product_categories'])->add($datas);
	}
	
	/**
	 * public array function get_children_category_page(integer $category_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_children_category_page(int $category_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset = $page_length * ($page_num - 1);
		$this->field(['pcc.id', 'pcc.name', 'pcc.position', 'pcc.add_time']);
		$this->table(['pcc'=>'product_children_categories']);
		$this->where()->order();
	}
	
	/**
	 * public boolean function add_children_category(y b
	 */
	
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
	 * public integer function get_group_num(void)
	 */
	public function get_group_num(): int {
		$ends = $this->field(['group_num'=>'count(*)'])->table(['product_groups'])->select();
		return $ends[0]['group_num'] ?? -1;
	}
	
	/**
	 * public array function get_group_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_group_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset = $page_length * ($page_num - 1);
		$this->field(['pg.id', 'pg.code', 'pg.name', 'product_num'=>'count(`pgd`.`product_id`)', 'pg.position', 'pg.add_time']);
		$this->table(['pg'=>'product_groups'])->join(['pgd'=>'product_group_details', 'pg.id'=>'pgd.group_id'], 'left');
		$this->group(['pg.id'])->order(['pg.position'=>'desc'])->limit($page_length, $offset);
		return $this->select();
	}
	
	/**
	 * public array function get_group_record(integer $group_id)
	 */
	public function get_group_record(int $group_id): array {
		$this->field(['pg.id', 'pg.code', 'pg.name', 'product_num'=>'count(`pgd`.`product_id`)', 'pg.position', 'pg.add_time']);
		$this->table(['pg'=>'product_groups'])->join(['pgd'=>'product_group_details', 'pg.id'=>'pgd.group_id'], 'left');
		$ends = $this->where(['pg.id'=>(string)$group_id])->group(['pg.id'])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array function get_page_by_group_id(integer $group_id, integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_page_by_group_id(int $group_id, int $page_num = 1, int $page_length = self::page_record_num): array {
		//
	}
	
	/**
	 * public boolean function delete_group(integer $group_id)
	 */
	public function delete_group(int $group_id): bool {
		$this->begin();
		$end1 = $this->table(['product_group_details'])->where(['group_id'=>(string)$group_id])->delete();
		$end2 = $this->table(['product_groups'])->where(['id'=>(string)$group_id])->delete();
		if($end1 >= 0 && $end2 > 0){
			$this->end();
			return true;
		}
		$this->rollback();
		return false;
	}
	
	/**
	 * public boolean function edit_group(integer $group_id, array $datas)
	 */
	public function edit_group(int $group_id, array $datas): bool {
		$end = $this->table(['product_groups'])->where(['id'=>(string)$group_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean function add_group(array $datas)
	 */
	public function add_group(array $datas): bool {
		$end = $this->table(['product_groups'])->add($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer function num(void)
	 */
	public function num(): int {
		$ends = $this->field(['product_num'=>'count(*)'])->table(['products'])->where(['status'=>'deleted'], 'neq')->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public array function page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['category_id'=>'pc.id', 'category_code'=>'pc.code', 'children_category_id'=>'pcc.id', 'children_category_code'=>'pcc.code', 'p.id', 'p.unique_id', 'p.code', 'p.name', 'p.tag_price', 'p.discount_price', 'sold_num'=>'0', 'p.position', 'p.add_time', 'p.status']);
		$this - table(['p'=>'products'])->join(['pc'=>'product_categories', 'p.category_id'=>'pc.id']);
		$this->join(['pcc'=>'product_children_categories', 'p.children_category_id'=>'pcc.id'], 'left');
		$this->where(['p.status'=>"'deleted'"], 'neq')->order(['p.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		$ends = $this->select();
		$sold_nums = $this->get_sold_nums();
		foreach($ends as &$data){
			$product_id = $data['id'];
			$data['sold_num'] = $sold_nums[$product_id] ?? 0;
		}
		return $ends;
	}
	
	/**
	 * public integer function get_prepared_num(void)
	 */
	public function get_prepared_num(): int {
		$ends = $this->field(['product_num'=>'count(*)'])->table(['products'])->where(['status'=>'prepared'])->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public array function get_prepared_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_prepared_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['category_id'=>'pc.id', 'category_code'=>'pc.code', 'children_category_id'=>'pcc.id', 'children_category_code'=>'pcc.code', 'p.id', 'p.unique_id', 'p.code', 'p.name', 'p.tag_price', 'p.discount_price', 'p.position', 'p.add_time', 'p.status']);
		$this - table(['p'=>'products'])->join(['pc'=>'product_categories', 'p.category_id'=>'pc.id']);
		$this->join(['pcc'=>'product_children_categories', 'p.children_category_id'=>'pcc.id'], 'left');
		$this->where(['p.status'=>"'prepared'"])->order(['p.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public integer function get_online_num(void)
	 */
	public function get_online_num(): int {
		$ends = $this->field(['product_num'=>'count(*)'])->table(['products'])->where(['status'=>'online'])->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public array function get_online_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_online_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['category_id'=>'pc.id', 'category_code'=>'pc.code', 'children_category_id'=>'pcc.id', 'children_category_code'=>'pcc.code', 'p.id', 'p.unique_id', 'p.code', 'p.name', 'p.tag_price', 'p.discount_price', 'sold_num'=>'0', 'p.position', 'p.add_time', 'p.status']);
		$this - table(['p'=>'products'])->join(['pc'=>'product_categories', 'p.category_id'=>'pc.id']);
		$this->join(['pcc'=>'product_children_categories', 'p.children_category_id'=>'pcc.id'], 'left');
		$this->where(['p.status'=>"'online'"])->order(['p.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		$ends = $this->select();
		$sold_nums = $this->get_sold_nums();
		foreach($ends as &$data){
			$product_id = $data['id'];
			$data['sold_num'] = $sold_nums[$product_id] ?? 0;
		}
		return $ends;
	}
	
	/**
	 * public integer function get_offline_num(void)
	 */
	public function get_offline_num(): int {
		$ends = $this->field(['product_num'=>'count(*)'])->table(['products'])->where(['status'=>'offline'])->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public array function get_offline_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_offline_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['category_id'=>'pc.id', 'category_code'=>'pc.code', 'children_category_id'=>'pcc.id', 'children_category_code'=>'pcc.code', 'p.id', 'p.unique_id', 'p.code', 'p.name', 'p.tag_price', 'p.discount_price', 'sold_num'=>'0', 'p.position', 'p.add_time', 'p.status']);
		$this - table(['p'=>'products'])->join(['pc'=>'product_categories', 'p.category_id'=>'pc.id']);
		$this->join(['pcc'=>'product_children_categories', 'p.children_category_id'=>'pcc.id'], 'left');
		$this->where(['p.status'=>"'offline'"])->order(['p.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		$ends = $this->select();
		$sold_nums = $this->get_sold_nums();
		foreach($ends as &$data){
			$product_id = $data['id'];
			$data['sold_num'] = $sold_nums[$product_id] ?? 0;
		}
		return $ends;
	}
	
	/**
	 * public integer function get_deleted_num(void)
	 */
	public function get_deleted_num(): int {
		$ends = $this->field(['product_num'=>'count(*)'])->table(['products'])->where(['status'=>'deleted'])->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public array function get_deleted_page(integer $page_num = 1, integer $page_length = self::page_record_num)
	 */
	public function get_deleted_page(int $page_num = 1, int $page_length = self::page_record_num): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['category_id'=>'pc.id', 'category_code'=>'pc.code', 'children_category_id'=>'pcc.id', 'children_category_code'=>'pcc.code', 'p.id', 'p.unique_id', 'p.code', 'p.name', 'p.tag_price', 'p.discount_price', 'sold_num'=>'0', 'p.position', 'p.add_time', 'p.status']);
		$this - table(['p'=>'products'])->join(['pc'=>'product_categories', 'p.category_id'=>'pc.id']);
		$this->join(['pcc'=>'product_children_categories', 'p.children_category_id'=>'pcc.id'], 'left');
		$this->where(['p.status'=>"'deleted'"])->order(['p.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		$ends = $this->select();
		$sold_nums = $this->get_sold_nums();
		foreach($ends as &$data){
			$product_id = $data['id'];
			$data['sold_num'] = $sold_nums[$product_id] ?? 0;
		}
		return $ends;
	}
	
	/**
	 * public array function record(integer $product_id)
	 */
	public function record(int $product_id): array {
		//
	}
	
	/**
	 * public boolean function online(integer $product_id)
	 */
	public function online(int $product_id): bool {
		$datas = ['status'=>'online'];
		$end = $this->table(['products'])->where(['id'=>(string)$product_id])->modify($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public boolean function offline(integer $product_id)
	 */
	public function offline(int $product_id): bool {
		/* -- ? -- */
	}
	
	/**
	 * public boolean function delete(integer $product_id)
	 */
	public function delete_item(int $product_id): bool {
		/* -- ? -- */
	}
	
	/**
	 * public boolean function recover(integer $product_id)
	 */
	public function recover(int $product_id): bool {
		/* -- ? -- */
	}
	
	/**
	 * protected array function get_category_names(void)
	 */
	protected function get_category_names(): array {
		$this->field(['a.id', 'parent_name'=>'b.name', 'a.name']);
		$this->table(['a'=>'product_categories'])->join(['b'=>'product_categories', 'a.parent_id'=>'b.id'], 'left');
		$this->order(['a.id'=>'asc']);
		return $this->select();
	}
	
	/**
	 * protected array function get_sold_nums(void)
	 */
	protected function get_sold_nums(): array {
		$this->field(['od.product_id', 'product_num'=>'sum(`od`.`quantity`)']);
		$this->table(['o'=>'orders'])->join(['od'=>'order_details', 'o.id'=>'od.order_id']);
		$this->where(['o.status'=>"'completed'"])->group('od.product_id')->order(['od.product_id'=>'asc']);
		$datas = $this->select();
		foreach($datas as $data){
			list('product_id'=>$id, 'product_num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	/**
	 * protected integer function get_children_category_num_by_category_id(integer $category_id)
	 */
	protected function get_children_category_num_by_category_id(int $category_id): int {
		$this->field(['children_category_num'=>'count(*)'])->table(['product_children_categories']);
		$ends = $this->where(['parent_id'=>(string)$category_id])->select();
		return $ends[0]['children_category_num'] ?? -1;
	}
	
	/**
	 * protected integer function get_num_by_category_id(integer $category_id)
	 */
	protected function get_num_by_category_id(int $category_id): int {
		$this->field(['product_num'=>'count(*)'])->table(['products']);
		$ends = $this->where(['category_id'=>(string)$category_id])->select();
		return $ends[0]['product_num'] ?? -1;
	}
	//
}











