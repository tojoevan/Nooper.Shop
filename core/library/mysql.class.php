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
	 * public void function __construct(?array $connect_params = null)
	 */
	public function __construct(array $connect_params = null) {
		
		if(is_string($this->memory) && is_underline_named_regular($this->memory)) $this->sql('table', $this->memory);
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
	public function __get(string $cmd): ?string {
		return $this->sql_datas[$cmd] ?? null;
	}
	
	/**
	 * public boolean function sql(string $cmd, string $data)
	 */
	public function sql(string $cmd, string $data): bool {
		if(!in_array($cmd, $this->sql_cmds, true)) return false;
		$this->sql_datas[$cmd] = $data;
		return true;
	}
	
	/**
	 * public integer function sqls(array $datas)
	 * @$datas=array(string $cmd => string $data);
	 */
	public function sqls(array $datas): int {
		$counter = 0;
		foreach($datas as $cmd => $data){
			if(is_string($cmd) && is_string($data)){
				$end = $this->sql($cmd, $data);
				if($end) $counter++;
			}
		}
		return $counter;
	}
	
	/**
	 * boolean public function clear(?string $cmd = null)
	 */
	public function clear(string $cmd = null): bool {
		if(is_null($cmd)) $this->sql_datas = array();
		elseif(!in_array($cmd, $this->sql_cmds, true)) return false;
		else unset($this->sql_datas[$cmd]);
		return true;
	}
	
	/**
	 * array public function select(void)
	 */
	public function select(): array {
		$sql_subgroup = array('select', $this->distinct, $this->field, 'from', $this->table, $this->join, $this->where, $this->group, $this->having, $this->order, $this->limit);
		$sql = implode(' ', array_filter($sql_subgroup, is_no_empty_str));
		return $this->query($sql);
	}
	
	/**
	 * integer public function insert(array $datas)
	 * @$datas = array(string $field => ?scalar $data,...)
	 */
	public function insert(array $datas): int {
		$datas = $this->filter($datas);
		$keys_str = implode(',', array_keys($datas));
		$values_str = implode(',', array_values($datas));
		$sql_subgroup = array('insert into', $this->table . '(' . $keys_str . ')', 'values(' . $values_str . ')');
		$sql = implode(' ', array_filter($sql_subgroup, is_no_empty_str()));
		return $this->cmd($sql);
	}
	
	/**
	 * integer public function update(array $datas)
	 * @$datas = array(string $field => ?scalar $data,...)
	 */
	public function update(array $datas): int {
		$datas = $this->filter($datas);
		array_walk($datas, __NAMESPACE__ . '\\merge_key_to_data');
		$datas_str = implode(',', $datas);
		$sql_subgroup = array('update', $this->table, 'set', $datas_str, $this->where, $this->order, $this->limit);
		$sql = implode(' ', array_filter($sql_subgroup, __NAMESPACE__ . '\\is_no_empty_str'));
		return $this->cmd($sql);
	}
	
	/**
	 * integer public function delete(void)
	 */
	public function delete(): int {
		$sql_subgroup = array('delete from', $this->table, $this->where, $this->order, $this->limit);
		$sql = implode(' ', array_filter($sql_subgroup, __NAMESPACE__ . '\\is_no_empty_str'));
		return $this->cmd($sql);
	}
	
	/**
	 * integer public function cmd(string $sql)
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
	 * array public function query(string $sql)
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
	 * ?string public function error(void)
	 */
	public function error(): string {
		return $this->error;
	}
	
	/**
	 * ?string public function get_last_sql(void)
	 */
	public function get_last_sql(): string {
		return $this->sql;
	}
	
	/**
	 * ?integer public function get_last_id(void)
	 */
	public function get_last_id(): int {
		return $this->id;
	}
	
	/**
	 * boolean protected function link(void)
	 */
	protected function link(): bool {
		$params = $this->connector();
		if($params) list($dsn, $username, $password) = $params;
		else return false;
		try{
			$this->database = new PDO($dsn, $username, $password, $this->driver_options);
			return true;
		}catch(PDOException $err){
			return false;
		}
	}
	
	/**
	 * array protected function connector(void)
	 */
	protected function connector(): array {
		if($this->connect_params){
			// list('type'=>$type, 'host'=>$host, 'port'=>$port, 'dbname'=>$dbname, 'charset'=>$charset)=$this->conenct_params;
			// list('username'=>$username, 'pwssword'=>$password)=$this->connect_params;
			extract($this->connect_params);
			$dsn = implode(';', array($type . ':host=' . $host, 'port=' . $port, 'dbname=' . $dbname, 'charset=' . $charset));
			return array($dsn, $username, $password);
		}
		return array();
	}
	
	/**
	 * array protected function filter(array $datas)
	 * @$datas = array(string $field => ?scalar $data,...)
	 */
	protected function filter(array $datas): array {
		foreach($datas as $field => $data){
			if(is_underline_named_regular($field)) $field = wrap_database_backquote($field);
			else continue;
			if(is_integer($data) or is_float($data)) $data = (string)$data;
			elseif(is_string($data)) $data = "'" . $data . "'";
			elseif(is_bool($data)) $data = $data ? '1' : '0';
			elseif(is_null($data)) $data = 'null';
			else continue;
			$ends[$field] = $data;
		}
		return $ends ?? array();
	}
	
	/**
	 * void protected function close(void)
	 */
	protected function close(): void {
		$this->free();
		$this->database = null;
	}
	
	/**
	 * void protected function free(void)
	 */
	protected function free(): void {
		$this->ds = null;
	}
	//
}