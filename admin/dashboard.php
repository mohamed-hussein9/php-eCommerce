<?php
session_start();
$Title = 'DASHBOARD';
if (isset($_SESSION['Username'])) {
	include 'init.php';


?>
	<div class="dashboard_container">
		<h1>Dashboard</h1>
		<div class="d_statistics">
			<div class="" onclick="location.href='members.php'">

				<div>Total Members</div>
				<div><a href="members.php"><?php echo checkData("RegStatus", "users", "all"); ?></a></div>
			</div>
			<div class="" onclick="location.href='members.php?mum=pending'">
				<div>Pending Members</div>
				<div> <a href="members.php?mum=pending"><?php echo checkData("RegStatus", "users", 0);  ?></a></div>

			</div>
			<div class="" onclick="location.href='items.php'">
				<div>Total Items</div>
				<div> <a href="items.php"><?php echo checkData("ItemID", "items", 'all');  ?></a></div>
			</div>
			<div class="" onclick="location.href='comments.php'">
				<div>Total Comments</div>
				<div> <a href="comments.php"><?php echo checkData("comment_id", "comments", 'all');  ?></a></div>
			</div>

		</div>
		<div class="d_latest">
			<Div>

				<?php
				$l_u_limit = 5;
				echo '<div>Latest ' . $l_u_limit . ' Register User</div>';
				$result = GetAllFrom("*", "users", "", "UserID", "DESC", "", "LIMIT " . $l_u_limit . "");

				foreach ($result as $row) {
					$avatar_src = !empty($row['avatar']) ? $row['avatar'] : 'default.png';
					echo '<div class="latest_users">';
					echo '<img src="upload/avatars/' . $avatar_src . '" alt="">';
					echo '<a href="members.php?do=manage&userid=' . $row['UserID'] . '">';
					echo $row['Username'];
					echo '</a>';
					if ($row['RegStatus'] == 0) {
						echo '<small>Not Activated</small>';
					}


					echo '</div>';
				}
				?>
			</Div>
			<Div>

				<?php
				$l_i_limit = 5;
				echo '<div>Latest ' . $l_i_limit . ' Items</div>';
				$stmt2 = $con->prepare("select 
													 items.*,
													 categories.Name as CategoryName,
													 users.Username ,
													 users.UserID
											  FROM items
											  INNER JOIN categories 
														on categories.ID=items.Cat_ID
											  INNER JOIN users
														  on users.UserID=items.Member_ID
														  ORDER BY ItemID DESC
                                                          LIMIT " . $l_i_limit . "");

				$stmt2->execute();
				$result2 =  $stmt2->fetchAll();
				foreach ($result2 as $row2) {
					echo '<div>';
					echo '<a href="items.php?do=manage&itemid=' . $row2['ItemID'] . '">';
					echo $row2['Name'];
					echo '</a>';
					echo '<span> Posted By <a href="members.php?do=manage&userid=' . $row2['UserID'] . '">' . $row2['Username'] . '</a></span>';
					if ($row2['Approve'] == 0) {
						echo '<small>  pending</small>';
					}


					echo '</div>';
				}
				?>

			</Div>

			<Div>

				<?php
				$l_c_limit = 5;
				echo '<div>Latest ' . $l_c_limit . ' Comments</div>';
				$stmt3 = $con->prepare("select 
													comments.*,
													items.Name as ItemName,
													users.Username ,
													users.avatar
											from comments
											INNER JOIN items 
														on items.ItemID=comments.item_id
											INNER JOIN users
														on users.UserID=comments.user_id
														ORDER BY comment_id DESC
                                                        LIMIT " . $l_c_limit . "");

				$stmt3->execute();
				$result3 =  $stmt3->fetchAll();
				foreach ($result3 as $row3) {
					echo '<div class="show_latest" style="';
					if ($row3['status'] == 0) {
						echo 'background:#ff9e9e;';
					}
					echo '">';
					$avatar_src = $row3['avatar'] !== "" ? $row3['avatar'] : 'default.png';


					echo '<span class="c_user">  ' . '<img src="upload/avatars/' . $avatar_src . '" alt=""><a href="#">' . $row3['Username'] . ':</a> </span> ';
					echo '<div class="L_comment"><a href="comments.php?do=manage&comment_id=' . $row3['comment_id'] . '">';
					echo $row3['comment'];
					echo '</a></div>';

					if ($row3['status'] == 0) {
						echo '<small>  pending</small>';
					}


					echo '</div>';
				}
				?>

			</Div>

		</div>


	</div>

<?php

} else {
	header("location: index.php");
	exit();
}

include $templates . 'footer.php'
?>