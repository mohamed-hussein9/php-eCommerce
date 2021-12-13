<?php
session_start();
$no_nav = '';
$Title = "LOGIN";


if (isset($_SESSION['Username'])) {
	header("location: dashboard.php");
	exit();
}


include('init.php');




if ($_SERVER['REQUEST_METHOD'] == 'POST') {


	$user = $_POST['Username'];
	$pass = $_POST['Password'];
	$en_pass = sha1($pass);
	//chech if the user exests in the database
	$stmt = $con->prepare("SELECT UserID, Username,Password FROM users WHERE Username=? and Password=? and GroupID = 1 limit 1");
	$stmt->execute(array($user, $en_pass));
	$row = $stmt->fetch();
	$count = $stmt->rowCount();
	if ($count == 1) {
		$_SESSION['Username'] = $row['Username'];
		$_SESSION['ID'] = $row['UserID'];


		header("location: dashboard.php");
		exit();
	}
}


?>

<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h3>Admin Login</h3>
	<input type="text" placeholder="UserName" name="Username" autocomplete="off" spellcheck="false" />
	<input type="password" placeholder="password" name="Password" autocomplete="off" spellcheck="false" />
	<input type="submit" value="Login" />


</form>

<?php


?>

<?php include($templates . "footer.php");  ?>