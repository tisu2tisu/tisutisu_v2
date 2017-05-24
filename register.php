<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title> Register </title>
		<link href="main.css?version=1" type="text/css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Amaranth" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Gentium+Basic" rel="stylesheet">
		<script src="https://use.fontawesome.com/7fc6d91130.js"></script>
	</head>
<?php
	// Lampirkan db dan user
	include_once "myconfig.php";
	
	// buat object user
	
	
	// jika sudah login
	if($user->isLoggedIn())
	{
		header("location: index.php"); // Redirect ke halaman utama
	}
	
	// jika ada data kirim 
	if(isset($_POST['kirim']))
	{
		$nama = $_POST['nama'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$status = $_POST['status'];
		$login = $_POST['login'];
		
		
		// registrasi user baru
		if($user->register($nama, $password, $email, $status, $login))
		// THIS IS SHIT I AM FUCKING TALKING ABOUT YOU GUYS if($user->buat($nama, $password, $email, ))
		{
			// jika berhasil set variabel succes ke true
			$success = true;
		}
		else 
		{
			// jika gagal ambil pesan error
			$error = $user->getLastError();
		}
	}
	
?>
	
	<body>
		<div class="header">
			<ul>
				<li><a href="index.php">HOME </a></li>
				<li><a href="animation.php">ANIMATION </a></li>
				<li><a href="programming.php">PROGRAMMING </a></li>
				<li><a href="aboutus.php">ABOUT US </a></li>
			</ul>
		</div>
		
	<div class="page-login-container">
	&nbsp;
	<div class="page-login">
		<h1><center> REGISTER FORM </center></h1>
	<div class="page-login-form">
			<form class="register-form" method="post">
			<table>
			<?php
			 if(isset($error)): ?>
			 <div class="error">
				<?php echo $error ?>
			 </div>
			<?php
			endif;
			?>
			<?php
			if(isset($success)):
			?>
			<div class="success">
				Berhasil Mendaftar. Silahkan <a href="login.php">Masuk</a>
			</div>
			<?php
			endif;
			?>
			<tr>
				<td>
					Nama &nbsp; &nbsp; &nbsp;&nbsp;:&nbsp;&nbsp; <input type="text" name="nama" required />
				</td>
			</tr>
			
			<tr>
				<td>
					Password :&nbsp;&nbsp; <input type="password" name="password" required />
				</td>
			</tr>
		
			<tr>
				<td>
					E-mail &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; <input type="email" name="email" required />
				</td>
			</tr>
			
			<tr>
				<td>
					<input type="hidden" name="status" value="1" />
				</td>
			</tr>
			
			<tr>
				<td>
					<input type="hidden" name="login" value="<?php echo date("Y-m-d H:i:s"); ?>" />
				</td>
			</tr>
			
			<tr>
				<td>
					<button type="submit" name="kirim">Create</button>
				</td>
			</tr>
			
			<tr>
				<td> Already registered? <a href="login.php"> Sign In</a> </td>
			</tr>
			</table>
			</form>
		</div>
		</div>
		
		<div class="footer-regis">
		<a href="your link here"> <i class="fa fa-facebook fa-2x"></i></a>
		CaturSurya
		&nbsp;&nbsp;&nbsp;
		<a href="your link here"> <i class="fa fa-envelope fa-2x"></i></a>
		catursura@gmail.com
		&nbsp;&nbsp;&nbsp;
		<a href="your link here"> <i class="fa fa-mobile fa-3x"></i></a>
		+6287875629238

			<p>@CaturSura. All rights reserved | FLAT Design  | Demo Portofolio </p>
		</div>
	</div>
		
		
		
	</body>
	</html>






















