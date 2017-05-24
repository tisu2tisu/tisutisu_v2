<!DOCTYPE html>
	<html>
	<head>
		<title> Logout</title>
	</head>
	<body>
<?php
	// Lampirkan db dan user
	require_once "myconfig.php";
	require_once "func.php";
	
	
	
	if($user->logout())
	{
?>
	<div class="logout">
		<a href="index.php">Berhasil Logout! Silahkan Klik disini untuk kembali ke halaman depan :)</a>
	</div>
	<?php
	}
	?>
	
	</body>
	</html>
	