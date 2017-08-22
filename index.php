<!DOCTYPE html>
	<html lang="en">
<!-- kamis kerumah ciming malem belajar bersama -->
	<head>
		<meta charset="utf-8">
		<title> Tisu Tisu v2 </title>
		<link href="main.css?version=17" type="text/css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Amaranth" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Gentium+Basic" rel="stylesheet">
		<script src="https://use.fontawesome.com/7fc6d91130.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script>
			$(document).ready(function (e) {
				$("#search").keyup(function (){
					$("#search-result").show();
					var x = $(this).val();
					$.ajax (
						{
						type: 'GET',
						url: 'fetch.php',
						data: 'q='+x,
						success:function(data)
						{
							$("#search-result").html(data);
						},
					});
				});
			});
		</script>
	</head>
	
	<body>
		<div class="header">
			<ul>
				<li><a href="index.php">HOME </a></li>
				<li><a href="animation.php">ANIMATION </a></li>
				<li><a href="programming.php">PROGRAMMING </a></li>
				<li><a href="aboutus.php">ABOUT US </a></li>
			</ul>
			<div class="header-inside">
			<form>
				<input type="text" id="search" placeholder="Cari Artikel disini..." />
				<button type="submit" id="search-submit">Search!</button>
				<br>
				<div id="search-result"></div>

			</form>
			</div>
		</div>
		
		<div class="content-background">
		<br />
		<div class="content-container">
		<div class="aside-container">
			<div class="aside">
			<?php
			include_once "myconfig.php";
				if (isset($_SESSION['user_session']))
				{
					$session = $_SESSION['user_session'];
			?>
				<h2> Selamat datang <?php echo $session; ?>  </h2>
				<p> This is information for your account : 
				<br />
				<br />
					Email : <?php  echo $user->email($session); ?> <br />
					Status : <?php echo $user->status($session); ?> <br />
					Terakhir Login : <?php echo $user->lastlogin($session); ?> <br />
					<br />
					
				<div class="logout-img"><a href="logout.php" ><img src="img/logout.png"></a></div>&nbsp;&nbsp; <div class="config-img"><a href="cms.php"><img src="img/config.png"></a></div>
				
				
				</p>
					
			<?php 
				}
				else
				{
			?>
			<h2><a href="login.php"> Login for more feature </a> </h2>
			
			<?php
				}
			?>
			</div>
		</div>
		
		<!-- old code for see design
		<a href="index.php?" class="judul"><div class="content">
		<p>
		<img src="img/meepo.png" alt="meepo" /><h1>Lorem ipsum dolor sit amet</h1><div class="isi-content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum laoreet urna a ante tincidunt aliquet. 
		Praesent vitae congue mi. Sed a libero ac metus posuere placerat. 
		Vivamus rhoncus in justo ut fermentum. Ut posuere odio nec mauris vulputate, 
		nec volutpat dolor ultricies. Sed tincidunt maximus elementum. Phasellus feugiat semper libero eu rhoncus. 
		Maecenas at erat vitae felis auctor tincidunt. Maecenas ut lacus ornare neque gravida lacinia ac ut augue. 
		Suspendisse metus diam, sollicitudin at vulputate id, finibus id arcu.
		</div>
		</p>
		</div>
		</a>
		
		<a href="index.php?" class="judul">
		<div class="content">
		<p><img src="img/invoker.jpg" alt="invoker" /><h1> Lorem ipsum dolor sit amet </h1><div class="isi-content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum laoreet urna a ante tincidunt aliquet. 
		Praesent vitae congue mi. Sed a libero ac metus posuere placerat. 
		Vivamus rhoncus in justo ut fermentum. Ut posuere odio nec mauris vulputate, 
		nec volutpat dolor ultricies. Sed tincidunt maximus elementum. Phasellus feugiat semper libero eu rhoncus. 
		Maecenas at erat vitae felis auctor tincidunt. Maecenas ut lacus ornare neque gravida lacinia ac ut augue. 
		Suspendisse metus diam, sollicitudin at vulputate id, finibus id arcu.
		</div>
		</p>
		</div>
		</a>
		
		<a href="index.php?" class="judul">
		<div class="content">
		<p><img src="img/crystal-maiden.png" alt="crystal maiden" /><h1> Lorem ipsum dolor sit amet </h1><div class="isi-content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum laoreet urna a ante tincidunt aliquet. 
		Praesent vitae congue mi. Sed a libero ac metus posuere placerat. 
		Vivamus rhoncus in justo ut fermentum. Ut posuere odio nec mauris vulputate, 
		nec volutpat dolor ultricies. Sed tincidunt maximus elementum. Phasellus feugiat semper libero eu rhoncus. 
		Maecenas at erat vitae felis auctor tincidunt. Maecenas ut lacus ornare neque gravida lacinia ac ut augue. 
		Suspendisse metus diam, sollicitudin at vulputate id, finibus id arcu.
		</div>
		</p>
		</div>
		</a>
		
		<a href="index.php?" class="judul">
		<div class="content">
		<p><img src="img/lina.jpg" alt="lina" /><h1> Lorem ipsum dolor sit amet </h1><div class="isi-content">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum laoreet urna a ante tincidunt aliquet. 
		Praesent vitae congue mi. Sed a libero ac metus posuere placerat. 
		Vivamus rhoncus in justo ut fermentum. Ut posuere odio nec mauris vulputate, 
		nec volutpat dolor ultricies. Sed tincidunt maximus elementum. Phasellus feugiat semper libero eu rhoncus. 
		Maecenas at erat vitae felis auctor tincidunt. Maecenas ut lacus ornare neque gravida lacinia ac ut augue. 
		Suspendisse metus diam, sollicitudin at vulputate id, finibus id arcu.
		</div>
		</p>
		</div>
		</div>
		</a>
		-->
		<?php
			$query = "SELECT * FROM content";
			$records_per_page = 4;
			$newquery = $user->paging($query, $records_per_page);
			if(isset($_GET['article']))
			{
				$title = $_GET['article'];
				$user->show_post($title);
			}
			else
			{
				$user->lihatdata($newquery);
		?>
				<div class="paging">
				<br />
				<table>
				<tr>
					<td colspan="7" align="center"> <?php $user->paginglink($query, $records_per_page); ?></td>
				</tr>
				<table>
				</div>
		<?php
			}
		?>
		</div>
		<div class="clear-float"></div>
		<div class="footer">
		<a href="https://www.facebook.com/suracatur" target="_blank"> <i class="fa fa-facebook fa-2x"></i></a>
		CaturSurya
		<a><i class="fa fa-envelope fa-2x"></i></a>
		catursura@gmail.com
		<a><i class="fa fa-mobile fa-3x"></i></a>
		+6287875629238

			<p>@CaturSura. All rights reserved | FLAT Design  | Demo Portofolio</p>
		</div>
		</div>
		
	</body>
	</html>