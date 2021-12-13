<?php
ob_start();
session_start();
$Title = 'PROFILE';

include('init.php');
if (isset($_GET['user_id'])) {
    $uid = $_GET['user_id'];


    $getInfo = $con->prepare("SELECT * FROM users WHERE  UserID = ?");
    $getInfo->execute(array($uid));
    if ($getInfo->rowCount() > 0) {
        $UInfo = $getInfo->fetch();
?>
        <div class="profile"> my profile</div>
        <div class="p_information p_list">
            <p class="i_head"> my information </p>
            <?php
            if (CheckUserActivate($uid) == 1) {
                echo '<div style="color: red;">' . 'Waiting for your membership to be accepted' . '</div>';
            }
            ?>
            <div> <span>name</span> : <?php echo $UInfo['Username'] ?></div>
            <div> <span>full name</span> : <?php echo $UInfo['Fullname'] ?></div>
            <div> <span>Email</span> : <?php echo $UInfo['Email'] ?></div>
            <div> <span>rigester date</span> : <?php echo $UInfo['Date'] ?></div>

            <?php if ($_SESSION['UserID'] === $uid) {
                echo '<a href="edit_profile.php?do=edit&id=' . $UInfo['UserID'] . '">Edit My Profile</a>';
            } ?>

        </div>

        <div class="p_information">
            <div class="i_head"> ADS </div>
            <div>
                <?php
                $getitems = $con->prepare("SELECT * FROM items WHERE Member_ID = ? ");
                $getitems->execute(array($UInfo['UserID']));
                $showitems = $getitems->fetchAll();
                if (empty($showitems)) {
                    echo '<div style="padding:10px;width=100%">' . 'You have not posted any advertisements' . '</div>';
                } else {

                    // foreach ($showitems as $showitem) {
                    //     echo 'Name : ';
                    //     echo $showitem['Name'];
                    //     echo '<br>';
                    //     echo 'Price : ';
                    //     echo  $showitem['Price'];
                    //     echo '<br>';
                    //     echo 'Date : ';
                    //     echo  $showitem['Add_Date'];
                    //     echo '<br>--------------------------<br>';
                    // }


                ?>
                    <div class="container">
                        <div class="items items_profile">
                            <?php foreach ($showitems as $item) {
                                $img_name =  $item['Image'] !== '' ? $item['Image'] : "default-store.jpg";
                                echo '<div onclick="location.href =\' items.php?itemid=' . $item['ItemID'] . '\' " >';
                                echo '<img src="admin/upload/item_images/' . $img_name . '" alt="">';
                                echo '<h3>' . $item['Name'] . '</h3>';
                                if ($item['Approve'] == 0) {
                                    echo '<h4> <p>Waiting Approve</p> </h4>';
                                }
                                echo '<h5>' . $item['Description'] . '</h5>';
                                echo '<span class="price">' . $item['Price'] . '</span>';
                                echo '</div>';
                            } ?>


                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
        <?php if ($_SESSION['UserID'] === $uid) { ?>
            <div class="p_information p_list">
                <p class="i_head"> latest comments </p>

                <?php
                $getcomments = $con->prepare("SELECT * FROM comments WHERE user_id = ? ");
                $getcomments->execute(array($UInfo['UserID']));
                $showcomments = $getcomments->fetchAll();
                if (empty($showcomments)) {
                    echo '<div style="padding:10px;width=100%">' . 'You didn\'t post any comment' . '</div>';
                } else {
                    foreach ($showcomments as $showcomment) {
                        echo '<div style="padding:10px;width=100%">' . $showcomment['comment'] . '  ' . $showcomment['comment_date'] . '</div>';
                    }
                }

                ?>




            </div>
        <?php
        } ?>


<?php
    } else {
        echo 'There is no uer with this identification';
    }
} else {
    header("location: index.php");
    exit();
}
include($templates . "footer.php");
ob_end_flush();  ?>