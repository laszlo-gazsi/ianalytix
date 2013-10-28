<?php
	class DataBase {
		
	private $connection;
	private $host = 'localhost';
	private $user = 'root';
	private $password = 'mysqlpass';
	private $dbName = 'ianalytix';
	
	public function connect(){ //connecting to the MsSQL server
		$this->connection = mysql_connect( $this->host, $this->user, $this->password );
		mysql_select_db( $this->dbName, $this->connection );
	}
	
	public function query( $myQuery ){ //getting the result of a given query
		return mysql_query( $myQuery );
	}
	
	public function close(){ //closing the connection
		mysql_close( $this->connection );
	}
}
?>