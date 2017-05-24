<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<title> Login </title>
		<link href="main.css?version=4" type="text/css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Amaranth" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Gentium+Basic" rel="stylesheet">
	</head>
<?php
	session_start();
	include_once "myconfig.php";
	
	if ($user->isLoggedIn())
	{
		header("index.php");
	}
	
	if(isset($_POST['kirim']))
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		if($user->login($username, $password))
		{
			header("location:index.php");
		}
		else 
		{
			$error = $user->getLastError();
		}
	}
	
?>
	
	<body>
		<div class="login-container-background">
			<div class="login-form">
			<h1> Login Form </h1>
			<form method="post" >
			<?php 
				if(isset($error)): ?>
				<div class="error">
					<?php echo $error; ?>
				</div>
				<?php endif; ?>
				
				<table>
					<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp; Username : <input type="text" name="username"> </td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp; Password : <input type="password" name="password"> </td>
					</tr>
					
					<tr>
						<td> &nbsp;</td>
					</tr>
					<tr>
						<td><button type="submit" name="kirim">Kirim</button></td>
					</tr>	
				</table>
				<p> Not registered? <a href="register.php">Create an Account </a></p>
			</form>
			</div>
		</div>
	
	
	</body>
	</html>
			