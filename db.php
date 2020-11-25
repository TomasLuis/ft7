<?php
	$DBC = "mysql:dbname=ft4;host=localhost";
	$DBUSER = "root";
	$DBPASS = "";

	try{
		$pdo = new PDO($DBC, $DBUSER, $DBPASS); 
	}catch(PDOExcetpion $e){
		echo "FALHOU: ".$e->getMessage();
	}
?>