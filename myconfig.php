<?php
	// konfigurasi koneksi PDO
	try
	{
		$con = new PDO('mysql:host=localhost;dbname=tisutisu','root','0210sura',array(PDO::ATTR_PERSISTENT => true));
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
	
	include_once 'func.php';
	$user = new user($con);
?>