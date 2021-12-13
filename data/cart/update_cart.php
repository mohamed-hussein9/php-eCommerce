<?php
ob_start();
session_start();
include('init.php');
echo getIPAddress();
?>

<?php
if (isset($_POST['amount']) && intval($_POST['amount'])) {
    $stmt = $con->prepare('UPDATE carts SET amount= ? WHERE item_id=? AND ip_address=?');
    $stmt->execute(array($_POST['amount'], $_POST['item_id'], $_POST['ip']));
    if ($stmt) {
        $stmt = $con->prepare('SELECT amount FROM carts WHERE item_id=? AND ip_address=? ');
        $stmt->execute(array($_POST['item_id'], $_POST['ip']));
        $res = $stmt->fetch();
        echo $res['amount'];
    }
} else {
}
