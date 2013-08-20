<?php
session_start();
require_once ("../modules/autoloader.php"); 

loged_in();


?>

<!DOCTYPE HTML>
<html>
<head>
	<title>Free Fundaaz Website template | Home :: w3layouts</title>
	<meta name="keywords" content="Fundaaz Iphone web template, Andriod web template, Smartphone web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php include ("../templates/header_links.php"); ?>
</head>
<body>

	<?php include ("../templates/header.php"); ?>

	<div class="msg"><?php echo $message; ?></div>

	<div class="button-location">
		<a href=# class="black button" id="personal_btn">My Personal Info</a>
		<a href=# class="black button" id="display_btn">My Display Info</a>
		<a href="images.php" class="black button" id="display_btn">Upload Images</a>
	</div>

	
	<?php include ("../templates/footer.php"); ?>

</body>

</html>