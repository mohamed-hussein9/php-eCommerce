<?php
ob_start();
session_start();
$Title = 'CATEGORIES';
if (isset($_SESSION['Username'])) {
	include 'init.php';
	$do = isset($_GET['do']) ? $_GET['do'] : 'manage';
	if ($do == 'manage') {
		$sort = 'Sort By';
		$order_arr = array(
			'nameASC' => ' Name ASC',
			'nameDESC' => ' Name desc',
			'orderingASC' => ' Ordering asc',
			'orderingDESC' => ' Ordering desc',
			'H-First' => ' Visibility asc',
			'V-First' => ' Visibility desc',
			'DateASC' => ' ID asc',
			'DateDESC' => ' ID desc',
		);
		if (isset($_GET['sort']) && array_key_exists($_GET['sort'], $order_arr)) {
			$sort = $_GET['sort'];
			$r_sort = $order_arr[$sort];
		} else {
			$sort = 'Sort By';
			$r_sort = 'ID';
		}

		$stmt = $con->prepare("SELECT * FROM categories ORDER BY $r_sort");
		$stmt->execute();
		$rows = $stmt->fetchAll();


?>


		<div class="catigories">
			<h1>categories</h1>
			<a class="add_cate" href="?do=add">Add New Category</a>
			<div class="sort_category">
				<div class="c_sort"> <?php echo $sort ?>
					<i class="fas fa-angle-down "></i>
				</div>

				<div class="">
					<div class="sort_list">Name<i class="fas fa-angle-right"></i>
						<div class="L_name">
							<a href="?sort=nameASC">
								<div>Asc</div>
							</a>
							<a href="?sort=nameDESC">
								<div>Desc</div>
							</a>
						</div>
					</div>
					<div class="sort_list">Ordering<i class="fas fa-angle-right"></i>
						<div class="L_order">
							<a href="?sort=orderingASC">
								<div>Asc</div>
							</a>
							<a href="?sort=orderingDESC">
								<div>Desc</div>
							</a>
						</div>



					</div>
					<div class="sort_list">Visbility<i class="fas fa-angle-right"></i>
						<div class="L_visi">
							<a href="?sort=V-First">
								<div>Visible First</div>
							</a>
							<a href="?sort=H-First">
								<div>Hidden First</div>
							</a>

						</div>
					</div>
					<div class="sort_list">Date<i class="fas fa-angle-right"></i>
						<div class="L_date">
							<a href="?sort=DateASC">
								<div>Asc</div>
							</a>
							<a href="?sort=DateDESC">
								<div>Desc</div>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="catigories_list">
				<?php
				foreach ($rows as $row) {
					echo '<div>';
					echo '<div class="cate_option">
					<a href="?do=edit&id=' . $row['ID'] . '"><div class="cate_edit">Edit</div></a>
					<a href="?do=delete&id=' . $row['ID'] . '"><div class="cate_delete confirm"> Delete</div></a>
					</div>';
					echo '<h2>' . $row['Name'] . '</h2>';
					if (!empty($row['Description'])) {
						echo '<p>' . $row['Description'] . '</p>';
					} else {
						echo '<p> No Description</p>';
					}
					if ($row['Visibility'] == '0') {
						echo '<span>Hidden</span>';
					}
					if ($row['Allow_Comment'] == '0') {
						echo '<span>Comments disabled</span>';
					}
					if ($row['Allow_Ads'] == '0') {
						echo '<span>Ads disabled</span>';
					}
					echo '</div>';
				}
				?>
			</div>



		</div>
	<?php
	}


	//========== Start Add Category Page
	elseif ($do == 'add') {

	?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?do=insert" class="edit_profile">
			<h2>Add New Category</h2>
			<div>
				<label for="name">Name</label>
				<input id="name" type="text" name="name" autocomplete="off" spellcheck="false" placeholder="Category Name " required="required">

			</div>
			<div>
				<label for="desc">Description</label>


				<input id="desc" type="text" name="description" placeholder="description of this category">

			</div>
			<div>
				<label for="ord">Ordering</label>
				<input id="ord" type="number" name="ordering" autocomplete="off" spellcheck="false" placeholder="Order ">

			</div>
			<div class="chechboxes">
				<div>
					<input type="checkbox" checked id="visi" name="visibility">
					<label for="visi"> Visible</label>
				</div>
				<div>
					<input type="checkbox" checked id="allow_comments" name="allow_comments">
					<label for="allow_comments"> Allow Comments</label>
				</div>
				<div>
					<input type="checkbox" checked id="allow_ads" name="allow_ads">
					<label for="allow_ads"> Allow Ads</label>
				</div>
			</div>
			<input type="submit" value="Save">
		</form>

		<?php

	}
	//---------- end Add Category Page

	//============start insert page=======//
	elseif ($do == 'insert') {
		$name = $_POST['name'];
		$desc = $_POST['description'];
		$order = $_POST['ordering'];
		$visi = isset($_POST['visibility']) ? 1 : 0;
		$a_comment = isset($_POST['allow_comments']) ? 1 : 0;
		$a_ads = isset($_POST['allow_ads']) ? 1 : 0;
		if (empty($name)) {
			redirect_error('the name of category is Required', 'back', $second = 3);
		} else {
			$count = checkData('Name', 'categories', $name);
			if ($count > 0) {
				redirect_error(
					'This Category is exist please write another name',
					'back',
					$second = 3
				);
			} else {

				$stmt = $con->prepare("INSERT INTO 
					categories(Name,Description,Ordering,Visibility,Allow_Comment,Allow_Ads)
					values(:name,:des,:ord,:visi,:aco,:ads)
					");
				$stmt->execute(array(
					"name" => $name,
					"des" => $desc,
					"ord" => $order,
					"visi" => $visi,
					"aco" => $a_comment,
					"ads" => $a_ads

				));

				redirect_success($name . " Added to Category list ", 'categories.php', $second = 3);
			}
		}
	}

	//----------------end insert category----------//
	//============== start update category============/
	elseif ($do == 'update') {
		$id = $_POST['id'];
		$name = $_POST['name'];
		$desc = $_POST['description'];
		$order = $_POST['ordering'];
		$visi = isset($_POST['visibility']) ? 1 : 0;
		$a_comment = isset($_POST['allow_comments']) ? 1 : 0;
		$a_ads = isset($_POST['allow_ads']) ? 1 : 0;


		if (empty($name)) {
			redirect_error('the name of category is Required', 'back', $second = 5);
		}
		if (!empty($name)) {
			$count = checkData('Name', 'categories', $name);
			if ($count > 0) {

				$stmt = $con->prepare("UPDATE categories SET  
							Name=:name,
							Description = :des,
							Ordering = :ord,
							Visibility = :visi,
							Allow_Comment  = :aco,
							Allow_Ads = :ads
							 WHERE ID = :id
							");
				$stmt->execute(array(
					"name" => $name,
					"des" => $desc,
					"ord" => $order,
					"visi" => $visi,
					"aco" => $a_comment,
					"ads" => $a_ads,
					"id" => $id


				));

				redirect_success($name . "  updated  ", 'categories.php', $second = 3);
			} else {
				redirect_error(
					'there is no category to edit',
					'back',
					$second = 2
				);
			}
		}
	}

	//-------------- end update category------------//

	//=====================start delete page===================//

	elseif ($do == 'delete') {

		$id = $_GET['id'];
		$count = checkData('ID', 'Categories', $id);
		if ($count > 0) {
			$stmt = $con->prepare("DELETE FROM Categories WHERE ID= ?");
			$stmt->execute(array($id));
			redirect_success('Category DELETED', 'back');
		} else {
			redirect_error('No category for this name', 'back');
		}
	}
	//-------------- end delete category------------//


	//=====================start edit page===================//
	elseif ($do == 'edit') {
		if ($_SERVER['HTTP_REFERER'] && is_numeric($_GET['id'])) {
			$id = $_GET['id'];
			$stmt = $con->prepare("select * from categories where ID = ? limit 1");
			$stmt->execute(array($id));
			$rows = $stmt->fetch();
			$count = $stmt->rowCount();


		?>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?do=update" class="edit_profile">
				<h2>Edit Category</h2>
				<div>
					<label for="name">Name</label>
					<input id="name" type="text" name="name" autocomplete="off" spellcheck="false" value="<?php echo $rows['Name'] ?>" placeholder="Category Name ">
					<!-- required="required" -->

				</div>
				<div>
					<label for="desc">Description</label>


					<input id="desc" type="text" name="description" placeholder="description of this category" value="<?php echo $rows['Description'] ?>">

				</div>
				<div>
					<label for="ord">Ordering</label>
					<input id="ord" type="number" name="ordering" autocomplete="off" spellcheck="false" placeholder="Order " value="<?php echo $rows['Ordering'] ?>">

				</div>
				<div class="chechboxes">

					<div>
						<input type="checkbox" <?php if ($rows['Visibility'] == 1) {
													echo 'checked';
												} ?> id="visi" name="visibility">
						<label for="visi"> Visible</label>

					</div>
					<div>
						<input type="checkbox" id="allow_comments" name="allow_comments" <?php if ($rows['Allow_Comment'] == 1) {
																								echo 'checked';
																							} ?>>
						<label for="allow_comments"> Allow Comments</label>

					</div>
					<div>
						<input type="checkbox" id="allow_ads" name="allow_ads" <?php if ($rows['Allow_Ads'] == 1) {
																					echo 'checked';
																				} ?>>
						<label for="allow_ads"> Allow Ads</label>

					</div>
					<input type="hidden" name="id" value=" <?php echo $rows['ID']; ?>" />



				</div>
				<input type="submit" value="Save">

			</form>

<?php
		} else {
			header("location: categories.php");
		}
	}

	//-----------------------end edit page--------------------//


	include $templates . 'footer.php';
} else {
	header("location: index.php");
}




ob_end_flush();


?>