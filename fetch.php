<?php
	include "func.php";
	include "myconfig.php";
	if(!empty($_GET['q']))
	{
		$q = $_GET['q'];
		$result = $con->prepare("SELECT * FROM content WHERE judul LIKE '%$q%'");
		$result->execute();
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
		?>
			<a href="index.php?article=<?php echo $row['url']; ?> "> <?php echo $row['judul']; ?> </a><br/>
		<?php
			}
	}
?>
			