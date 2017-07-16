<?php
// declare(strict_types = 1);
namespace NooperShop;

use PDO;
use PDOException;

class Mysql {
	
	/**
	 * Properties
	 */
	protected $id;
	protected $sql;
	protected $error;
	protected $database;
	protected $ds;
	protected $driver_options = array(PDO::ATTR_CASE=>PDO::CASE_LOWER, PDO::ATTR_ERRMODE=>PDO::ERRMODE_SILENT, PDO::ATTR_ORACLE_NULLS=>PDO::NULL_NATURAL, PDO::ATTR_STRINGIFY_FETCHES=>false);
	protected $connect_params = array();
	protected $sql_cmds = array('distinct', 'field', 'table', 'join', 'where', 'group', 'having', 'order', 'limit');
	protected $sql_datas = array();
	protected $memory;
	
	/**
	 * public void function __construct(?string $memory = null, ?array $connect_params = null)
	 */
	public function __construct(string $memory = null, array $connect_params = null) {
		if(is_underline_named_regular($memory)) $this->table($this->memory = $memory);
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
	public function __get(string $cmd): string {
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
	 * public Mysql function field(string $data)
	 */
	public function field(string $data): Mysql {
		$this->sql('field', $data);
		return $this;
	}
	
	/**
	 * public Mysql function table(string $data)
	 */
	public function table(string $data): Mysql {
		$this->sql('table', $data);
		return $this;
	}
	
	/**
	 * public Mysql function join(string $data)
	 */
	public function join(string $data): Mysql {
		$this->sql('join', $data);
		return $this;
	}
	
	/**
	 * public Mysql function where(string $data)
	 */
	public function where(string $data = null): Mysql {
		$this->sql('where', $data);
		return $this;
	}
	
	/**
	 * public Mysql function group(string $data)
	 */
	public function group(string $data): Mysql {
		$this->sql('group', $data);
		return $this;
	}
	
	/**
	 * public Mysql function having(string $data)
	 */
	public function having(string $data): Mysql {
		$this->sql('having', $data);
		return $this;
	}
	
	/**
	 * public Mysql function order(string $data)
	 */
	public function order(string $data): Mysql {
		$this->sql('order', $data);
		return $this;
	}
	
	/**
	 * public Mysql function limit(integer $num, integer $offset =0)
	 */
	public function limit(int $num, int $offset = 0): Mysql {
		$this->sql('limit', 'limit ' . ($offset != 0 ? $offset . ',' . $num : $num));
		return $this;
	}
	
	/**
	 * public Mysql function clear(void)
	 */
	public function clear(): Mysql {
		$this->sql_datas = array();
		if(!is_null($this->memory)) $this->table($this->memory);
		return $this;
	}
	
	/**
	 * public array function select(void)
	 */
	public function select(): array {
		$sql_subgroup = ['select', $this->distinct, $this->field, 'from', $this->table, $this->join, $this->where, $this->group, $this->having, $this->order, $this->limit];
		$sql = implode(' ', array_filter($sql_subgroup, 'is_no_empty_str'));
		return $this->query($sql);
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
		return $this->cmd($sql);
	}
	
	/**
	 * public integer function modify(array $datas)
	 * @$datas = [string $field => ?scalar $data|array $data,...]
	 * @$data = [string $expression]
	 */
	public function modify(array $datas): int {
		$datas_str = implode(',', $datas);
		$sql_subgroup = ['update', $this->table, 'set', $datas_str, $this->where, $this->order, $this->limit];
		$sql = implode(' ', array_filter($sql_subgroup, 'is_no_empty_str'));
		return $this->cmd($sql);
	}
	
	/**
	 * public integer function delete(void)
	 */
	public function delete(): int {
		$sql_subgroup = array('delete from', $this->table, $this->where, $this->order, $this->limit);
		$sql = implode(' ', array_filter($sql_subgroup, 'is_no_empty_str'));
		return $this->cmd($sql);
	}
	
	/**
	 * public integer function cmd(string $sql)
	 */
	public function cmd(string $sql): int {
		$this->sql = $sql;
		$this->error = null;
		if($this->link()){
			$this->ds = $this->database->prepare($this->sql);
			if($this->ds){
				if($this->ds->execute()) return $this->ds->rowCount();
				else $this->error = implode(':', $this->ds->errorInfo());
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
			 * list('type'=>$type, 'host'=>$host, 'port'=>$port, 'dbname'=>$dbname, 'charset'=>$charset)=$this->conenct_params;
			 * list('username'=>$username, 'password'=>$password)=$this->connect_params;
			 */
			extract($this->connect_params);
			$dsn = implode(';', array($type . ':host=' . $host, 'port=' . $port, 'dbname=' . $dbname, 'charset=' . $charset));
			return array($dsn, $username, $password);
		}
		return array();
	}
	
	/**
	 * protected boolean function sql(string $cmd, string $data)
	 */
	protected function sql(string $cmd, string $data): bool {
		if(!in_array($cmd, $this->sql_cmds, true)) return false;
		$this->sql_datas[$cmd] = $data;
		return true;
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
			elseif(!$only_allow_num && is_string($data)) $data = "'" . $data . "'";
			elseif(!$only_allow_num && is_bool($data)) $data = $data ? '1' : '0';
			elseif(!$only_allow_num && is_null($data)) $data = 'null';
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