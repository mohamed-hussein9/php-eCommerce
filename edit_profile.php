<?php
ob_start();
session_start();
include('init.php');
///======================start edit page
$do = isset($_GET['do']) ? $_GET['do'] : 'manage';

if ($do === 'manage') {
    echo 'you can not access to this page ';
} elseif ($do === 'edit') {
    if (isset($_GET['id'])) {

        $ID = $_GET['id'];
        if ($ID === $_SESSION['UserID']) {
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID=?");
            $stmt->execute(array($ID));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();


            if ($count == 1) {
?>
                <div style="height: 100px;"></div>
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
            header('location:index.php');
        }
    } else {
        header("location: index.php");
    }
}


///end edit page


//==================== start update page

elseif ($do === 'update') {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $ID = $_GET['id'];
        if ($ID === $_SESSION['UserID']) {



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
                    move_uploaded_file($avatar_tmp, 'admin/upload/avatars/' . $rand . '_' . $avatar_name . '');
                    $stmt = $con->prepare("UPDATE users SET Username= ?,Password=? ,Email=? , Fullname=?,avatar=? WHERE 	UserID=? ");
                    $stmt->execute(array($user, $pass, $email, $full, $rand . '_' . $avatar_name, $_GET['id']));
                    if ($stmt) {
                        $bath = 'admin/upload/avatars/' . $avataroldname['avatar'];
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
            // header('location:index.php');
            echo 'id not equal session';
        }
    } else {
        $msg = "you dont have premetion to acces to this page";
        redirect_error($msg, 'members.php', 5);
    }
}
//end update page
?>

<?php include($templates . "footer.php");
ob_end_flush(); ?>