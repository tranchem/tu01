<?php
	class Model extends PDO{
		public $_user;
		public $_pwd;
		public $_dbName;
		public $_db;
		public function __construct($usr,$pwd,$dbName){
			$this->_user = $usr;
			$this->_pwd = $pwd;
			$this->_dbName = $dbName;
			$dsn = "mysql:dbname=$dbName;host=localhost";
			try{
				$this->_db = new PDO($dsn,$usr,$pwd);
				$this->_db->query("SET NAMES 'utf8'");
			}catch(PDOException $e){
				echo "Not connected to database <br>".$e->getMessage();
			}
			
		}
		public function execSQL($sql){
			$sth=$this->_db->prepare($sql);
			$sth->execute();
			return $sth;
		}
		public function fetchAll($sql){
			$dbh = $this->execSQL($sql);
			return $dbh->fetchAll(PDO::FETCH_ASSOC);

		}
		public function fetchOne($sql){
			$dbh = $this->execSQL($sql);
			return $dbh->fetch(PDO::FETCH_ASSOC);
		}
		
	}
?>