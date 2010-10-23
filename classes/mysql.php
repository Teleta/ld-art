<?php
class mysql
{
	var $host;
	var $login;
	var $password;
	var $db;

	var $conn_id;
	var $resource;

	function mysql($host, $login='', $password='', $db=''){
		if(is_array($host)){
			$this->host = $host['host'];
			$this->login = $host['login'];
			$this->password = $host['password'];
			$this->db = $host['db'];
		}else{
			$this->host = $host;
			$this->login = $login;
			$this->password = $password;
			$this->db = $db;
		}
		$this->conn_id = mysql_connect($this->host, $this->login, $this->password);
		@mysql_query("SET NAMES cp1251", $this->conn_id);
		@mysql_query("SET CHARACTER_SET cp1251", $this->conn_id);
		mysql_select_db($this->db, $this->conn_id);
	}

	function query($q){
		$this->resource = mysql_query($q, $this->conn_id);
		return $this->resource;
	}

	function fetch($res = null){
		if(!$res){
			$res = $this->resource;
		}
		return mysql_fetch_array($res, MYSQL_ASSOC);
	}
}
?>
