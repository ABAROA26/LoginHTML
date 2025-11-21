<?php 

class ConnectionController{

	private $HOST = "localhost";
	private $USER = "root";
	private $PASS = "";
	private $DBNM = "mi_proyecto";

	private $PORT = 3308;

	function connect()
	{
		$conn = new mysqli($this->HOST,$this->USER,$this->PASS,$this->DBNM, $this->PORT);
		if ($conn) {
			return $conn;
		}
		return null;
	}

}

?>