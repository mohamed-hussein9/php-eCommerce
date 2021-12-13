<?php
ob_start();
session_start();
$Title = 'ITEMS';
if (isset($_SESSION['Username'])) {
	include 'init.php';
	$do = isset($_GET['do']) ? $_GET['do'] : 'manage';
	if ($do == 'manage') {
		//echo '<a href="?do=add">add new item</a>';
		$where = isset($_GET['itemid']) ? 'WHERE ItemID= ' . $_GET['itemid'] . '' : '';
		$where = isset($_GET['pending_items']) && $_GET['pending_items'] == "pending" ? 'WHERE Approve=0' : '';
		if (isset($_GET['search'])) {
			$where = "WHERE items.Name LIKE '%" . str_replace('+', ' ', $_GET['search']) . "%'";
		}
		$stmt = $con->prepare("SELECT 
													items.*,
													categories.Name AS CategoryName,
													users.Username 
											FROM items
												INNER JOIN categories 
														ON categories.ID=items.Cat_ID
											INNER JOIN users
														ON users.UserID=items.Member_ID
											$where
											ORDER BY ItemID DESC
														");

		$stmt->execute();
		$rows = $stmt->fetchAll(); ?>
		<!-- search comment -->
		<div class="admin_search">
			<form action="items.php">
				<input name="search" spellcheck="off" autocomplete="off" type="text" placeholder="Search For Product">
				<input type="submit" value="search">
			</form>

		</div>
		<?php if (isset($_GET['search'])) {
			echo '<h1 class="res_search_h1">Results of "' . str_replace('+', ' ', $_GET['search']) . '"</h1>';
		} ?>
		<!-- end search  -->


		<div class="table_container">
			<a href="items.php?do=add"><i class="fas fa-cart-plus fa-fw">
				</i> Add New Item</a>
			<table class="table_mumbers">

				<tr class="th">
					<td>#ID</td>
					<td>Name</td>
					<td>description</td>
					<td>status</td>
					<td>price</td>
					<td> Date</td>
					<td> category</td>
					<td> username</td>
					<td>Controll</td>
				</tr>




				<?php
				foreach ($rows as $result) {
					echo '<tr ">
							<td>' . $result['ItemID'] . '</td>
							<td>' . $result['Name'] . '</td>
							<td>' . $result['Description'] . '</td>
							<td>';
					if ($result['Status'] == 1) {
						echo 'New';
					}
					if ($result['Status'] == 2) {
						echo 'like new';
					}
					if ($result['Status'] == 3) {
						echo 'used';
					}
					if ($result['Status'] == 4) {
						echo 'old';
					}



					echo '</td>
							<td>' . $result['Price'] . '</td>
				
							<td>' . $result['Add_Date'] . ' </td>
							<td>' . $result['CategoryName'] . '</td>
							<td>' . $result['Username'] . '</td>
							<td> <a href="items.php?do=edit&id=' . $result['ItemID'] . '" >
							 EDIT</a>
							<a href="items.php"
							 class="delete_item_and_user" delete_id="' . $result['ItemID'] . '"> DELETE</a>
							 ';
					if ($result["Approve"] == 0) {
						echo '<a href="items.php"
							class="approve" approve_id="' . $result['ItemID'] . '"> approve</a>';
					} ?>



				<?php '
							 </td>
						</tr>';
				}
				echo '</table></div>';
			}


			//========== Start Add item Page
			elseif ($do == 'add') {

				?>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?do=insert" class="edit_profile add_status">
					<h2>Add New Item</h2>
					<div>
						<label for="name">Name</label>
						<input id="name" type="text" name="name" autocomplete="off" spellcheck="false" placeholder="Category Name " required="required">
					</div>
					<div>
						<label for="desc">Description</label>
						<input id="desc" type="text" name="description" placeholder="description of this item" required="required">
					</div>
					<div>
						<label for="price">Price</label>
						<input id="price" type="text" name="price" placeholder="item price" required="required">
					</div>
					<div>
						<label for="c_made">Contry made</label>
						<input id="c_made" type="text" name="c_made" placeholder="item contry made" required="required">
					</div>
					<div>
						<label for="status" class="status_label">Status</label>
						<select name="status" class="status_select" id="status">
							<option value="0">...</option>
							<option value="1">new</option>
							<option value="2">like new</option>
							<option value="3">used</option>
							<option value="4">old</option>
						</select>
					</div>
					<div>
						<label for="status" class="status_label">Member</label>
						<select name="member_id" class="status_select" id="status">
							<option value="0">...</option>
							<?php
							$stmt2 = $con->prepare("SELECT * FROM users");
							$stmt2->execute();
							$rows = $stmt2->fetchAll();
							foreach ($rows as $row) {
								echo '<option value="' . $row['UserID'] . '">' . $row['Username'] . '</option>';
							}
							?>
						</select>
					</div>
					<div>
						<label for="status" class="status_label">Category</label>
						<select name="cat_id" class="status_select" id="status">
							<option value="0">...</option>
							<?php
							$stmt3 = $con->prepare("SELECT * FROM categories");
							$stmt3->execute();
							$rows = $stmt3->fetchAll();
							foreach ($rows as $row) {
								echo '<option value="' . $row['ID'] . '">' . $row['Name'] . '</option>';
							}
							?>
						</select>
					</div>
		</div>
		<input type="submit" value="Add Item">
		</form>

		<?php

			}
			//---------- end Add ITEM Page

			//=====================start edit page===================//
			elseif ($do == 'edit') {
				if (isset($_GET['id'])) {
					$ID = $_GET['id'];
					$stmt = $con->prepare("SELECT * FROM items WHERE ItemID=?");
					$stmt->execute(array($ID));
					$row = $stmt->fetch();
					$count = $stmt->rowCount();
					if ($count > 0) {
		?>
				<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?do=update" class="edit_profile add_status">
					<h2>Edit Item</h2>
					<div>
						<label for="name">Name</label>
						<input id="name" type="text" name="name" autocomplete="off" spellcheck="false" placeholder="Category Name " required="required" value="<?php echo $row['Name']; ?>">

					</div>
					<div>
						<label for="desc">Description</label>


						<input id="desc" type="text" name="description" placeholder="description of this item" required="required" value="<?php echo $row['Description']; ?>">

					</div>

					<div>
						<label for="price">Price</label>


						<input id="price" type="text" name="price" placeholder="item price" required="required" value="<?php echo $row['Price']; ?>">

					</div>
					<div>
						<label for="c_made">Contry made</label>


						<input id="c_made" type="text" name="c_made" placeholder="item contry made" required="required" value="<?php echo $row['Contry_made']; ?>">
					</div>
					<div>
						<label for="status" class="status_label">Status</label>

						<select name="status" class="status_select" id="status">
							<option value="1" <?php if ($row['Status'] == 1) {
													echo 'selected';
												}  ?>>new</option>
							<option value="2" <?php if ($row['Status'] == 2) {
													echo 'selected';
												}  ?>>like new</option>
							<option value="3" <?php if ($row['Status'] == 3) {
													echo 'selected';
												}  ?>>used</option>
							<option value="4" <?php if ($row['Status'] == 4) {
													echo 'selected';
												}  ?>>old</option>
						</select>
					</div>
					<div>
						<label for="status" class="status_label">Member</label>

						<select name="member_id" class="status_select" id="status">
							<?php
							$stmt2 = $con->prepare("SELECT * FROM users");
							$stmt2->execute();
							$rows = $stmt2->fetchAll();
							foreach ($rows as $row2) {
								echo '<option value="' . $row2['UserID'] . '"';
								if ($row['Member_ID'] == $row2['UserID']) {
									echo 'selected';
								}

								echo '>' . $row2['Username'] . '</option>';
							}
							?>

						</select>
					</div>
					<div>
						<label for="status" class="status_label">Category</label>

						<select name="cat_id" class="status_select" id="status">
							<?php
							$stmt3 = $con->prepare("SELECT * FROM categories");
							$stmt3->execute();
							$rows = $stmt3->fetchAll();
							foreach ($rows as $row3) {

								echo '<option value="' . $row3['ID'] . '"';
								if ($row['Cat_ID'] == $row3['ID']) {
									echo 'selected';
								}
								echo '>' . $row3['Name'] . '</option>';
							}
							?>

						</select>
					</div>
					<div>
						<label for="tags">Tags </label>
						<input value="<?php echo $row['tags']; ?>" id="tags" type="text" name="tags" placeholder="Type Tags Separated By a Comma">
					</div>
					</div>
					<input type="hidden" value="<?php echo $row['ItemID'] ?>" name="id">
					<input type="submit" value="Save">

				</form>
				<?php
						if (checkData('item_id', "comments", "" . $ID . "") > 0) {
							$stmt = $con->prepare("SELECT comments.*,users.Username,items.Name
                        FROM comments 
                          INNER JOIN users
                          ON users.UserID = comments.user_id
                          INNER JOIN items 
						  ON  items.ItemID = comments.item_id 
						
						  WHERE item_id=" . $ID . " 
						  
						   ORDER BY comment_id DESC ");

							$stmt->execute();
							$rows = $stmt->fetchAll(); ?>

					<div class="table_container">
						<h1 style="width:fit-content;margin:auto; margin-top:20px;padding:10px">Manage Comments</h1>

						<table class="table_mumbers">

							<tr class="th">

								<td>Comment</td>

								<td>User</td>

								<td>comment date</td>
								<td>Controll</td>
							</tr>




							<?php
							foreach ($rows as $result) {
								echo '<tr ">
							
							<td>' . $result['comment'] . '</td>
							
							<td>' . $result['Username'] . '</td>

							<td>' . $result['comment_date'] . ' </td>
							<td> <a href="comments.php?do=edit&id=' . $result['comment_id'] . '" >
							 EDIT</a>
							<a href="comments.php?do=delete&id=' . $result['comment_id'] . '"
							 class="confirm"> DELETE</a>
							 ' ?>
								<?php
								if ($result["status"] == 0) {
									echo '<a href="comments.php?do=approve&id=' . $result['comment_id'] . '"
							 > approve</a>';
								}
								?>


						<?php '
							 </td>
						</tr>';
							}
							echo '</table></div>';
						} else {
							echo '<h1 style="width:fit-content;margin:auto; margin-top:20px;padding:10px">No comments in this item</h1>';
						}

						?>

		<?php }
				}
			}

			//-----------------------end edit page--------------------//


			//============start insert page=======//
			elseif ($do == 'insert') {

				if ($_SERVER['REQUEST_METHOD'] == "POST") {


					$name = $_POST['name'];
					$desc = $_POST['description'];
					$price = $_POST['price'];
					$c_made = $_POST['c_made'];
					$status = $_POST['status'];
					$member_id = $_POST['member_id'];
					$cat_id = $_POST['cat_id'];




					//validate the form
					echo '<div class="errors_list">';
					$errors = array();
					if (strlen($name) < 4 || strlen($name) > 20) {
						$errors[] = 'Item name must be between <strong>(4-20)</strong> characters ';
					}
					if (empty($_POST['description'])) {
						$errors[] = 'you can\'t leave<strong> description</strong> empty ';
					}
					if (empty($_POST['price'])) {
						$errors[] = 'you can\'t leave<strong> Price</strong> empty ';
					}
					if (empty($_POST['c_made'])) {
						$errors[] = 'you can\'t leave the <strong>contry made</strong> empty ';
					}
					if (empty($_POST['status'])) {
						$errors[] = 'you can\'t leave the <strong>status</strong> empty ';
					}
					if (empty($_POST['member_id'])) {
						$errors[] = 'you can\'t leave the <strong> User</strong> empty ';
					}
					if (empty($_POST['cat_id'])) {
						$errors[] = 'you can\'t leave the <strong>category</strong> empty ';
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
						$stmt = $con->prepare("INSERT INTO items ( Name,Description,Price, Status,
				Contry_made,Cat_ID,Member_ID,Add_Date ) values(?,?,?,?,?,?,?,now()) 
				");
						$stmt->execute(array($name, $desc, $price, $status, $c_made, $cat_id, $member_id));

						$count = $stmt->rowCount();



						if ($count > 0) {
							redirect_success('DATA INSERTED', 'items.php', 3);
						} else {
							header("location: members.php");
						}
					}
				} else {
					$msg = "you dont have premetion to acces to this page";
					redirect_error($msg, 'members.php', 5);
				}
			}

			//----------------end insert category----------//
			//============== start update category============/
			elseif ($do == 'update') {
				if ($_SERVER['REQUEST_METHOD'] == "POST") {


					$name = $_POST['name'];
					$desc = $_POST['description'];
					$price = $_POST['price'];
					$c_made = $_POST['c_made'];
					$status = $_POST['status'];
					$member_id = $_POST['member_id'];
					$cat_id = $_POST['cat_id'];
					$tags = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
					$tags = strtolower(str_replace(" ", "", $tags));





					//validate the form
					echo '<div class="errors_list">';
					$errors = array();
					if (strlen($name) < 4 || strlen($name) > 20) {
						$errors[] = 'Item name must be between <strong>(4-20)</strong> characters ';
					}
					if (empty($_POST['description'])) {
						$errors[] = 'you can\'t leave<strong> description</strong> empty ';
					}
					if (empty($_POST['price'])) {
						$errors[] = 'you can\'t leave<strong> Price</strong> empty ';
					}
					if (empty($_POST['c_made'])) {
						$errors[] = 'you can\'t leave the <strong>contry made</strong> empty ';
					}
					if (empty($_POST['status'])) {
						$errors[] = 'you can\'t leave the <strong>status</strong> empty ';
					}
					if (empty($_POST['member_id'])) {
						$errors[] = 'you can\'t leave the <strong> User</strong> empty ';
					}
					if (empty($_POST['cat_id'])) {
						$errors[] = 'you can\'t leave the <strong>category</strong> empty ';
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
						$stmt = $con->prepare("UPDATE items SET Name= ?,Description=? ,Price=? , status=?,
				Contry_made=?,Cat_ID=? ,Member_ID=?,tags=?  WHERE 	ItemID=?  
				");
						$stmt->execute(array($name, $desc, $price, $status, $c_made, $cat_id, $member_id, $tags, $_POST['id']));

						$count = $stmt->rowCount();



						if ($count > 0) {
							redirect_success('DATA UPDATED', 'items.php', 3);
						} else {
							// header("location: members.php");
							echo 'ERROR';
						}
					}
				} else {
					$msg = "you dont have premetion to acsess to this page";
					redirect_error($msg, 'members.php', 5);
				}
			}


			//=======================start delete page==================//
			elseif ($do == 'delete') {
				if (isset($_GET['id']) && isset($_SERVER['HTTP_REFERER'])) {

					$ID = $_GET['id'];
					$count =	checkData("ItemID", "items", $ID);

					if ($count == 1) {
						$stmt = $con->prepare("DELETE FROM items WHERE ItemID=:i");
						$stmt->bindParam(":i", $ID);
						$stmt->execute();
						$row = $stmt->rowCount();
						if ($row > 0) {
							redirect_success($row . ' ITEM DELETED', 'back', 3);
						} else {
							redirect_error("feild to delete", 'back', 3);
						}
					}
				}
			}
			//-----------------------end delete page-------------------//

			//start approve page
			elseif ($do === "approve") {
				if (isset($_SERVER['HTTP_REFERER']) && isset($_GET['id'])) {
					$ID = $_GET['id'];
					if (checkData("ItemID", "items", $ID) == 1) {

						$stmt = $con->prepare("UPDATE items SET Approve=1 WHERE ItemID= ?");
						$stmt->execute(array($ID));
						$row = $stmt->rowCount();
						//set notification
						if ($stmt) {
							$stmt4 = $con->prepare('SELECT Member_ID,Name FROM items WHERE ItemID=?');
							$stmt4->execute(array($ID));
							$u_n_id = $stmt4->fetch();
							$n = 'Your Item  \" ' . $u_n_id['Name'] . ' \" has been approved ';
							$stmt_n = $con->prepare("INSERT INTO users_notification (notification,date,user_id)
																values(?,now(),?)");
							$stmt_n->execute(array($n, $u_n_id['Member_ID']));
						} //end set notification
						if ($row == 1) {
							redirect_success("Approved", "back", $second = 3);
						}
					} else {
						redirect_error("there is no itemswith this id", "back", $second = 3);
					}
				} else {
					redirect_error("dont try to access here directly", "back", $second = 3);
				}
			} //end approve page



			//start approve All page
			elseif ($do === "approveall") {
				if (isset($_SERVER['HTTP_REFERER'])) {
					$_GET['approveall'];
					if ($_GET['approveall'] === 'all') {
						$get_pending_items = GetAllFrom('Member_ID,Name', 'items', 'WHERE Approve = 0', 'ItemID');

						$stmt = $con->prepare("UPDATE items SET Approve = 1 ");
						$stmt->execute();
						$row = $stmt->rowCount();
						//set notification
						if ($stmt) {
							foreach ($get_pending_items as $item) {
								$n = 'Your Item  \" ' . $item['Name'] . ' \" has been approved ';
								$stmt_n = $con->prepare("INSERT INTO users_notification (notification,date,user_id)
																values(?,now(),?)");
								$stmt_n->execute(array($n, $item['Member_ID']));
							}
						} //end set notification
						if ($row > 0) {
							redirect_success("ALL Items Approved ", "items.php", $second = 3);
						}
					} else {
						redirect_error("Uncorrect aproval value", "items.php", $second = 3);
					}
				} else {
					redirect_error("dont try to access here directly", "items.php", $second = 3);
				}
			} //end approve All page



			include $templates . 'footer.php';
		} else {
			header("location: index.php");
		}




		ob_end_flush();


		?>