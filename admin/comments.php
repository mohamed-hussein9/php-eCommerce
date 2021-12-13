<?php
ob_start();
session_start();
$Title = 'COMMENTS';
if (isset($_SESSION['Username'])) {
	include 'init.php';

	/////
	$do = isset($_GET['do']) ? $_GET['do'] : 'manage';
	/////

	if ($do === 'manage') {
		$query = '';
		if (isset($_GET['comment_id'])) {
			$query = 'WHERE comment_id=' . $_GET['comment_id'];
		}

		if (isset($_GET['pending_comments']) && $_GET['pending_comments'] === "pending") {
			$query = "WHERE comments.status = 0";
		}
		if (isset($_GET['search'])) {
			$query = "WHERE comments.comment LIKE'%" . str_replace('+', ' ', $_GET['search']) . "%'";
		}



		$stmt = $con->prepare("SELECT comments.*,users.Username,items.Name
                        FROM comments 
                          INNER JOIN users
                          ON users.UserID = comments.user_id
                          INNER JOIN items 
						  ON  items.ItemID = comments.item_id 


						  " . $query . "
						  ORDER BY comment_id DESC");

		$stmt->execute();
		$rows = $stmt->fetchAll(); ?>
		<!-- search comment -->
		<div class="admin_search">
			<form action="comments.php">
				<input name="search" spellcheck="off" autocomplete="off" type="text" placeholder="Search For comment">
				<input type="submit" value="search">
			</form>

		</div>
		<?php if (isset($_GET['search'])) {
			echo '<h1 class="res_search_h1">Results of "' . str_replace('+', ' ', $_GET['search']) . '"</h1>';
		} ?>
		<!-- end search  -->

		<div class="table_container">
			<h1 style="width:fit-content;margin:auto; margin-top:20px;padding:10px">Manage Comments</h1>

			<table class="table_mumbers">

				<tr class="th">
					<td>ID</td>
					<td>Comment</td>
					<td>Item name</td>
					<td>User</td>

					<td>comment date</td>
					<td>Controll</td>
				</tr>




				<?php
				foreach ($rows as $result) {
					echo '<tr ">
							<td>' . $result['comment_id'] . '</td>
							<td>' . $result['comment'] . '</td>
							<td>' . $result['Name'] . '</td>
							<td>' . $result['Username'] . '</td>

							<td>' . $result['comment_date'] . ' </td>
							<td> <a href="comments.php?do=edit&id=' . $result['comment_id'] . '" >
							 EDIT</a>
							<a href="comments.php?do=delete&id=' . $result['comment_id'] . '"
							 class="confirm"> DELETE</a>
							 ' ?>
					<?php
					if ($result["status"] == 0) {
						echo '<a href="comments.php"
							class="approve" approve_id="' . $result['comment_id'] . '"> approve</a>';
					}
					?>


					<?php '
							 </td>
						</tr>';
				}
				echo '</table></div>';




				//


			}




			//=================start delete member

			elseif ($do === 'delete') {
				if (isset($_GET['id']) && isset($_SERVER['HTTP_REFERER'])) {

					$ID = $_GET['id'];
					$count =	checkData("comment_id", "comments", $ID);

					if ($count == 1) {
						$stmt = $con->prepare("DELETE FROM comments WHERE comment_id=:i");
						$stmt->bindParam(":i", $ID);
						$stmt->execute();
						$row = $stmt->rowCount();
						if ($row > 0) {
							redirect_success($row . ' COMMENT DELETED', 'back', 3);
						} else {
							redirect_error("feild to delete", 'back', 3);
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


					///
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
							$stmt = $con->prepare("INSERT INTO users (Username,Password,Email,Fullname,RegStatus,Date)
							values(?,?,?,?,1,now())");
							$stmt->execute(array($user, $pass, $email, $full));
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
					$stmt = $con->prepare("SELECT comment, comment_id FROM comments WHERE comment_id=?");
					$stmt->execute(array($ID));
					$row = $stmt->fetch();
					$count = $stmt->rowCount();


					if ($count == 1) {
					?>

						<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?do=update&id=<?php echo $ID; ?>" class="edit_profile">
							<h2>Edit comment</h2>
							<textarea name="comment" id="" cols="30" rows="10"><?php echo $row['comment']; ?></textarea>
							<input type="hidden" value="<?php echo  $row['comment_id'];  ?>" name="id">
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


					$comment = $_POST['comment'];
					$ID = $_POST['id'];





					$stmt = $con->prepare("UPDATE comments SET comment= ? WHERE 	comment_id=? ");
					$stmt->execute(array($comment, $ID));

					$count = $stmt->rowCount();



					if ($count > 0) {
						redirect_success('COMMENT UPDATED', 'back', 3);
					} else {
						header("location: comments.php");
					}
				} else {
					$msg = "you dont have premetion to acces to this page";
					redirect_error($msg, 'comments.php', 5);
				}
			}
			//end update page

			//start approve page
			elseif ($do === "approve") {
				if (isset($_SERVER['HTTP_REFERER'])) {
					$ID = $_GET['id'];
					if (checkData("comment_id", "comments", $ID) == 1) {

						$stmt = $con->prepare("UPDATE comments SET status=1 WHERE comment_id= ?");
						$stmt->execute(array($ID));
						$row = $stmt->rowCount();
						//set notification
						if ($stmt) {
							$stmt4 = $con->prepare('SELECT comments.user_id,
													items.Name
													 FROM comments
													inner join items
															on items.ItemID=comments.item_id
													 WHERE comments.comment_id=?');
							$stmt4->execute(array($ID));
							$u_n_id = $stmt4->fetch();
							$n = 'Your Comment on the Product   ' . $u_n_id['Name'] . '  has been approved ';
							$stmt_n = $con->prepare("INSERT INTO users_notification (notification,date,user_id)
																values(?,now(),?)");
							$stmt_n->execute(array($n, $u_n_id['user_id']));
						} // end set notification
						if ($row == 1) {


							redirect_success("Approved", "back", $second = 3);
						}
					} else {
						redirect_error("there is no comment with this id", "members.php", $second = 3);
					}
				} else {
					redirect_error("dont try to access here directly", "members.php", $second = 3);
				}
			} //end approve page




			//start approve All page
			elseif ($do === "approveall") {
				if (isset($_SERVER['HTTP_REFERER'])) {
					$_GET['approveall'];
					if ($_GET['approveall'] === 'all') {

						$stmt = $con->prepare("UPDATE comments SET status = 1 ");
						$stmt->execute(array());
						$row = $stmt->rowCount();
						if ($row > 0) {
							redirect_success("all comments Approved ", "comments.php", $second = 3);
						}
					} else {
						redirect_error("Uncorrect aproval value", "comments.php", $second = 3);
					}
				} else {
					redirect_error("dont try to access here directly", "comments.php", $second = 3);
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


		ob_end_flush();
		?>