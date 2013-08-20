<?php

require_once ("modules/autoloader.php"); 

$validate=new validate_base();

if($_POST) {

	try {
		
		$users=new users_user();
		$mail = new mail_base();

		$fields=array(
			'Login'=>trim($_POST['login']),
			'Password'=>trim($_POST['password']),
			'First Name'=>trim($_POST['first_name']),
			'Last Name'=>trim($_POST['last_name']),
			'Address'=>trim($_POST['address']),
			'City'=>trim($_POST['city']),
			'State'=>trim($_POST['state']),
			'Zipcode'=>trim($_POST['zip']),
			'Phone'=>trim($_POST['phone']),
			'Country'=>$_POST['country'],
		);
		//validate all fields are filled in
		$validate->validate_fields($fields);

		$phone = $validate->validate_telephone_number(trim($_POST['phone']));

		$users->setLogin(trim($_POST['login']));
		$check = $users->get();

		if(!(empty($check))) {
			throw new Exception('A user with your chosen login already exists');
		}

		$users->load(array(
				'login'=>trim($_POST['login']),
				'password'=>trim(strtolower($_POST['password'])),
			)
		);

		$id_user = $users->addUser();

		if(empty($id_user)) {
			throw new Exception('An error has occured. Please try again');
		}

		$users->free();

		$users->load(array(
				'id_user'=>$id_user,
				'first_name'=>trim(ucfirst($_POST['first_name'])),
				'last_name'=>trim(ucfirst($_POST['last_name'])),
				'email'=>trim(strtolower($_POST['email'])),
				'address'=>trim($_POST['address']),
				'address2'=>trim($_POST['address2']),
				'city'=>trim(ucfirst($_POST['city'])),
				'state'=>$_POST['state'],
				'zipcode'=>trim($_POST['zip']),
				'phone'=>$phone,
				'country'=>$_POST['country'],
			)
		);

		$users->addUserInfo();

		$mail_info=array(
			'template'=>'modules/email_templates/registration.html',
			'mail_to'=>trim($_POST['email']),
			"subject"=>"Listpalace.com registration confirmation",
		);
	 	
	 	$mail->sendMail($mail_info);


		$message="<div class='success'>Thank you for registering. Please check your email to validate your registration.</div>";
	} catch(Exception $e) {
		$message = "<div class='error'>".$e->getMessage() . "</div>";
	}
}

try {
	$location = new location_base();

	//for now we are only checking in the United States
	$country=236;

	$location->setId_country($country);
	$states_array=$location->getState();

	$state="";
	foreach ($states_array as $states) {
		$state .= "<option value=\"{$states['id_state']}\">{$states['state_name']}</option>\n";
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

	<div class="register_form">

		<div class="msg"><?php echo $message; ?></div>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

			<div class="even">
				<div class="words">Login: </div>
				<div class="fields">
					<input type="text" name="login" id="login" value="" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>
			

			<div class="even">
				<div class="words">Password: </div>
				<div class="fields"><input type="password" id="password" name="password"  value="" />
					<span class="redstar">*</span>
					<span class="fields_msg" name="message"></span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">First Name: </div>
				<div class="fields"><input type="text" id="first_name" name="first_name"  value="" />
					<span class="redstar">*</span>
					<span class="fields_msg" name="message"></span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Last Name: </div>
				<div class="fields"><input type="text" id="last_name" name="last_name"  value="" />
					<span class="redstar">*</span>
					<span class="fields_msg" name="message"></span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Email: </div>
				<div class="fields"><input type="email" id="email" name="email"  value="" />
					<span class="redstar">*</span>
					<span class="fields_msg" name="message"></span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Address: </div>
				<div class="fields"><input type="text" id="address" name="address"  value="" />
					<span class="redstar">*</span>
					<span class="fields_msg" name="message"></span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Address 2: </div>
				<div class="fields"><input type="text" id="address2" name="address2"  value="" />
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">City: </div>
				<div class="fields"><input type="text" id="city" name="city"  value="" />
					<span class="redstar">*</span>
					<span class="fields_msg" name="message"></span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">State: </div>
				<div class="fields">
					<select id="state" name="state">
						<?php echo $state; ?>
					</select>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Zipcode: </div>
				<div class="fields"><input type="text" id="zip" name="zip"  value="" />
					<span class="redstar">*</span>
					<span class="fields_msg" name="message"></span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Phone: </div>
				<div class="fields"><input type="text" id="phone" name="phone"  value="" />
					<span class="redstar">*</span>
					<span class="fields_msg" name="message"></span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Country: </div>
				<div class="fields">
					<select name="country" id="country">
						<option value="236">United States:</option>
					</select>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words"></div>
				<div class="fields"><input type="submit" id="button_home" value="Register" onclick="return validateSubmitHome();" /></div>
				<div class="clear"></div>
			</div>

		</form>	

	</div>

	<div class="clear"></div>

	<?php include ("templates/footer.php"); ?>

</body>

</html>