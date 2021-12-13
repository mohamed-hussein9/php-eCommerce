<?php
session_start();
$Title = 'MEMBERS';
if (isset($_SESSION['Username'])) {
	include 'init.php';

	/////

	$do = isset($_GET['do']) ? $_GET['do'] : 'manage';
	/////

	if ($do === 'manage') {


		$query = '';
		if (isset($_GET['mum']) && $_GET['mum'] == "pending") {
			$query = "WHERE RegStatus=0";
		}

		if (isset($_GET['userid'])) {
			$query = "WHERE UserID=" . $_GET['userid'] . "";
		}
		if (isset($_GET['search'])) {
			$query = "WHERE Username LIKE'%" . str_replace('%20', ' ', $_GET['search']) . "%'";
		}


		$stmt = $con->prepare("select * from users $query");

		$stmt->execute();
		$rows = $stmt->fetchAll(); ?>
		<div class="admin_search">
			<form action="">
				<input spellcheck="off" autocomplete="off" id="search_box" type="text" placeholder="Search For User">
				<span class="search_btn" onclick="location.href='members.php?search='+$(this).prev().val()">search</span>
			</form>

		</div>
		<?php if (isset($_GET['search'])) {
			echo '<h1 class="res_search_h1">Results of "' . str_replace('%20', ' ', $_GET['search']) . '"</h1>';
		} ?>

		<div class="table_container">
			<a href="members.php?do=add"><i class="fas fa-user-plus fa-fw">
				</i> Add New Member</a>
			<table class="table_mumbers">

				<tr class="th">
					<td>#ID</td>
					<td>Avatar</td>
					<td>Username</td>
					<td>Email</td>
					<td>Fullname</td>

					<td>Registerd Date</td>
					<td>Controll</td>
				</tr>
				<img src="" alt="">




				<?php
				foreach ($rows as $result) {
					$avatar_src = !empty($result['avatar']) ? $result['avatar'] : 'default.png';
					echo '<tr ">
							<td>' . $result['UserID'] . '</td>
							<td><img src="upload/avatars/' . $avatar_src . '" alt="' . $result['Username'] . '"></td>
							<td>' . $result['Username'] . '</td>
							<td>' . $result['Email'] . '</td>
							<td>' . $result['Fullname'] . '</td>
				
							<td>' . $result['Date'] . ' </td>
							<td> <a href="members.php?do=edit&id=' . $result['UserID'] . '" >
							EDIT</a>
							<a href="members.php"
							class="delete_item_and_user" delete_id="' . $result['UserID'] . '"> DELETE</a>
							' ?>
					<?php
					if ($result["RegStatus"] == 0) {
						echo '<a href="members.php"
							 class="approve" approve_id="' . $result['UserID'] . '"> approve</a>';
					}
					?>


				<?php '
							 </td>
						</tr>';
				}
				echo '</table></div>';




				//


			}

			//===============start add member
			elseif ($do === 'add') {

				?>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?do=insert" class="edit_profile" enctype="multipart/form-data">
					<h2>Add New Member</h2>
					<div>
						<label for="username">Username</label>
						<input id="username" type="text" name="username" autocomplete="off" spellcheck="false" placeholder="user name " required="required">

					</div>
					<div>
						<label for="password">Password</label>


						<input type="password" name="password" placeholder="use hard password " autocomplete="new-password" spellcheck="false" required="required"><i class="password_eye fa fa-eye fa-fw"></i><i class="password_deye fa fa-eye-slash fa-fw"></i>

					</div>
					<div>
						<label for="email">Email</label>
						<input type="email" name="email" autocomplete="off" spellcheck="false" placeholder="Email " required="required">

					</div>
					<div>
						<label for="fullname">Full Name</label>
						<input type="text" name="fullname" autocomplete="off" spellcheck="false" placeholder="profile name ">

					</div>
					<div>
						<label for="avatar">Avatar</label>
						<input type="file" name="avatar" id="avatar">

					</div>
					<input type="submit" value="Add member">

				</form>


				<?php


			}
			//end add member page


			//=================start delete member

			elseif ($do === 'delete') {
				if (isset($_GET['id']) && isset($_SERVER['HTTP_REFERER'])) {

					$ID = $_GET['id'];
					$count =	checkData("UserID", "users", $ID);


					if ($count == 1) {




						$stimg = $con->prepare("SELECT avatar FROM users WHERE UserID=$ID");
						$stimg->execute();
						$img = $stimg->fetch();
						$get_images = GetAllFrom('Image', 'items', 'WHERE Member_ID = ' . $ID, 'ItemID');
						$stmt = $con->prepare("DELETE FROM users WHERE UserID=:i");
						$stmt->bindParam(":i", $ID);
						$stmt->execute();
						$row = $stmt->rowCount();
						if ($row > 0) {
							foreach ($get_images as $image) {
								if (file_exists('upload/item_images/' . $image['Image'])) {
									if ($image['Image'] != 'default.jpg') {
										unlink('upload/item_images/' . $image['Image']);
									}
								}
							}

							if (isset($_SESSION['User'])) {
								unset($_SESSION['User']);
								unset($_SESSION['UserID']);
							}


							echo "success from php";

							if ($img != 'default.png') {
								if (file_exists('upload/avatars/' . $img['avatar'])) {
									unlink("upload/avatars/" . $img['avatar'] . "");
								}
							}
						} else {
							echo "error from php";
						}
					}
				} else {
					header('location: members.php');
				}
			}

			//end delete member



			//===================start insert page
			elseif ($do === 'insert') {
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {

					$user = $_POST['username'];
					$pass = sha1($_POST['password']);
					$email = $_POST['email'];
					$full = $_POST['fullname'];
					$avatar = $_FILES['avatar'];
					$avatar_name = $avatar['name'];
					$avatar_size = $avatar['size'];
					$avatar_tmp = $avatar['tmp_name'];
					$avatar_type = $avatar['type'];
					$allowd_extentions = array('jpg', 'png', 'gif');
					$a = explode('.', $avatar['name']);
					$avatar_extention = strtolower(end($a));






					echo '<div class="errors_list">';
					$errors = array();
					if (strlen($user) < 4 || strlen($user) > 20) {
						$errors[] = 'User name must be between (4-20)characters ';
					}
					if (empty($_POST['username'])) {
						$errors[] = 'you can\'t leave Username empty ';
					}
					if (empty($_POST['password'])) {
						$errors[] = 'you can\'t leave password empty ';
					}
					if (empty($_POST['email'])) {
						$errors[] = 'you can\'t leave Email empty ';
					}
					if (empty($_POST['fullname'])) {
						$errors[] = 'you can\'t leave the full name empty ';
					}
					if (!empty($avatar_name) && !in_array($avatar_extention, $allowd_extentions)) {
						$errors[] = 'This File Not Allowed';
					}
					if (empty($avatar_name)) {
						$errors[] = 'You Have To Upload Avatar';
					}
					foreach ($errors as $err_result) {
						echo '<div class="err_daner">' . $err_result . '</div>';
					}

					if (!empty($err_result)) {
						add_error_p();
					}
					echo '</div>';
					///
					if (empty($err_result)) {
						echo '<script>$(".errors_list").hide();</script>';

						$result = checkData("Username", "users", $user);
						if ($result > 0) {
							redirect_error("The Username already exists", 'back', 3);
						} else {
							$rand = rand(0, 100000);
							move_uploaded_file($avatar_tmp, 'upload/avatars/' . $rand . '_' . $avatar_name . '');
							$stmt = $con->prepare("INSERT INTO users (Username,Password,Email,Fullname,RegStatus,Date,avatar)
							values(?,?,?,?,1,now(),?)");
							$stmt->execute(array($user, $pass, $email, $full, $rand . '_' . $avatar_name));
							redirect_success("member added ", 'members.php', 3);
						}
					}
				} else {
					redirect_error("PAGE NOT FOUND", 'members.php', 5);
				}
				//echo '<div class="errors_list"><p>ERROR</p><div>PAGE NOT FOUND</div></div>';}
			}
			//end insert page

			///======================start edit page
			elseif ($do === 'edit') {
				if (isset($_GET['id'])) {
					$ID = $_GET['id'];
					$stmt = $con->prepare("SELECT * FROM users WHERE UserID=?");
					$stmt->execute(array($ID));
					$row = $stmt->fetch();
					$count = $stmt->rowCount();


					if ($count == 1) {
				?>

						<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?do=update&id=<?php echo $ID; ?>" class="edit_profile" enctype="multipart/form-data">
							<h2>Edit Profile</h2>
							<div>
								<label for="username">Username</label>
								<input type="text" name="username" value="<?php echo $row['Username']; ?>" required="required" autocomplete="off" spellcheck="false">

							</div>
							<div>
								<span style="opacity:0">*</span>
								<label for="password"> <span></span> Password</label>
								<input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">

								<input type="password" name="newpassword" autocomplete="new-password" spellcheck="false"><i class="password_eye fa fa-eye fa-fw"></i><i class="password_deye fa fa-eye-slash fa-fw"></i>

							</div>
							<div>
								<label for="email">Email</label>
								<input type="email" name="email" required="required" value="<?php echo $row['Email']; ?>" autocomplete="off" spellcheck="false">

							</div>
							<div>
								<label for="fullname">Full Name</label>
								<input type="text" name="fullname" value="<?php echo $row['Fullname']; ?>" autocomplete="off" spellcheck="false" required="required">

							</div>
							<div>
								<label for="avatar">Change Avatar</label>
								<input type="file" name="avatar" id="avatar">

							</div>
							<input type="submit" value="Save">

						</form>

		<?php

					}
				} else {
					header("location: index.php");
				}
			}


			///end edit page


			//==================== start update page

			elseif ($do === 'update') {
				if ($_SERVER['REQUEST_METHOD'] == "POST") {


					$user = $_POST['username'];
					$email = $_POST['email'];
					$full = $_POST['fullname'];
					$avatar = $_FILES['avatar'];
					$avatar_name = $avatar['name'];
					$avatar_size = $avatar['size'];
					$avatar_tmp = $avatar['tmp_name'];
					$avatar_type = $avatar['type'];
					$allowd_extentions = array('jpg', 'png', 'gif');
					$a = explode('.', $avatar['name']);
					$avatar_extention = strtolower(end($a));

					//password trick
					$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
					//validate the form
					echo '<div class="errors_list">';
					$errors = array();
					if (strlen($user) < 4 || strlen($user) > 20) {
						$errors[] = 'User name must be between (4-20)characters ';
					}
					if (empty($_POST['username'])) {
						$errors[] = 'you can\'t leave Username empty ';
					}
					if (empty($_POST['email'])) {
						$errors[] = 'you can\'t leave Email empty ';
					}
					if (empty($_POST['fullname'])) {
						$errors[] = 'you can\'t leave the full name empty ';
					}
					if (!empty($avatar_name) && !in_array($avatar_extention, $allowd_extentions)) {
						$errors[] = 'This File Not Allowed';
					}
					if (empty($avatar_name)) {
						$errors[] = 'You Have To Upload Avatar';
					}
					foreach ($errors as $err_result) {
						echo '<div class="err_daner">' . $err_result . '</div>';
					}

					if (!empty($err_result)) {
						add_error_p();
					}
					echo '</div>';
					if (empty($err_result)) {

						echo '<script>$(".errors_list").hide();</script>';
						$stmt1 = $con->prepare("SELECT UserID from users where Username=? and UserID !=? ");
						$stmt1->execute(array($user, $_GET['id']));
						$count1 = $stmt1->rowCount();
						if ($count1 > 0) {
							redirect_error('this user is exist', 'back', 3);
						} else {
							$avataroldname = GetAllFrom("avatar", "users", "WHERE UserID={$_GET['id']}", "UserID", "DESC",  "fetch()");
							$rand = rand(0, 100000);
							move_uploaded_file($avatar_tmp, 'upload/avatars/' . $rand . '_' . $avatar_name . '');
							$stmt = $con->prepare("UPDATE users SET Username= ?,Password=? ,Email=? , Fullname=?,avatar=? WHERE 	UserID=? ");
							$stmt->execute(array($user, $pass, $email, $full, $rand . '_' . $avatar_name, $_GET['id']));
							if ($stmt) {
								$bath = 'upload/avatars/' . $avataroldname['avatar'];
								if (file_exists($bath) && !empty($avataroldname['avatar'])) {
									unlink($bath);
								}
							}
							$count = $stmt->rowCount();



							if ($count > 0) {
								redirect_success('DATA UPDATED', 'back', 3);
							} else {
								header("location: members.php");
							}
						}
					}
				} else {
					$msg = "you dont have premetion to acces to this page";
					redirect_error($msg, 'members.php', 5);
				}
			}
			//end update page

			//start approve page
			elseif ($do === "approve") {
				if (isset($_SERVER['HTTP_REFERER'])) {
					$ID = $_GET['id'];
					if (checkData("UserID", "users", $ID) == 1) {
						$stmt = $con->prepare("UPDATE users SET RegStatus=1 WHERE UserID= ?");
						$stmt->execute(array($ID));
						$row = $stmt->rowCount();
						//set notification
						if ($stmt) {
							$n = 'Your membership approved you can now add items and post comments';
							$stmt_n = $con->prepare("INSERT INTO users_notification (notification,date,user_id)
																values(?,now(),?)");
							$stmt_n->execute(array($n, $ID));
						} //end set notification
					} else {
						redirect_error("there is no users with this id", "members.php", $second = 3);
					}
				} else {
					redirect_error("dont try to access here directly", "members.php", $second = 3);
				}
			}



			//end approve page

			//start approve All page
			elseif ($do === "approveall") {
				if (isset($_SERVER['HTTP_REFERER'])) {
					$_GET['approveall'];
					if ($_GET['approveall'] === 'all') {
						$get_pending_users = GetAllFrom('UserID', 'users', 'WHERE RegStatus = 0', 'UserID', 'DESC');

						$stmt = $con->prepare("UPDATE users SET RegStatus=1 ");
						$stmt->execute(array());
						$row = $stmt->rowCount();
						//send notification to users
						if ($stmt) {
							foreach ($get_pending_users as $getid) {
								$n = 'Your membership approved you can now add items and post comments';
								$stmt_n = $con->prepare("INSERT INTO users_notification (notification,date,user_id)
																values(?,now(),?)");
								$stmt_n->execute(array($n, $getid['UserID']));
							}
						} //send notification

						if ($row > 0) {
							redirect_success("all users Approved ", "members.php", $second = 3);
						}
					} else {
						redirect_error("Uncorrect aproval value", "members.php", $second = 3);
					}
				} else {
					redirect_error("dont try to access here directly", "members.php", $second = 3);
				}
			} //end approve All page





			else {
				redirect_error("PAGE NOT FOUND", "members.php");
			} // if($do==='manage')


			//////

			include $templates . 'footer.php';
		} else {
			header("location: index.php");
			exit();
		} //if(isset($_SESSION['Username']))

		?>