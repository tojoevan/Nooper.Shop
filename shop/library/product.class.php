<?php
// declare(strict_types = 1);
namespace Nooper;

class Product extends Mysql {
	
	/**
	 * public array function get_category_page(integer $page_num, integer $page_length = 20)
	 */
	public function get_category_page(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['pc.id', 'pc.name', 'pc.position'])->table(['pc'=>'product_categories'])->where(['pc.parent_id'=>'0']);
		$this->order(['pc.position'=>'desc'])->limit($page_length, $offset_num);
		$ends = $this->select();
	}
	
	/**
	 * public boolean function add_category(string $name, integer $parent_id = 0)
	 */
	public function add_category(string $name, int $parent_id = 0): bool {
		$datas = ['name'=>$name, 'parent_id'=>$parent_id];
		$end = $this->table(['product_categories'])->add($datas);
		return $end > 0 ? true : false;
	}
	
	/**
	 * public integer function add_category_properties(integer $category_id, array $datas)
	 */
	public function add_category_properties(int $category_id, array $properties):int {
		$counter=0;
		foreach($properties as $prop){
			$datas=['category_id'=>$category_id, 'name'=>$prop];
			$end = $this->table(['product_category_properties'])->add($datas);
			if($end>0) $counter++;
		}
		return $counter;
	}
	
	/**
	 * public integer function num(void)
	 */
	public function num(): int {
		$ends = $this->field(['product_num'=>'count(*)'])->table(['products'])->where_cmd("`status`!='deleted'")->select();
		return $ends[0]['product_num'] ?? -1;
	}
	
	/**
	 * public array function page(integer $page_num = 1, integer $page_length = 20)
	 */
	public function page(int $page_num = 1, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['p.id', 'p.unique_id', 'p.code', 'p.name', 'p.tag_price', 'p.discount_price', 'p.position', 'p.add_time', 'p.status']);
		$this - table(['p'=>'products']);
		$this->where_cmd("`p`.`status`!='deleted'")->order(['p.id'=>'desc']);
		$this->limit($page_length, $offset_num);
		$ends = $this->select();
		if($ends){
			$category_names = $this->get_category_names();
			$sale_nums = $this->get_sale_nums();
			foreach($ends as $data){
				$id = $data['id'];
				$data['category'] = $category_names[$id] ?? null;
				$data['sale_num'] = $sale_nums[$id] ?? 0;
			}
		}
		return $ends;
	}
	
	/**
	 * public array function item(integer $product_id)
	 */
	public function item(int $product_id): array {
		/* -- ? -- */
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
	 * public integer function get_group_num(void)
	 */
	public function get_group_num(): int {
		$ends = $this->field(['group_num'=>'count(*)'])->table(['pg'=>'product_groups'])->select();
		return $ends[0]['group_num'] ?? -1;
	}
	
	/**
	 * public array function get_group_page(integer $page_num, integer $page_length = 20)
	 */
	public function get_group_page(int $page_num, int $page_length = 20): array {
		$offset_num = $page_length * ($page_num - 1);
		$this->field(['pg.id', 'pg.code', 'pg.name', 'pg.position', 'product_num'=>'count(`pgd`.`product_id`)', 'pg.add_time']);
		$this->table(['pg'=>'product_groups'])->join(['pgd'=>'product_group_details', 'pg.id'=>'pgd.group_id'], 'left');
		$this->group(['pg.id'])->order(['pg.position'=>'desc'])->limit($page_length, $offset_num);
		return $this->select();
	}
	
	/**
	 * public array function get_group(integer $group_id)
	 */
	public function get_group(int $group_id): array {
		$this->field(['pg.id', 'pg.code', 'pg.name', 'pg.position', 'product_num'=>'count(`pgd`.`product_id`)', 'pg.add_time']);
		$this->table(['pg'=>'product_groups'])->join(['pgd'=>'product_group_details', 'pg.id'=>'pgd.group_id'], 'left');
		$ends = $this->where(['pg.id'=>(string)$group_id])->group(['pg.id'])->select();
		return $ends[0] ?? [];
	}
	
	/**
	 * public array function get_page_by_group_id(integer $group_id)
	 */
	public function get_page_by_group_id(int $group_id): array {
		/* -- ? -- */
	}
	
	/**
	 * public boolean function delete_group(integer $group_id)
	 */
	public function delete_group(int $group_id): bool {
		$this->begin();
		$end1 = $this->table(['product_group_details'])->where(['group_id'=>(string)$group_id])->delete();
		$end2 = $this->table(['product_groups'])->where(['id'=>(string)$group_id])->delete();
		if($end1 >= 0 && $end2 >= 0){
			$this->end();
			return true;
		}
		$this->rollback();
		return false;
	}
	
	/**
	 * protected array function get_category_property_nums(void)
	 */
	protected function get_category_property_nums(): array {
		$this->field(['pc.id', 'num'=>'count(*)'])->table(['pc'=>'product_categories']);
		$this->join(['pcp'=>'product_category_properties', 'pc.id'=>'pcp.category_id'], 'left');
		$datas = $this->group(['pc.id'])->order(['pc.id'=>'desc'])->select();
		foreach($datas as $data){
			list('id'=>$id, 'num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
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
	 * public array function get_sale_nums(void)
	 */
	protected function get_sale_nums(): array {
		$this->field(['od.product_id', 'sale_num'=>'sum(`od`.`quantity`)']);
		$this->table(['o'=>'orders'])->join(['od'=>'order_details', 'o.id'=>'od.order_id']);
		$this->where(['o.status'=>"'completed'"])->group('od.product_id')->order(['od.product_id'=>'asc']);
		$datas = $this->select();
		foreach($datas as $data){
			list('product_id'=>$id, 'sale_num'=>$num)=$data;
			$ends[$id] = $num;
		}
		return $ends ?? [];
	}
	
	//
}











