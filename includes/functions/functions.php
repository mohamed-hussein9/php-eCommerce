<?php
ob_start();


function GetAllFrom($field, $table, $where = null, $orderBy = null, $ordering = 'DESC', $type_fetch = null)
{

	global $con;
	$stmt3 = $con->prepare("SELECT $field FROM $table  $where ORDER BY $orderBy  $ordering ");
	$stmt3->execute();
	if ($type_fetch == null) {
		$row = $stmt3->fetchAll();
	} else {
		$row = $stmt3->fetch();
	}
	return $row;
}
// get ip address
function getIPAddress()
{
	//whether ip is from the share internet  
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	//whether ip is from the proxy  
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	//whether ip is from the remote address  
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

///////////////////////////////////////
function getTitle()
{
	global $Title;
	if (isset($Title)) {
		echo lang($Title);
	} else {
		echo 'eCommerce';
	}
}

function add_error_p()
{
	echo '<script>$("<p >ERROR</p>").prependTo(".errors_list");</script>';;
}
//=======redirected functions v1.0

//--redirect with error
function redirect_error($errmsg, $url = null, $second = 3)
{
	if ($url === null) {
		$url = 'index.php';
	} elseif ($url == "back") {
		$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
	}


	if ($errmsg == '') {
	}
	$e =  '<div class="errors_list"><p>ERROR </p><div>' . $errmsg . '</div><p class="second">' . $second . '</p></div>';
	if ($errmsg == 'null') {
		$e = '<p class="second ">' . $second . '</p>';
	}
	echo $e;
	echo '<script> var count = ' . $second . '
            function timer() {
                count--;
                if (count <= 0) { clearInterval(counter) }
                $(".second").html(count);
            }
            counter = setInterval(timer, 1000);</script>';
	header("refresh:$second;url=$url");
}
//--redirect with successv1.0
function redirect_success($msg, $url, $second = 3)
{
	if ($url === null) {
		$url = 'index.php';
	} elseif ($url == "back") {
		$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
	}

	echo '<div class="succes"><div>' . $msg . '</div><div><strong></strong></div> </div>';
	echo '<script> var count = ' . $second . '
            function timer() {
                count--;
                if (count <= 0) { clearInterval(counter) }
                $(".succes strong").html(count);
            }
            counter = setInterval(timer, 1000);</script>';
	header("refresh:$second ;url=" . $url . "");
}
/*
		** Chick data function v1.1
		** this function to chech if the data you enter is exist in the database
		** added $value controll in v1.1
		*/
function checkData($select, $table, $value)
{
	global $con;
	$cond = 'WHERE $select=?';
	if ($value === "all") {
		$cond = " ";
		$value = "";
	} else {
		$cond = "WHERE $select=?";
	}
	$statment = $con->prepare("SELECT $select FROM $table  $cond");
	$statment->execute(array($value));

	$count = $statment->rowCount();
	return $count;
}

/*
		** 
		** Get count of the table content 
		**
		*/
function getCount($item, $table, $if = '')
{
	global $con;

	$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table $if ");
	$stmt2->execute();
	$count2 = $stmt2->fetchColumn();
	return $count2;
}

/*
			** funcrion to get latest of item v1.0
			**
			*/
function latest($select, $table, $ordering, $limit)
{
	global $con;
	$stmt3 = $con->prepare("SELECT $select FROM $table ORDER BY $ordering DESC LIMIT $limit");
	$stmt3->execute();
	$row = $stmt3->fetchAll();
	return $row;
}

/* function to check user not activated */
function CheckUserActivate($userst)
{
	global $con;
	$checkuser = $con->prepare("SELECT * FROM users WHERE UserID = ? AND RegStatus = 0");
	$checkuser->execute(array($userst));
	$userstatus = $checkuser->rowCount();
	return $userstatus;
}
