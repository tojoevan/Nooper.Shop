<?php
// declare(strict_types = 1);
namespace Nooper;

use PDO;
use PDOException;

class Mysql {
	
	/**
	 * Properties
	 */
	protected $id;
	protected $sql;
	protected $error;
	protected $driver_options = array(PDO::ATTR_CASE=>PDO::CASE_LOWER, PDO::ATTR_ERRMODE=>PDO::ERRMODE_SILENT, PDO::ATTR_ORACLE_NULLS=>PDO::NULL_NATURAL, PDO::ATTR_STRINGIFY_FETCHES=>false);
	protected $connect_params = array();
	protected $sql_cmds = array('distinct', 'field', 'table', 'join', 'where', 'group', 'having', 'order', 'limit');
	protected $sql_datas = array();
	protected $database;
	protected $ds;
	
	/**
	 * public void function __construct(?array $connect_params = null)
	 */
	public function __construct(array $connect_params = null) {
		if(is_array($connect_params) && is_database_connect_params($connect_params)) $this->connect_params = $connect_params;
		else $this->connect_params = get_config('database_connect_params', array());
	}
	
	/**
	 * public void function __destruct(void)
	 */
	public function __destruct() {
		$this->close();
	}
	
	/**
	 * public ?string function __get(string $cmd)
	 */
	public function __get(string $cmd): ?string 








{
	return $this->sql_datas[$cmd] ?? null;
}
	
	/**
	 * public Mysql function distinct(boolean $data)
	 */
	public function distinct(bool $data): Mysql {
		$this->sql('distinct', $data ? 'distinct' : 'all');
		return $this;
	}
	
	/**
	 * public Mysql function distinct_cmd(string $data)
	 */
	public function distinct_cmd(string $data): Mysql {
		$this->sql('distinct', $data);
		return $this;
	}
	
	/**
	 * public Mysql function field(array $datas)
	 * @$datas = [(string $alias=>string $field)|(string $field),...]
	 */
	public function field(array $datas): Mysql {
		foreach($datas as $key => $data){
			if(!is_string($data)) continue;
			elseif(is_int($key) && is_database_named_regular($data, true)) $ends[] = wrap_database_backquote($data);
			elseif(is_string($key) && is_database_primary_named_regular($key)) $ends[] = (is_database_named_regular($data) ? wrap_database_backquote($data) : $data) . ' as ' . wrap_database_backquote($key);
		}
		if(isset($ends)) $this->sql('field', implode(',', $ends));
		return $this;
	}
	
	/**
	 * public Mysql function field_cmd(string $data)
	 */
	public function field_low(string $data): Mysql {
		$this->sql('field', $data);
		return $this;
	}
	
	/**
	 * public Mysql function table(array $datas)
	 * @$datas = [(string $alias=>string $table)|(string $table),...]
	 */
	public function table(array $datas): Mysql {
		foreach($datas as $key => $data){
			if(!is_string($data)) continue;
			elseif(is_int($key) && is_database_primary_named_regular($data)) $ends[] = wrap_database_backquote($data);
			elseif(is_string($key) && is_database_primary_named_regular($key) && is_database_primary_named_regular($data)) $ends[] = wrap_database_backquote($data) . ' ' . wrap_database_backquote($key);
		}
		if(isset($ends)) $this->sql('table', implode(',', $ends));
		return $this;
	}
	
	/**
	 * public Mysql function table_cmd(string $data)
	 */
	public function table_cmd(string $data): Mysql {
		$this->sql('table', $data);
		return $this;
	}
	
	/**
	 * public Mysql function join(array $datas, string $method = inner|left|right)
	 * @$datas = [(string $alias => string $table)|(string $table), string $left_condition => string $right_condition]
	 */
	public function join(array $datas, string $method = 'inner'): Mysql {
		if(count($datas) != 2) return $this;
		elseif(!in_array($method, ['inner', 'left', 'right'], true)) return $this;
		
		$maps = ['inner'=>'inner join', 'left'=>'left outer join', 'right'=>'right outer join'];
		$plus = $maps[$method];
		
		$keys = array_keys($datas);
		$values = array_values($datas);
		if(is_string($keys[0]) && is_database_primary_named_regular($keys[0]) && is_database_primary_named_regular($values[0])) $memory = wrap_database_backquote($values[0]) . ' ' . wrap_database_backquote($keys[0]);
		elseif(is_int($keys[0]) && is_database_primary_named_regular($values[0])) $memory = wrap_database_backquote($values[0]);
		else return $this;
		
		if(is_database_plus_named_regular($keys[1]) && is_database_plus_named_regular($values[1])) $condition = wrap_database_backquote($keys[1]) . '=' . wrap_database_backquote($values[1]);
		else return $this;
		
		$this->sql('join', implode(' ', [$this->join, $plus, $memory, 'on', $condition]));
		return $this;
	}
	
	/**
	 * public Mysql function join_cmd(string $data)
	 */
	public function join_cmd(string $data): Mysql {
		$this->sql('join', $data);
		return $this;
	}
	
	/**
	 * public Mysql function where(array $datas)
	 * @$datas = [string $field=>string $data,...]
	 */
	public function where(array $datas): Mysql {
		foreach($datas as $key => $data){
			if(is_string($key) &&is_database_named_regular($key)  && is_string($data)) $ends[] = wrap_database_backquote($key) . '=' . $data;
		}
		if(isset($ends)) $this->sql('where', 'where ' . implode(' and ', $ends));
		return $this;
	}
	
	/**
	 * public Mysql function where_cmd(string $data)
	 */
	public function where_cmd(string $data): Mysql {
		$this->sql('where', 'where ' . $data);
		return $this;
	}
	
	/**
	 * public Mysqlfunction group(array $datas)
	 * @$datas = [(string $field=>'asc|desc')|(string $field),...]
	 */
	public function group(array $datas): Mysql {
		foreach($datas as $key => $data){
			if(is_string($key) && is_database_named_regular($key) && in_array($data, ['asc', 'desc'], true)) $ends[] = wrap_database_backquote($key) . ' ' . $data;
			elseif(is_int($key) && is_database_named_regular($data)) $ends[] = wrap_database_backquote($data);
		}
		if(isset($ends)) $this->sql('group', 'group by ' . implode(',', $ends));
		return $this;
	}
	
	/**
	 * public Mysql function group_cmd(string $data)
	 */
	public function group_cmd(string $data): Mysql {
		$this->sql('group', 'group by ' . $data);
		return $this;
	}
	
	/**
	 * public Mysql function having(array $datas)
	 * @$datas = [string $field=>string $data,...]
	 */
	public function having(array $datas): Mysql {
		foreach($datas as $key => $data){
			if(is_string($key) && is_database_primary_named_regular($key) && is_string($data)) $ends[] = wrap_database_backquote($key) . '=' . $data;
		}
		if(isset($ends)) $this->sql('having', 'having ' . implode(' and ', $ends));
		return $this;
	}
	
	/**
	 * public Mysql function having_cmd(string $data)
	 */
	public function having_cmd(string $data): Mysql {
		$this->sql('having', 'having ' . $data);
		return $this;
	}
	
	/**
	 * public Mysql function order(array $datas)
	 * @$datas = [(string $field=>'asc|desc')|(string $field),...]
	 */
	public function order(array $datas): Mysql {
		foreach($datas as $key => $data){
			if(is_string($key) && is_database_named_regular($key) && in_array($data, ['asc', 'desc'], true)) $ends[] = wrap_database_backquote($key) . ' ' . $data;
			elseif(is_int($key) && is_database_named_regular($data)) $ends[] = wrap_database_backquote($data);
		}
		if(isset($ends)) $this->sql('order', 'order by ' . implode(',', $ends));
		return $this;
	}
	
	/**
	 * public Mysql function order_cmd(string $data)
	 */
	public function order_cmd(string $data): Mysql {
		$this->sql('order', 'order by ' . $data);
		return $this;
	}
	
	/**
	 * public Mysql function limit(integer $num, integer $offset = 0)
	 */
	public function limit(int $num, int $offset = 0): Mysql {
		$this->sql('limit', 'limit ' . ($offset != 0 ? $offset . ',' . $num : $num));
		return $this;
	}
	
	/**
	 * public Mysql function limit_cmd(string $data)
	 */
	public function limit_cmd(string $data): Mysql {
		$this->sql('limit', 'limit ' . $data);
		return $this;
	}
	
	/**
	 * public Mysql function clear(void)
	 */
	public function clear(): Mysql {
		$this->sql_datas = array();
		return $this;
	}
	
	/**
	 * public array function select(void)
	 */
	public function select(): array {
		$sql_subgroup = ['select', $this->distinct, $this->field, 'from', $this->table, $this->join, $this->where, $this->group, $this->having, $this->order, $this->limit];
		$sql = implode(' ', array_filter($sql_subgroup, 'is_no_empty_str'));
		return $this->clear()->query($sql);
	}
	
	/**
	 * public integer function add(array $datas)
	 * @$datas = array(string $field => ?scalar $data,...)
	 */
	public function add(array $datas): int {
		$datas = $this->filter($datas);
		$keys_str = implode(',', array_keys($datas));
		$values_str = implode(',', array_values($datas));
		$sql_subgroup = ['insert into', $this->table . '(' . $keys_str . ')', 'values(' . $values_str . ')'];
		$sql = implode(' ', array_filter($sql_subgroup, 'is_no_empty_str'));
		return $this->clear()->cmd($sql);
	}
	
	/**
	 * public integer function modify(array $datas)
	 * @$datas = [string $field => ?scalar $data|array $data,...]
	 * @$data = [string $expr]
	 */
	public function modify(array $datas): int {
		$datas = $this->filter($datas);
		array_walk($datas, 'merge_key_to_data');
		$datas_str = implode(',', $datas);
		$sql_subgroup = ['update', $this->table, 'set', $datas_str, $this->where, $this->order, $this->limit];
		$sql = implode(' ', array_filter($sql_subgroup, 'is_no_empty_str'));
		return $this->clear()->cmd($sql);
	}
	
	/**
	 * public integer function delete(void)
	 */
	public function delete(): int {
		$sql_subgroup = array('delete from', $this->table, $this->where, $this->order, $this->limit);
		$sql = implode(' ', array_filter($sql_subgroup, 'is_no_empty_str'));
		return $this->clear()->cmd($sql);
	}
	
	/**
	 * public integer function cmd(string $sql)
	 */
	public function cmd(string $sql): int {
		$this->sql = $sql;
		$this->error = null;
		if($this->link()){
			$old_id = $this->database->lastInsertId();
			$this->ds = $this->database->prepare($this->sql);
			if($this->ds){
				if($this->ds->execute()){
					$id = $this->database_lastInsertId();
					$this->id = $old_id != $id ? $id : null;
					return $this->ds->rowCount();
				}else
					$this->error = implode(':', $this->ds->errorInfo());
			}else
				$this->error = implode(':', $this->database->errorInfo());
		}
		return -1;
	}
	
	/**
	 * public array function query(string $sql)
	 */
	public function query(string $sql): array {
		$this->sql = $sql;
		$this->error = null;
		if($this->link()){
			$this->ds = $this->database->prepare($this->sql);
			if($this->ds){
				if($this->ds->execute()) return $this->ds->fetchAll(PDO::FETCH_ASSOC);
				else $this->error = implode(':', $this->ds->errorInfo());
			}else
				$this->error = implode(':', $this->database->errorInfo());
		}
		return array();
	}
	
	/**
	 * public boolean function begin(void)
	 */
	public function begin(): bool {
		if(!$this->link()) return false;
		elseif($this->database->inTransaction()) return false;
		return $this->database->beginTransaction();
	}
	
	/**
	 * public boolean function end(void)
	 */
	public function end(): bool {
		if(!$this->link()) return false;
		elseif(!$this->database->inTransaction()) return false;
		return $this->database->commit();
	}
	
	/**
	 * public boolean function rollback(void)
	 */
	public function rollback(): bool {
		if(!$this->link()) return false;
		elseif(!$this->database->inTransaction()) return false;
		return $this->database->rollBack();
	}
	
	/**
	 * public ?string function get_last_error(void)
	 */
	public function get_last_error(): string {
		return $this->error;
	}
	
	/**
	 * public ?string function get_last_sql(void)
	 */
	public function get_last_sql(): string {
		return $this->sql;
	}
	
	/**
	 * public ?integer function get_last_id(void)
	 */
	public function get_last_id(): int {
		return $this->id;
	}
	
	/**
	 * protected boolean function link(void)
	 */
	protected function link(): bool {
		$params = $this->connector();
		if($params) list($dsn, $username, $password) = $params;
		else return false;
		try{
			$this->database = new PDO($dsn, $username, $password, $this->driver_options);
			return true;
		}catch(PDOException $err){
			$this->error = implode(':', $err->errorInfo());
			return false;
		}
	}
	
	/**
	 * protected array function connector(void)
	 */
	protected function connector(): array {
		if($this->connect_params){
			/*
			 * list('rich'=>$rich, 'host'=>$host, 'port'=>$port, 'dbname'=>$dbname, 'charset'=>$charset)=$this->conenct_params;
			 * list('username'=>$username, 'password'=>$password)=$this->connect_params;
			 */
			extract($this->connect_params);
			$dsn = implode(';', array($protocol . ':host=' . $host, 'port=' . $port, 'dbname=' . $dbname, 'charset=' . $charset));
			return array($dsn, $username, $password);
		}
		return array();
	}
	
	/**
	 * protected boolean function sql(string $cmd, string $data)
	 */
	protected function sql(string $cmd, string $data): bool {
		if(in_array($cmd, $this->sql_cmds, true)){
			$this->sql_datas[$cmd]=$data;
			return true;
		}
		return false;
	}
	
	/**
	 * protected array function filter(array $datas)
	 * @$datas = [string $field => ?scalar $data|array $data,...]
	 * @$data = [string $expression]
	 */
	protected function filter(array $datas): array {
		foreach($datas as $field => $data){
			if(is_string($field) && is_underline_named_regular($field)) $field = wrap_database_backquote($field);
			else continue;
			if(is_integer($data) or is_float($data)) $data = (string)$data;
			elseif(is_string($data)) $data = "'" . $data . "'";
			elseif(is_bool($data)) $data = $data ? '1' : '0';
			elseif(is_null($data)) $data = 'null';
			elseif(is_array($data) && isset($data[0]) && is_string($data[0])) $data = $data[0];
			else continue;
			$ends[$field] = $data;
		}
		return $ends ?? array();
	}
	
	/**
	 * protected void function close(void)
	 */
	protected function close(): void {
		$this->free();
		$this->database = null;
	}
	
	/**
	 * protected void function free(void)
	 */
	protected function free(): void {
		$this->ds = null;
	}
	//
}