<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<title> Tisu Tisu v2 </title>
		<link href="main.css?version=7" type="text/css" rel="stylesheet">
		<script src="https://use.fontawesome.com/7fc6d91130.js"></script>
		<script type="text/javascript" src="asset/js/jquery.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Amaranth" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Gentium+Basic" rel="stylesheet">
		<script type="text/javascript" src="asset/plugin/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="asset/plugin/tinymce/init-tinymce.js"></script>
	</head>
	
	<body>
<?php
	include_once "myconfig.php";

?>
		
		
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
		<h1> Member Config</h1>
		<?php
			
		if(isset($_POST['kirim']))
		{
			$username = $_SESSION['user_session'];
			$judul = $_POST['judul'];
			$isi = ($_POST['isi']);
			$created = $_POST['created'];
			$url = $_POST['judul'];
			if($user->createpost($username,$judul,$isi,$created, $url))
				{
					$success = true;
				}
				else 
				{
					$error = $user->getLastError();
				}
		}
	?>
		<form method="post">
		<h2><center><a href="cms.php" 
		<?php
		if(!isset($_GET['showpost']) && !isset($_GET['edit']) && !isset($_GET['hapus']) && !isset($_GET['option'])){
			echo "style='color:red;'";
		}
		?> 
		>Create New Post</a> | <a href="cms.php?showpost=true" 
		<?php
		if(isset($_GET['showpost']))
		{
			echo "style='color:red;'";
		} elseif(isset($_GET['edit']))
		{
			echo "style='color:red;'";
		}
		?> >Show All My Post</a> | <a href="cms.php?option=editprofile" 
			<?php
		if(isset($_GET['option']) && $_GET['option'] == "editprofile")
		{
			echo "style='color:red;'";
		}
		?>
		>Edit My Profile</a> </center></h2>
		<br />
		<?php
			if(isset($_GET['showpost']) == "true")
			{
				$user->show_all_post($_SESSION['user_session']);
			} elseif(isset($_GET['edit']))
			{
				if(isset($_POST['edit']))
				{
				$id = $_POST['id'];
				$username = $_POST['username'];
				$judul = $_POST['judul'];
				$isi = $_POST['isi'];
				$created = $_POST['created'];
				$modified = $_POST['modified'];
					$user->edit_post($id, $username, $judul, $isi, $created, $modified);
					$sukses = null;
				}
				$user->show_edit_post($_GET['edit']);
			} elseif(isset($_GET['hapus']))
			{
				if($user->delete_post($_GET['hapus']))
				{
					echo "<a href='cms.php?showpost=true'>Berhasil Menghapus data :) </a>";
				}
			} elseif(isset($_GET['option']) && $_GET['option'] == "editprofile")
			{
				if(isset($_POST['editprofile']))
				{
					if(!isset($_POST['status']))
					{
						$status = 1;
					} else {
						$status = $_POST['status'];
					}
					$id = $_POST['id'];
					$username = $_POST['username'];
					$password = $_POST['password'];
					$email = $_POST['email'];
					if($user->edit_profile($id,$username,$password,$email,$status))
					{
						echo "Berhasil mengedit user :)";
					}
				}			
				$user->show_edit_profile($_SESSION['user_session']);
			}
			else
			{
		?>
		
		Judul : <input type="text" name="judul" required /> <br /> &nbsp; <?php if(isset($success))
		{
			echo "Berhasil Menambah Posting Baru :)";
		}
		?><br />
				<input type="hidden" name="created" value="<?php echo date('Y-m-d'); ?>" required />
		<textarea name="isi" class="tinymce"></textarea><br />
		<button type="submit" name="kirim">Create New Post</button>
			<?php
			}
			?>
		</form>
		</div>
		</div>
		<div class="clear-float"></div>
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