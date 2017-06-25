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
	protected $driverOptions = array(PDO::ATTR_CASE=>PDO::CASE_LOWER, PDO::ATTR_ERRMODE=>PDO::ERRMODE_SILENT, PDO::ATTR_ORACLE_NULLS=>PDO::NULL_NATURAL, PDO::ATTR_STRINGIFY_FETCHES=>false);
	protected $connect_params = array();
	
	/**
	 * void public function __construct(?array $connect_params = null)
	 */
	public function __construct(array $connect_params = null) {
		if(is_array($connect_params) && is_database_connect_params($connect_params)) $this->connect_params = $connect_params;
		else $this->connect_params = get_config('database_connect_params', array());
	}
	
	/**
	 * void public function __destruct(void)
	 */
	public function __destruct() {
		$this->close();
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
	public function error(): ?string {
		return $this->error;
	}
	
	/**
	 * ?string public function sql(void)
	 */
	public function sql(): string {
		return $this->sql;
	}
	
	/**
	 * ?integer public function id(void)
	 */
	public function id(): int {
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
			$this->database = new PDO($dsn, $username, $password, $this->driverOptions);
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
	 * void protected function close(void)
	 */
	protected function close(): void {
		$this->free();
	}
	
	/**
	 * void protected function free(void)
	 */
	protected function free(): void {
		$this->ds = null;
	}
	//
}