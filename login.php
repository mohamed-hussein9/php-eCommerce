<?php
session_start();
$Title = 'LOGIN';
if (isset($_SESSION['User'])) {
    header("location: index.php");
    exit();
}
include 'init.php'; ?>
<div class="login_con">
    <div class="login">
        <form action="login.php" method="post">
            <div>LOGIN</div>
            <label for=""><input class="err_border" type="text" placeholder="UserName" name="username"></label>
            <label for=""><input class="err_border" type="password" placeholder="Password" name="password"></label>
            <input type="submit" value="LogIn">
        </form>
        <br>
        <span>you dont have account</span> <a href="signup.php">sign up</a>
    </div>



</div>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $hashpass = sha1($pass);
    $stmt = $con->prepare('SELECT  * FROM users
                        WHERE   Username= ? and Password= ?');
    $stmt->execute(array($user, $hashpass));
    $result = $stmt->rowCount();
    $row = $stmt->fetch();
    if ($result == 1) {
        $_SESSION['User'] = $row['Username'];
        $_SESSION['UserID'] = $row['UserID'];
        header("location: index.php");
        exit();
    } else {

        echo '<script>
              $(".err_border").css("border", "2px solid red");
              </script>';
        echo '<div class="signup_err">You enterd wrong username or password</div>';
    }
}
?>


<?php include $templates . 'footer.php'; ?>