<?php
session_start();
require_once ("../modules/autoloader.php"); 

loged_in();

$validate=new validate_base();
$users=new users_user();
$location = new location_base();
$cat = new categories_base();
if($_POST) {

	try{

		$id_user = $_SESSION['id_user'];
		$image_upload = new image_upload();
		$image_base = new image_image();
/**
 UPDATE PERSONAL DATA
 */
		if($_POST['cmd']=='update_personal') {

			$users->free();

			$fields=array(
				'Login'=>trim($_POST['login']),
				'First Name'=>trim($_POST['first_name']),
				'Last Name'=>trim($_POST['last_name']),
				'Address'=>trim($_POST['address']),
				'City'=>trim($_POST['city']),
				'State'=>trim($_POST['state']),
				'Zipcode'=>trim($_POST['zipcode']),
				'Phone'=>trim($_POST['phone']),
			);

			$validate->validate_fields($fields);

			$users->setId_user($id_user);
			$currentUser=current($users->get());

			$users->free();

			//check if login name is being changed and if so see if one already exists
			if($currentUser['login'] != trim($_POST['login'])) {
				$users->setLogin(trim($_POST['login']));
				$check = $users->get();

				if(!(empty($check))) {
					throw new Exception('A user with your chosen login already exists');
				}


			}


			//$users->setId_user($id_user);
			$users->setLogin(trim($_POST['login']));
			if(!empty($_POST['password'])) {
				$users->setPassword(trim($_POST['password']));
			}

			$users->updateUsersLogin($id_user);

			$users->free();

			$phone = $validate->validate_telephone_number(trim($_POST['phone']));

			$users->load(array(
				'first_name'=>trim(ucfirst($_POST['first_name'])),
				'last_name'=>trim(ucfirst($_POST['last_name'])),
				'email'=>trim(strtolower($_POST['email'])),
				'address'=>trim($_POST['address']),
				'address2'=>trim($_POST['address2']),
				'city'=>trim(ucfirst($_POST['city'])),
				'state'=>$_POST['state'],
				'zipcode'=>trim($_POST['zipcode']),
				'phone'=>$phone,
			));

			$users->updateUserInfo($id_user);
		}
/**
 UPDATE DISPLAY DATA
 */

		if($_POST['cmd']=='update_display') {
			$users->free();
			
			$fields=array(
				'Gender'=>$_POST['gender'],
				'Age'=>trim($_POST['age']),
				'Hair Color'=>trim($_POST['hair']),
				'Eyes Color'=>trim($_POST['eyes']),
				'height'=>trim($_POST['height']),
				'Bust Size'=>trim($_POST['bust']),
				'Weight'=>trim($_POST['weight']),
				'Waist Size'=>trim($_POST['waist']),
				'Ethnicity'=>trim($_POST['ethnicity']),
				"Availability"=>trim($_POST['availability']),
				"Sexual Preference"=>trim($_POST['sexual_preference']),
			);

			$validate->validate_fields($fields);

			$users->load(array(
				'id_user'=>$id_user,
				'gender'=>$_POST['gender'],
				'age'=>$_POST['age'],
				'hair'=>$_POST['hair'],
				'eyes'=>$_POST['eyes'],
				'height'=>$_POST['height'],
				'bust'=>$_POST['bust'],
				'weight'=>$_POST['weight'],
				'waist'=>$_POST['waist'],
				'affiliation'=>$_POST['affiliation'],
				'ethnicity'=>$_POST['ethnicity'],
				'availability'=>$_POST['availability'],
				'sexual_preference'=>$_POST['sexual_preference'],
				'about_me'=>$_POST['about_me'],
			));
					
			if($_POST['empty_ext'] == 'yes') {
				$users->setId_User($id_user);
				$users->addUserInfoExt();
			} else {
				$users->updateUserInfoExt($id_user);
			}
		}
/**
 UPLOAD IMAGES
 */		
		if($_POST['cmd']=='uploade_images') {

			foreach($_FILES['image']['name'] as $key=>$value) {
				if(empty($_FILES['image']['name'][$key])) {
					unset($_FILES['image']['name'][$key]);
					unset($_FILES['image']['type'][$key]);
					unset($_FILES['image']['tmp_name'][$key]);
					unset($_FILES['image']['error'][$key]);
					unset($_FILES['image']['size'][$key]);
				}
			}

			$image_path = $root . '/hot/img/uploaded_images/';
			$img_data = $image_upload->resize_image($_FILES, $image_path, 420,420);

			foreach($img_data as $key=>$value) {
				$image_base->free();
				$image_base->load(array(
					'id_user'=>$id_user,	
					'image_path'=>'/hot/img/uploaded_images/',
					'image_name'=>$value,
				));

				$image_base->addImage();
			}

			$message="<div class='success'>Images uploaded successfully.</div>";
		}

		if($_POST['cmd']=='delete_img') {
			foreach($_POST['delete_img'] as $key=>$value) {
				$image_base->free();
				$image_base->deleteImage($value);

			}
			$message="<div class='success'>Images deleted successfully.</div>";
		}

	} catch(Exception $e) {
		$message = "<div class='error'>".$e->getMessage() . "</div>";
	}
}

/**
 INITIAL DISPLAY DATA
 */
try {

	$id_user = $_SESSION['id_user'];

	$users->free();
	$users->setId_user($id_user);
	
	$image_base = new image_image();

	//get login data
	$loginData = current($users->get());

	//get user personal info
	$userData = current($users->getUserInfo());
	
	//get user display info
	$userInfo = current($users->getUserInfoExt());
	$categories = $cat->getCategories();
	
	$empty="";
	if(empty($userInfo)) {
		$empty = 'yes';
	}

	$location->setId_country($userData['country']);
	$states_array=$location->getState();

	$state="";
	foreach ($states_array as $states) {
		if($states['id_state'] == $userData['state']) {
			$state .= "<option value=\"{$states['id_state']}\" selected='selected'>{$states['state_name']}</option>\n";
		} else {
			$state .= "<option value=\"{$states['id_state']}\">{$states['state_name']}</option>\n";
		}
	}

	$gender_array=array('Male'=>'Male', 'Female'=>'Female');
	$gender="";
	foreach($gender_array as $key=>$value) {
		if($userInfo['gender']==$key) {
			$gender .="<option value=\"{key}\" selected='selected'>{$value}</option>";
		} else {
			$gender .="<option value=\"{$key}\">{$value}</option>";
		}

	}

	$image_base->setId_user($id_user);
	$img_data = $image_base->get();
	$img_num = current($image_base->getImageCount());
	
	$img_count = $img_num['count'];
		
	if(!empty($img_data)) {
		$img_path="";
		foreach($img_data as $data) {
			$img_path .="<div class='img_display'>".
						"<input type='checkbox' name='delete_img[]' value=\"{$data['id_image']}\" />".
						"<img src=\"{$data['image_path']}{$data['image_name']}\" width='200' height='173' />".
						"</div>";
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
	<?php include ("../templates/header_links.php"); ?>
</head>
<body>

	<?php include ("../templates/header.php"); ?>

	<div class="msg"><?php echo $message; ?></div>

	<div class="button-location">
		<a href=# class="black button" id="personal_btn">My Personal Info</a>
		<a href=# class="black button" id="display_btn">My Display Info</a>
		<a href=# class="black button" id="image_btn">Upload Images</a>
	</div>

	<div class="personal_info" id="pers_info_div"style="display:none;">

		<div class="title">Login Info</div>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type='hidden' name='cmd' value='update_personal' />

			<div class="even">
				<div class="words">Login: </div>
				<div class="fields">
					<input type="text" name="login" id="login" value="<?php echo $loginData['login'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Password: </div>
				<div class="fields">
					<input type="password" name="password" id="password" value="" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="clear"></div>

			<div class="title">Personal Info</div>

			<div class="even">
				<div class="words">First Name: </div>
				<div class="fields">
					<input type="text" name="first_name" id="first_name" value="<?php echo $userData['first_name'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Last Name: </div>
				<div class="fields">
					<input type="text" name="last_name" id="last_name" value="<?php echo $userData['last_name'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Email: </div>
				<div class="fields">
					<input type="email" name="email" id="email" value="<?php echo $userData['email'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Address: </div>
				<div class="fields">
					<input type="text" name="address" id="address" value="<?php echo $userData['address'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Address 2: </div>
				<div class="fields">
					<input type="text" name="address2" id="address2" value="<?php echo $userData['address2'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">City: </div>
				<div class="fields">
					<input type="text" name="city" id="city" value="<?php echo $userData['city'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">State: </div>
				<div class="fields">
					<select id="state" name="state">
						<?php echo $state; ?>
					</select>
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Zipcode: </div>
				<div class="fields">
					<input type="text" name="zipcode" id="zipcode" value="<?php echo $userData['zipcode'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Phone: </div>
				<div class="fields">
					<input type="text" name="phone" id="phone" value="<?php echo $userData['phone'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<input type="submit" class="black button" value="submit" />
		
		</form>

	</div>

	<div class="display_info" id="disp_info_div" style="display:none;">
		<div class="title">Display Info</div>

		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="cmd" value="update_display" />
			<input type="hidden" name="empty_ext" value="<?php echo $empty; ?>" />
			<div class="even">
				<div class="words">Gender: </div>
				<div class="fields">
					<select id="gender" name="gender">
						<?php echo $gender; ?>
					</select>
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Age: </div>
				<div class="fields">
					<input type="text" name="age" id="age" value="<?php echo $userInfo['age'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Hair Color: </div>
				<div class="fields">
					<input type="text" name="hair" id="hair" value="<?php echo $userInfo['hair'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Eyes Color: </div>
				<div class="fields">
					<input type="text" name="eyes" id="eyes" value="<?php echo $userInfo['eyes'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Height: </div>
				<div class="fields">
					<input type="text" name="height" id="height" value="<?php echo $userInfo['height'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Bust Size: </div>
				<div class="fields">
					<input type="text" name="bust" id="bust" value="<?php echo $userInfo['bust'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Weight: </div>
				<div class="fields">
					<input type="text" name="weight" id="weight" value="<?php echo $userInfo['weight'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Waist Size: </div>
				<div class="fields">
					<input type="text" name="waist" id="waist" value="<?php echo $userInfo['waist'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Affiliation: </div>
				<div class="fields">
					<input type="text" name="affiliation" id="affiliation" value="<?php echo $userInfo['affiliation'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Ethnicity: </div>
				<div class="fields">
					<input type="text" name="ethnicity" id="ethnicity" value="<?php echo $userInfo['ethnicity'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Availability: </div>
				<div class="fields">
					<input type="text" name="availability" id="availability" value="<?php echo $userInfo['availability'];?>" />
					<span class="redstar">*</span>
					<span>Incall / Outcall</span>
				</div>
				<div class="clear"></div>
			</div>

			<div class="even">
				<div class="words">Sexual Preference: </div>
				<div class="fields">
					<input type="text" name="sexual_preference" id="sexual_preference" value="<?php echo $userInfo['sexual_preference'];?>" />
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>
			
			<br />
			
			<div class="even">
				<div class="words" style="width:50%;">Categories:</div>
				<div class="clear"></div>
			</div>
			<br />
			
			<?php foreach($categories as $category):?>
				<div class="check">
					<input type="checkbox" name="category[]" value="<?php echo $category['id_category']; ?>" />
					<?php echo $category['category_name']; ?>
				
				</div>
				
			
			<?php endforeach; ?>
			
			<div class="clear"></div>
			<br />
				
			<div class="even">
				<div class="words">About Me: </div>
				<div class="fields">
					<textarea rows="7" cols="25" name="about_me" id="about_me"><?php echo $userInfo['about_me'];?></textarea>
					<span class="redstar">*</span>
				</div>
				<div class="clear"></div>
			</div>

			<input type="submit" class="black button" value="Submit"/>

		</form>

	</div>

	<div class="display_info" id="image_div" style="display:none;">
		<?php if (isset($img_path)):?>

			<div class="title">Current Images (Maximum 4 images allowed)</div>

			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="hidden" name="cmd" value="delete_img" />
				<?php
					echo $img_path;
				?>
				<div class="clear"></div>
				<input type="submit" class="black button" value="Delete" />
			</form>

			<div class="clear"></div>

		<?php endif;?>
		
		<?php if($img_count != 4):?>
		
			<div class="title">Upload New Images</div>
	
			<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="hidden" name="cmd" value="uploade_images" />
				
				
					<?php for($i=$img_count; $i < 4; $i++): ?>
					
					<div class="name">
						<div class="even">
							<div class="fields"><input type="file" name="image[]" id="image1" /></div>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>
					
					<?php endfor; ?>
				
				<input type="submit" class="black button" value="Upload" />
	
			</form>
			
		<?php endif; ?>
		
	</div>


	<?php include ("../templates/footer.php"); ?>

</body>

</html>