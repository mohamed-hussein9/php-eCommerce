<?php
ob_start();
session_start();
$Title = 'SIGN-UP';
if (isset($_SESSION['User'])) {
    header("location: index.php");
    exit();
}

include 'init.php'; ?>
<div class="login_con">



    <div class="signup">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

            <div>SIGN UP</div>
            <label><input class="un" type="text" placeholder="UserName" name="username" required="required"></label>
            <label><input type="password" placeholder="Password" name="password" required="required"></label>
            <label><input class="re" type="password" placeholder="Retype Password" name="re_password" required="required"></label>
            <label><input type="email" placeholder="Email" name="email" required="required"></label>
            <input type="submit" value="Sign Up">
        </form>
        <br>
        <span>back to </span><a href="login.php">Login</a>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $pass = $_POST['password'];
    $repass = $_POST['re_password'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    /* start validation */
    if ($pass !== $repass) {
        $errors_sign = array();

        echo '<script>
              $(".re").css("border", "2px solid red");
              </script>';
        $errors_sign[] = ' <div class="signup_err">The passwords do not match</div>';
    }
    if (strlen($user) > 3 && strlen($user) < 20) {
    } else {
        echo '<script>
                $(".un").css("border", "2px solid red");
                </script>';
        $errors_sign[] = '<div class="signup_err">The Username must be between 3-20 Characters</div>';
    }
    if (empty($user) || empty($pass) || empty($repass) || empty($email)) {

        $errors_sign[] = '<div class="signup_err">dont leave any feild empty</div>';
    }
    /** end validation  */
    if (empty($errors_sign)) {
        $check = checkData('Username', 'users', $user);
        if ($check > 0) {
            echo '<script>
                                $(".un").css("border", "2px solid red");
                                </script>';
            echo '<div class="signup_err">' . $user . ' is used before please change the username</div>';
        } else {
            $stmt = $con->prepare('INSERT INTO users (Username , Password , Email , Date)
                                            values(?,?,?,now())');
            $stmt->execute(array($user, sha1($pass), $email));
            $check = checkData('Username', 'users', $user);
            if ($check > 0) {
                $stmt2 = $con->prepare("SELECT * FROM users WHERE Username=? ");
                $stmt2->execute(array($user));
                $result_2 = $stmt2->fetch();
                $_SESSION['User'] = $result_2['Username'];
                $_SESSION['UserID'] = $result_2['UserID'];
            }
            header("location: index.php");
        }
    } else {
        foreach ($errors_sign as $er) {
            echo $er;
        }
    }
}
?>
<?php include $templates . 'footer.php';
ob_end_flush(); ?>