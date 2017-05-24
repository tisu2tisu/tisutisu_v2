<?php
	include_once "myconfig.php";
?>	
<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<title> Tisu Tisu v2 | <?php echo $user->convert_to_string($_GET['edit']); ?> </title>
		<link href="main.css?version=6" type="text/css" rel="stylesheet">
		<script src="https://use.fontawesome.com/7fc6d91130.js"></script>
		<script type="text/javascript" src="asset/js/jquery.min.js"></script>
		<script type="text/javascript" src="asset/plugin/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="asset/plugin/tinymce/init-tinymce.js"></script>
	</head>
	
	<body>
		<div class="header">
			<ul>
				<li><a href="index.php">HOME </a></li>
				<li><a href="animation.php">ANIMATION </a></li>
				<li><a href="programming.php">PROGRAMMING </a></li>
				<li><a href="aboutus.php">ABOUT US </a></li>
			</ul>
		</div>
		
		<div class="content-background">
		<br />
		<div class="content-container">
		<div class="content-cms">
			<?php
			$user->show_edit_post($_GET['edit']);
			?>
					
		</div>
		</div>
		<div class="footer">
		<a href="your link here"> <i class="fa fa-facebook fa-2x"></i></a>
		CaturSurya
		<a href="your link here"> <i class="fa fa-envelope fa-2x"></i></a>
		catursura@gmail.com
		<a href="your link here"> <i class="fa fa-mobile fa-3x"></i></a>
		+6287875629238

			<p>@CaturSura. All rights reserved | FLAT Design  | Demo Portofolio </p>
		</div>
		</div>
		
		</body>
	</html>