<?php

require_once ("modules/autoloader.php"); 

$validate=new validate_base();

try {

	session_start();

	$users=new users_user();

	if($_POST && $_POST['cmd']=='login') {

		$fields=array(
			'Login'=>trim($_POST['login']),
			'Password'=>trim($_POST['password']),
		);
		//validate all fields are filled in
		$validate->validate_fields($fields);
		
		$users->load(array(
				'login'=>trim($_POST['login']),
				'password'=>trim(strtolower($_POST['password'])),
			)
		);

		$login_user = current($users->get());

		if(empty($login_user)) {
			throw new Exception("Invalid Login or Password.");
		}

		$_SESSION['id_user']=$login_user['id_user'];
		
		//if they are coming from another page take them back to that page
		if(array_key_exists('callback', $_REQUEST)) {
			header("Location: {$_REQUEST[callback]}");
		} else {
			header( 'Location: account/myaccount.php' );
		}


	}

} catch(Exception $e) {
	$message = "<div class='error'>".$e->getMessage() . "</div>";
}

?>


<!DOCTYPE HTML>
<html>
<head>
	<title>Free Fundaaz Website template | Home :: w3layouts</title>
	<meta name="keywords" content="Fundaaz Iphone web template, Andriod web template, Smartphone web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php include ("templates/header_links.php"); ?>
</head>
<body>

	<?php include ("templates/header.php"); ?>

	<div class="msg"><?php echo $message; ?></div>

	<div class="login-form">
		<div class="login">
			
			<div id="lpmod">
				<span id="si3">
					<h3 id="lpHeader">Sign In</h3>
				</span>
			</div>
			
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="hidden" name="cmd" id="cmd" value="login" />
				
				<div class="even">
					<div class="words">Login: </div>
					<div class="fields"><input type="text" id="login" name="login" value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Password: </div>
					<div class="fields"><input type="password" id="password" name="password" value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
				
				<div class="login_button">
					<input id="submitID" type="submit" alt="Sign In" title="Sign In" tabindex="3" value="Sign In" onclick="return validateSubmit()";>
				</div>
				
				<div class="clear"></div>
				
				<a id="getSn" tabindex="6" target="_top" href="register.php">Register</a>
				
			</form>
			
		</div>
	</div>



	<?php include ("templates/footer.php"); ?>

</body>

</html>